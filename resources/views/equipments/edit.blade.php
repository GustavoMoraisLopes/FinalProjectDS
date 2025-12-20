@extends('layouts.app')

@section('title', 'Editar Equipamento')
@section('page-title', 'Editar Equipamento')
@section('page-subtitle', 'Atualizar detalhes do equipamento')

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
                <h5 class="card-title mb-3">{{ $equipment->name }}</h5>
                <div class="text-start">
                    <small class="d-block mb-2">
                        <i class="bi bi-tag"></i> <strong>Nome:</strong> <span id="preview-name">{{ $equipment->name }}</span>
                    </small>
                    <small class="d-block mb-2">
                        <i class="bi bi-hash"></i> <strong>Série:</strong> <span id="preview-serial">{{ $equipment->serial_number }}</span>
                    </small>
                    <small class="d-block mb-2">
                        <i class="bi bi-bookmark"></i> <strong>Categoria:</strong> <span id="preview-category">{{ $equipment->category->name }}</span>
                    </small>
                    <small class="d-block mb-2">
                        <i class="bi bi-geo-alt"></i> <strong>Localização:</strong> <span id="preview-location">{{ $equipment->location ?? '-' }}</span>
                    </small>
                    <small class="d-block">
                        <i class="bi bi-circle-fill"></i> <strong>Estado:</strong>
                        <span id="preview-status" class="badge
                            @if($equipment->status == 'available') bg-success
                            @elseif($equipment->status == 'loaned') bg-warning
                            @elseif($equipment->status == 'maintenance') bg-danger
                            @else bg-secondary @endif">
                            {{ ucfirst($equipment->status) }}
                        </span>
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
                    <i class="bi bi-pencil-square"></i> Editar Dados do Equipamento
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('equipments.update', $equipment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Upload de Imagem -->
                    <div class="mb-4">
                        <label class="form-label">Foto do Equipamento</label>
                        <div class="text-center">
                            @if($equipment->image)
                                <img src="{{ asset('storage/' . $equipment->image) }}" alt="Imagem" class="equipment-preview rounded mb-3" id="equipmentImagePreview" style="width: 180px; height: 180px; object-fit: cover;">
                            @else
                                <div class="equipment-preview-placeholder rounded mb-3 mx-auto" id="equipmentImagePreviewPlaceholder" style="width: 180px; height: 180px; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 8px;">
                                    <i class="bi bi-image" style="font-size: 3rem; color: #9ca3af;"></i>
                                </div>
                                <img src="" alt="Imagem" class="equipment-preview rounded mb-3 d-none" id="equipmentImagePreview" style="width: 180px; height: 180px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="d-flex gap-2 justify-content-center">
                            <label for="image" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-upload"></i> Escolher Foto
                            </label>
                            <input type="file" class="d-none @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" onchange="previewEquipmentImage(event)">
                            @if($equipment->image)
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEquipmentImage()">
                                    <i class="bi bi-trash"></i> Remover
                                </button>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-2">JPG, PNG ou GIF (máx. 2MB)</small>
                        @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <hr class="mb-3">

                    <!-- Informação Básica -->
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-info-circle"></i> Informação Básica
                    </h6>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Equipamento *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $equipment->name) }}" required
                               oninput="updatePreview()">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Categoria *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required onchange="updatePreview()">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $equipment->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="serial_number" class="form-label">Número de Série *</label>
                            <input type="text" class="form-control @error('serial_number') is-invalid @enderror"
                                   id="serial_number" name="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}" required
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
                            <input type="text" class="form-control" id="location" name="location"
                                   value="{{ old('location', $equipment->location) }}"
                                   oninput="updatePreview()">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Estado *</label>
                            <select class="form-select" id="status" name="status" required onchange="updatePreview()">
                                <option value="available" {{ old('status', $equipment->status) == 'available' ? 'selected' : '' }}>Disponível</option>
                                <option value="loaned" {{ old('status', $equipment->status) == 'loaned' ? 'selected' : '' }}>Emprestado</option>
                                <option value="maintenance" {{ old('status', $equipment->status) == 'maintenance' ? 'selected' : '' }}>Manutenção</option>
                                <option value="unavailable" {{ old('status', $equipment->status) == 'unavailable' ? 'selected' : '' }}>Indisponível</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="condition" class="form-label">Condição</label>
                        <select class="form-select" id="condition" name="condition">
                            <option value="">Selecionar...</option>
                            <option value="Excelente" {{ old('condition', $equipment->condition) == 'Excelente' ? 'selected' : '' }}>Excelente</option>
                            <option value="Bom" {{ old('condition', $equipment->condition) == 'Bom' ? 'selected' : '' }}>Bom</option>
                            <option value="Usado" {{ old('condition', $equipment->condition) == 'Usado' ? 'selected' : '' }}>Usado</option>
                            <option value="Avariado" {{ old('condition', $equipment->condition) == 'Avariado' ? 'selected' : '' }}>Avariado</option>
                        </select>
                    </div>

                    <hr class="my-4">

                    <!-- Informação Financeira -->
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-cash-coin"></i> Informação Financeira
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="purchase_date" class="form-label">Data de Compra</label>
                            <input type="text" class="form-control datepicker-any @error('purchase_date') is-invalid @enderror" id="purchase_date" name="purchase_date"
                                value="{{ old('purchase_date', optional($equipment->purchase_date)->format('d/m/Y')) }}" placeholder="dd/mm/aaaa">
                            @error('purchase_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="purchase_price" class="form-label">Preço de Compra (€)</label>
                            <input type="number" step="0.01" class="form-control" id="purchase_price"
                                   name="purchase_price" value="{{ old('purchase_price', $equipment->purchase_price) }}" placeholder="0.00">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Descrição -->
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-card-text"></i> Descrição Adicional
                    </h6>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                                  placeholder="Informações adicionais sobre o equipamento...">{{ old('description', $equipment->description) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Guardar Alterações
                        </button>
                        <a href="{{ route('equipments.show', $equipment) }}" class="btn btn-secondary">
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
        const nameVal = document.getElementById('name')?.value || '-';
        document.getElementById('preview-name').textContent = nameVal;

        const serialVal = document.getElementById('serial_number')?.value || '-';
        document.getElementById('preview-serial').textContent = serialVal;

        const categorySelect = document.getElementById('category_id');
        const categoryText = categorySelect?.options[categorySelect.selectedIndex]?.text || '-';
        document.getElementById('preview-category').textContent = categoryText;

        const locVal = document.getElementById('location')?.value || '-';
        document.getElementById('preview-location').textContent = locVal;

        const statusSelect = document.getElementById('status');
        const status = statusSelect?.value || '';
        const statusText = statusSelect?.options[statusSelect.selectedIndex]?.text || '-';
        const statusBadge = document.getElementById('preview-status');
        statusBadge.textContent = statusText;
        statusBadge.className = 'badge';
        switch (status) {
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

    function previewEquipmentImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('equipmentImagePreview');
            const placeholder = document.getElementById('equipmentImagePreviewPlaceholder');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    function removeEquipmentImage() {
        const form = document.querySelector('form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_image';
        input.value = '1';
        form.appendChild(input);
        form.submit();
    }
</script>
@endsection
