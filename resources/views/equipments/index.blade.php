@extends('layouts.app')

@section('title', 'Inventário')
@section('page-title', 'Inventário')
@section('page-subtitle', 'Geri equipamentos e stock.')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2 flex-fill" style="max-width: 500px;">
        <input type="text" id="globalSearch" class="form-control" placeholder="Pesquisar em tudo..." autocomplete="off">

        <select id="globalStatus" class="form-select" style="min-width: 150px;">
            <option value="">Todos os Estados</option>
            <option value="available">Disponível</option>
            <option value="loaned">Emprestado</option>
            <option value="maintenance">Manutenção</option>
            <option value="unavailable">Indisponível</option>
        </select>

        <button type="button" id="clearAllFilters" class="btn btn-outline-secondary" title="Limpar filtros">
            <i class="bi bi-x-circle"></i>
        </button>
    </div>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('equipments.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-circle"></i> Adicionar Equipamento
    </a>
    @endif
</div>

<!-- Acordeão de Tipos de Equipamento -->
<div class="accordion" id="typesAccordion">
    @php
        // Agrupar equipamentos por tipo
        $equipmentsByType = $equipments->groupBy(fn($eq) => $eq->equipmentType->name ?? 'Sem Tipo')
            ->sortKeys();
    @endphp

    @forelse($equipmentsByType as $typeName => $typeEquipments)
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button{{ $loop->first ? '' : ' collapsed' }}" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseType{{ $loop->index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                    <i class="bi bi-collection me-2"></i>
                    <strong>{{ $typeName }}</strong>
                    <span class="badge bg-secondary ms-2">{{ $typeEquipments->count() }}</span>
                </button>
            </h2>
            <div id="collapseType{{ $loop->index }}" class="accordion-collapse collapse{{ $loop->first ? ' show' : '' }}"
                 data-bs-parent="#typesAccordion">
                <div class="accordion-body p-0">
                    @if($typeEquipments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-equipment table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Equipamento</th>
                                        <th>S/N</th>
                                        <th>Categoria</th>
                                        <th>Localização</th>
                                        <th>Estado</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($typeEquipments as $equipment)
                                    <tr class="equipment-row" data-search="{{ strtolower($equipment->name . ' ' . $equipment->serial_number) }}"
                                        data-status="{{ $equipment->status }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="equipment-img bg-light me-2 d-flex align-items-center justify-content-center">
                                                    @if($equipment->image)
                                                        <img src="{{ asset('storage/' . $equipment->image) }}" alt="{{ $equipment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="bi bi-box-seam text-muted"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $equipment->name }}</div>
                                                    <small class="text-muted">{{ $equipment->condition }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code>{{ $equipment->serial_number }}</code></td>
                                        <td>
                                            @if($equipment->category)
                                                <span class="badge bg-light text-dark">{{ $equipment->category->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $equipment->location }}</td>
                                        <td>
                                            @if($equipment->status == 'available')
                                                <span class="badge status-available">Disponível</span>
                                            @elseif($equipment->status == 'loaned')
                                                <span class="badge status-loaned">Emprestado</span>
                                            @elseif($equipment->status == 'maintenance')
                                                <span class="badge status-maintenance">Manutenção</span>
                                            @else
                                                <span class="badge bg-secondary">Indisponível</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('equipments.show', $equipment) }}" class="btn btn-icon btn-outline-info" title="Ver detalhes" aria-label="Ver detalhes">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if(auth()->user()->isAdmin())
                                                <a href="{{ route('equipments.edit', $equipment) }}" class="btn btn-icon btn-outline-warning" title="Editar" aria-label="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <form method="POST" action="{{ route('equipments.destroy', $equipment) }}" style="display: inline;" onsubmit="return confirm('Tem certeza que quer remover este equipamento?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-outline-danger" title="Remover" aria-label="Remover">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                @endif

                                                @if($equipment->status === 'available' && !auth()->user()->isAdmin())
                                                <a href="{{ route('reservations.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-icon btn-outline-success" title="Requisitar" aria-label="Requisitar">
                                                    <i class="bi bi-plus-circle"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">Nenhum equipamento neste tipo</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-warning">
            Nenhum equipamento disponível
        </div>
    @endforelse
</div>

<style>
    .equipment-img {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        overflow: hidden;
        flex: 0 0 44px;
    }
    .equipment-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .btn-icon {
        --size: 36px;
        width: var(--size);
        height: var(--size);
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.1rem;
        line-height: 1;
        transition: all 0.15s ease;
        border: none;
        background: #f9fafb;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .btn-icon:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.12);
    }
    .btn-icon:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
    }
    .btn-outline-info {
        color: #fff;
        background: linear-gradient(135deg, #5bd1e1 0%, #0ea5b7 100%);
    }
    .btn-outline-info:hover {
        background: linear-gradient(135deg, #3ec4d8 0%, #0d94a0 100%);
    }
    .btn-outline-warning {
        color: #fff;
        background: linear-gradient(135deg, #f7b267 0%, #f59e0b 100%);
    }
    .btn-outline-warning:hover {
        background: linear-gradient(135deg, #f5a24d 0%, #d97706 100%);
    }
    .btn-outline-danger {
        color: #fff;
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    }
    .btn-outline-danger:hover {
        background: linear-gradient(135deg, #f55555 0%, #dc2626 100%);
    }
    .btn-outline-success {
        color: #fff;
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    }
    .btn-outline-success:hover {
        background: linear-gradient(135deg, #2cc48f 0%, #059669 100%);
    }

    .accordion-button:not(.collapsed) {
        background-color: #f0f4ff;
        color: #667eea;
    }
    .accordion-button:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.2);
    }

    .status-available {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%) !important;
        color: white;
    }
    .status-loaned {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        color: white;
    }
    .status-maintenance {
        background: linear-gradient(135deg, #f87171 0%, #dc2626 100%) !important;
        color: white;
    }

    .table-equipment {
        font-size: 0.95rem;
    }
    .table-equipment tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05) !important;
    }
</style>

<script>
    const searchInput = document.getElementById('globalSearch');
    const statusSelect = document.getElementById('globalStatus');
    const clearBtn = document.getElementById('clearAllFilters');

    searchInput.addEventListener('input', filterEquipments);
    statusSelect.addEventListener('change', filterEquipments);
    clearBtn.addEventListener('click', clearFilters);

    function filterEquipments() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusFilter = statusSelect.value;
        const rows = document.querySelectorAll('.equipment-row');

        rows.forEach(row => {
            const searchText = row.dataset.search;
            const status = row.dataset.status;

            const matchesSearch = !searchTerm || searchText.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    function clearFilters() {
        searchInput.value = '';
        statusSelect.value = '';
        filterEquipments();
    }
</script>
@endsection
