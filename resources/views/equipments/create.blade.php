@extends('layouts.app')

@section('title', 'Novo Equipamento')
@section('page-title', 'Novo Equipamento')
@section('page-subtitle', 'Adicionar equipamento ao inventário')

@section('content')
<div class="row">
    <!-- Preview Card -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <div class="equipment-icon-preview">
                        <i class="bi bi-laptop"></i>
                    </div>
                </div>
                <h5 class="card-title mb-3">Preview do Equipamento</h5>
                <div class="text-start">
                    <small class="d-block mb-2">
                        <i class="bi bi-tag"></i> <strong>Nome:</strong> <span id="preview-name" class="text-muted">-</span>
                    </small>
                    <small class="d-block mb-2">
                        <i class="bi bi-hash"></i> <strong>Série:</strong> <span id="preview-serial" class="text-muted">-</span>
                    </small>
                    <small class="d-block mb-2">
                        <i class="bi bi-bookmark"></i> <strong>Categoria:</strong> <span id="preview-category" class="text-muted">-</span>
                    </small>
                    <small class="d-block mb-2">
                        <i class="bi bi-geo-alt"></i> <strong>Localização:</strong> <span id="preview-location" class="text-muted">-</span>
                    </small>
                    <small class="d-block">
                        <i class="bi bi-circle-fill"></i> <strong>Estado:</strong> <span id="preview-status" class="badge bg-secondary">-</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Dados do Equipamento
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('equipments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Informação Básica -->
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-info-circle"></i> Informação Básica
                    </h6>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Equipamento *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required
                               oninput="updatePreview()">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Categoria *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required onchange="updatePreview()">
                                <option value="">Selecionar categoria...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="serial_number" class="form-label">Número de Série *</label>
                            <input type="text" class="form-control @error('serial_number') is-invalid @enderror"
                                   id="serial_number" name="serial_number" value="{{ old('serial_number') }}" required
                                   oninput="updatePreview()">
                            @error('serial_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Localização e Estado -->
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-geo-alt"></i> Localização e Estado
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Localização</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                   id="location" name="location" placeholder="Ex: Armário A1" value="{{ old('location') }}"
                                   oninput="updatePreview()">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Estado *</label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status" required onchange="updatePreview()">
                                <option value="">Selecionar estado...</option>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponível</option>
                                <option value="loaned" {{ old('status') == 'loaned' ? 'selected' : '' }}>Emprestado</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Manutenção</option>
                                <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Indisponível</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="condition" class="form-label">Condição</label>
                        <select class="form-select @error('condition') is-invalid @enderror" id="condition" name="condition">
                            <option value="">Selecionar...</option>
                            <option value="Excelente" {{ old('condition') == 'Excelente' ? 'selected' : '' }}>Excelente</option>
                            <option value="Bom" {{ old('condition') == 'Bom' ? 'selected' : '' }}>Bom</option>
                            <option value="Usado" {{ old('condition') == 'Usado' ? 'selected' : '' }}>Usado</option>
                            <option value="Avariado" {{ old('condition') == 'Avariado' ? 'selected' : '' }}>Avariado</option>
                        </select>
                        @error('condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">

                    <!-- Informação Financeira -->
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-cash-coin"></i> Informação Financeira
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="purchase_date" class="form-label">Data de Compra</label>
                            <input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
                                   id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
                            @error('purchase_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="purchase_price" class="form-label">Preço de Compra (€)</label>
                            <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror"
                                   id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" placeholder="0.00">
                            @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Descrição -->
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-card-text"></i> Descrição Adicional
                    </h6>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4"
                                  placeholder="Informações adicionais sobre o equipamento...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Guardar Equipamento
                        </button>
                        <a href="{{ route('equipments.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .equipment-icon-preview {
        font-size: 5rem;
        color: #667eea;
        margin-bottom: 1rem;
    }
</style>

<script>
    function updatePreview() {
        // Nome
        const name = document.getElementById('name').value || '-';
        document.getElementById('preview-name').textContent = name;

        // Série
        const serial = document.getElementById('serial_number').value || '-';
        document.getElementById('preview-serial').textContent = serial;

        // Categoria
        const categorySelect = document.getElementById('category_id');
        const category = categorySelect.options[categorySelect.selectedIndex]?.text || '-';
        document.getElementById('preview-category').textContent = category;

        // Localização
        const location = document.getElementById('location').value || '-';
        document.getElementById('preview-location').textContent = location;

        // Estado
        const statusSelect = document.getElementById('status');
        const status = statusSelect.value;
        const statusText = statusSelect.options[statusSelect.selectedIndex]?.text || '-';
        const statusBadge = document.getElementById('preview-status');

        statusBadge.textContent = statusText;
        statusBadge.className = 'badge';

        switch(status) {
            case 'available':
                statusBadge.classList.add('bg-success');
                break;
            case 'loaned':
                statusBadge.classList.add('bg-warning');
                break;
            case 'maintenance':
                statusBadge.classList.add('bg-danger');
                break;
            default:
                statusBadge.classList.add('bg-secondary');
        }
    }

    // Atualizar preview ao carregar se houver old values
    document.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endsection
