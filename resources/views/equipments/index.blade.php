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
                            <a href="{{ route('equipments.show', $equipment) }}" class="action-icon text-info" title="Ver detalhes">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('equipments.edit', $equipment) }}" class="action-icon text-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('equipments.destroy', $equipment) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover este equipamento?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link action-icon text-danger p-0" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
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

    // Função para submeter o formulário automaticamente
    function autoSubmit() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            form.submit();
        }, 500); // Espera 500ms após o usuário parar de digitar
    }

    // Event listeners para filtros dinâmicos
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

    // Feedback visual ao filtrar
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

    /* Melhorar visual dos filtros */
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
</style>
@endsection
