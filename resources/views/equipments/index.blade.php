@extends('layouts.app')

@section('title', 'Inventário')
@section('page-title', 'Inventário')
@section('page-subtitle', 'Geri equipamentos e stock.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2 flex-fill" style="max-width: 800px;">
        <form method="GET" action="{{ route('equipments.index') }}" class="d-flex gap-2 flex-fill" id="filterForm">
            <input type="text"
                   name="search"
                   id="searchInput"
                   class="form-control"
                   placeholder="Pesquisar por nome ou serial..."
                   value="{{ request('search') }}"
                   autocomplete="off">

            <select name="category" id="categorySelect" class="form-select" style="min-width: 180px;">
                <option value="">Todas as Categorias</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" id="statusSelect" class="form-select" style="min-width: 180px;">
                <option value="">Todos os Estados</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponível</option>
                <option value="loaned" {{ request('status') == 'loaned' ? 'selected' : '' }}>Emprestado</option>
                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Manutenção</option>
                <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Indisponível</option>
            </select>

            <button type="button" id="clearFilters" class="btn btn-outline-secondary" title="Limpar filtros">
                <i class="bi bi-x-circle"></i>
            </button>
        </form>
    </div>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('equipments.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-circle"></i> Adicionar Equipamento
    </a>
    @endif
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-equipment table-hover mb-0">
                <thead>
                    <tr>
                        <th>Equipamento</th>
                        <th>Categoria</th>
                        <th>S/N</th>
                        <th>Localização</th>
                        <th>Estado</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipments as $equipment)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="equipment-img bg-light me-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-box-seam text-muted"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $equipment->name }}</div>
                                    <small class="text-muted">{{ $equipment->condition }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $equipment->category->name }}</td>
                        <td><code>{{ $equipment->serial_number }}</code></td>
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

                                <form action="{{ route('equipments.destroy', $equipment) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover este equipamento?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-outline-danger" title="Eliminar" aria-label="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Nenhum equipamento encontrado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $equipments->links() }}
</div>

<script>
    // Filtro dinâmico em tempo real
    let debounceTimer;
    const form = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const statusSelect = document.getElementById('statusSelect');
    const clearBtn = document.getElementById('clearFilters');

    function autoSubmit() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            form.submit();
        }, 500);
    }

    searchInput.addEventListener('input', autoSubmit);
    categorySelect.addEventListener('change', () => {
        form.submit();
    });
    statusSelect.addEventListener('change', () => {
        form.submit();
    });

    // Botão limpar filtros
    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        categorySelect.value = '';
        statusSelect.value = '';
        form.submit();
    });

    let isFiltering = false;
    form.addEventListener('submit', () => {
        if (!isFiltering) {
            isFiltering = true;
            const submitBtn = document.createElement('div');
            submitBtn.className = 'filtering-indicator';
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Filtrando...';
            form.appendChild(submitBtn);
        }
    });
</script>

<style>
    .filtering-indicator {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #667eea;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .spin {
        display: inline-block;
        animation: spin 1s linear infinite;
    }

    #searchInput:focus,
    #categorySelect:focus,
    #statusSelect:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    #clearFilters {
        transition: all 0.3s ease;
    }

    #clearFilters:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
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
</style>
@endsection
