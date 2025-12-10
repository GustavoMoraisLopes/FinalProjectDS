@extends('layouts.app')

@section('title', 'Nova Requisição')
@section('page-title', 'Nova Requisição')
@section('page-subtitle', 'Requisitar um equipamento.')

@section('content')
<div class="row">
    <!-- Preview / Resumo -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <div class="equipment-icon-preview">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
                <h5 class="card-title mb-3">Resumo da Requisição</h5>
                <div class="text-start small">
                    <div class="mb-2">
                        <strong>Equipamento:</strong>
                        <div id="preview-name" class="text-muted">Selecione um equipamento</div>
                    </div>
                    <div class="mb-2 d-flex align-items-center gap-2">
                        <strong>Categoria:</strong>
                        <span id="preview-category" class="badge bg-light text-muted">-</span>
                    </div>
                    <div class="mb-2 d-flex align-items-center gap-2">
                        <strong>Estado:</strong>
                        <span id="preview-status" class="badge bg-secondary">-</span>
                    </div>
                    <div class="mb-2">
                        <strong>Localização:</strong>
                        <span id="preview-location" class="text-muted">-</span>
                    </div>
                    <div class="mb-2">
                        <strong>Datas:</strong>
                        <div class="text-muted" id="preview-dates">-</div>
                    </div>
                    <div>
                        <strong>Finalidade:</strong>
                        <div class="text-muted" id="preview-purpose">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Nova Requisição
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf

                    <h6 class="text-muted mb-3">
                        <i class="bi bi-info-circle"></i> Detalhes do Equipamento
                    </h6>
                    <div class="mb-3">
                        <label for="equipment_id" class="form-label">Equipamento *</label>
                        <select class="form-select @error('equipment_id') is-invalid @enderror" id="equipment_id" name="equipment_id" required>
                            <option value="">Selecionar equipamento...</option>
                            @foreach($equipments as $equipment)
                                <option value="{{ $equipment->id }}"
                                    data-name="{{ $equipment->name }}"
                                    data-category="{{ $equipment->category->name }}"
                                    data-status="{{ $equipment->status }}"
                                    data-location="{{ $equipment->location }}"
                                    data-condition="{{ $equipment->condition }}"
                                    {{ old('equipment_id', $equipment_id) == $equipment->id ? 'selected' : '' }}>
                                    {{ $equipment->name }} ({{ $equipment->category->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">

                    <h6 class="text-muted mb-3">
                        <i class="bi bi-calendar"></i> Datas da Requisição
                    </h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Data de Início *</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Data de Fim *</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-muted mb-3">
                        <i class="bi bi-card-text"></i> Finalidade
                    </h6>
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Finalidade</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Ex: Projeto Final" value="{{ old('purpose') }}">
                        <small class="text-muted">Para que vai usar o equipamento?</small>
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Projeto (Opcional)</label>
                        <input type="text" class="form-control" id="project" name="project" value="{{ old('project') }}">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Requisitar Equipamento
                        </button>
                        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
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
        font-size: 4rem;
        color: #667eea;
    }

    #equipment_id:focus,
    #start_date:focus,
    #end_date:focus,
    #purpose:focus,
    #project:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.2);
    }
</style>

<script>
    const equipmentSelect = document.getElementById('equipment_id');
    const previewName = document.getElementById('preview-name');
    const previewCategory = document.getElementById('preview-category');
    const previewStatus = document.getElementById('preview-status');
    const previewLocation = document.getElementById('preview-location');
    const previewDates = document.getElementById('preview-dates');
    const previewPurpose = document.getElementById('preview-purpose');
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const purposeInput = document.getElementById('purpose');

    function setDateConstraints() {
        const today = new Date().toISOString().split('T')[0];
        startInput.min = today;
        if (startInput.value) {
            endInput.min = startInput.value;
        } else {
            endInput.min = today;
        }
    }

    function updatePreview() {
        const option = equipmentSelect.options[equipmentSelect.selectedIndex];
        if (option && option.value) {
            previewName.textContent = option.dataset.name;
            previewCategory.textContent = option.dataset.category;
            previewCategory.className = 'badge bg-light text-muted';

            // Status badge colors
            previewStatus.textContent = option.dataset.status || '-';
            previewStatus.className = 'badge';
            switch(option.dataset.status) {
                case 'available':
                    previewStatus.classList.add('bg-success');
                    previewStatus.textContent = 'Disponível';
                    break;
                case 'loaned':
                    previewStatus.classList.add('bg-warning');
                    previewStatus.textContent = 'Emprestado';
                    break;
                case 'maintenance':
                    previewStatus.classList.add('bg-danger');
                    previewStatus.textContent = 'Manutenção';
                    break;
                default:
                    previewStatus.classList.add('bg-secondary');
                    previewStatus.textContent = 'Indisponível';
            }

            previewLocation.textContent = option.dataset.location || '-';
        } else {
            previewName.textContent = 'Selecione um equipamento';
            previewCategory.textContent = '-';
            previewCategory.className = 'badge bg-light text-muted';
            previewStatus.textContent = '-';
            previewStatus.className = 'badge bg-secondary';
            previewLocation.textContent = '-';
        }

        // Dates
        const start = startInput.value ? new Date(startInput.value).toLocaleDateString() : '-';
        const end = endInput.value ? new Date(endInput.value).toLocaleDateString() : '-';
        previewDates.textContent = `${start} até ${end}`;

        // Purpose
        previewPurpose.textContent = purposeInput.value ? purposeInput.value : '-';
    }

    // Event listeners
    equipmentSelect.addEventListener('change', updatePreview);
    startInput.addEventListener('change', () => {
        endInput.min = startInput.value || new Date().toISOString().split('T')[0];
        if (endInput.value && endInput.value < endInput.min) {
            endInput.value = endInput.min;
        }
        updatePreview();
    });
    endInput.addEventListener('change', updatePreview);
    purposeInput.addEventListener('input', updatePreview);

    // Inicializar
    setDateConstraints();
    updatePreview();
</script>
@endsection
