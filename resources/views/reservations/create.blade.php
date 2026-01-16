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
                        <i class="bi bi-calendar-range"></i> Datas da Requisição
                    </h6>
                    <div class="mb-3">
                        <label for="reservation_range" class="form-label">Intervalo *</label>
                        <input type="text" class="form-control @error('start_date') is-invalid @enderror @error('end_date') is-invalid @enderror" id="reservation_range" name="reservation_range" value="{{ old('start_date') && old('end_date') ? old('start_date') . ' até ' . old('end_date') : '' }}" placeholder="Selecione o intervalo" required>
                        <input type="hidden" id="start_date" name="start_date" value="{{ old('start_date') }}">
                        <input type="hidden" id="end_date" name="end_date" value="{{ old('end_date') }}">
                        @error('start_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        @error('end_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pickup_time" class="form-label">Hora de Levantamento *</label>
                            <input type="time" class="form-control @error('pickup_time') is-invalid @enderror" id="pickup_time" name="pickup_time" value="{{ old('pickup_time', '09:00') }}" required>
                            @error('pickup_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="return_time" class="form-label">Hora de Devolução *</label>
                            <input type="time" class="form-control @error('return_time') is-invalid @enderror" id="return_time" name="return_time" value="{{ old('return_time', '17:00') }}" required>
                            @error('return_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-muted mb-3">
                        <i class="bi bi-card-text"></i> Finalidade / Curso
                    </h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="school" class="form-label">Instituição *</label>
                            <select class="form-select @error('school') is-invalid @enderror" id="school" name="school" required>
                                <option value="">Selecione a instituição</option>
                                <option value="istec" {{ old('school') == 'istec' ? 'selected' : '' }}>ISTEC Porto</option>
                                <option value="ipta" {{ old('school') == 'ipta' ? 'selected' : '' }}>IPTA Porto</option>
                                <option value="outro" {{ old('school') == 'outro' ? 'selected' : '' }}>Outro</option>
                            </select>
                            @error('school')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="course_type" class="form-label">Tipo de Curso *</label>
                            <select class="form-select @error('course_type') is-invalid @enderror" id="course_type" name="course_type" required disabled>
                                <option value="">Selecione o tipo</option>
                            </select>
                            @error('course_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="course_name" class="form-label">Curso *</label>
                            <select class="form-select @error('course_name') is-invalid @enderror" id="course_name" name="course_name" required disabled>
                                <option value="">Selecione o curso</option>
                            </select>
                            @error('course_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="class_year" class="form-label">Turma / Ano *</label>
                            <select class="form-select @error('class_year') is-invalid @enderror" id="class_year" name="class_year" required disabled>
                                <option value="">Selecione a turma</option>
                            </select>
                            @error('class_year')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="purpose" class="form-label">Finalidade / Descrição (Opcional)</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Ex: Projeto Final, Trabalho Prático, etc." value="{{ old('purpose') }}">
                        <small class="text-muted">Detalhes adicionais sobre a finalidade do equipamento</small>
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Projeto (Opcional)</label>
                        <input type="text" class="form-control" id="project" name="project" value="{{ old('project') }}">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Observações</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Ex: Para projeto da disciplina de Programação Web. Necessito também de um adaptador HDMI.">{{ old('notes') }}</textarea>
                        <small class="text-muted">Especifique detalhes adicionais, acessórios necessários, disciplina, etc.</small>
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
    const rangeInput = document.getElementById('reservation_range');
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const purposeInput = document.getElementById('purpose');

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

        const start = startInput.value ? startInput.value : '-';
        const end = endInput.value ? endInput.value : '-';
        previewDates.textContent = `${start} até ${end}`;

        previewPurpose.textContent = purposeInput.value ? purposeInput.value : '-';
    }

    equipmentSelect.addEventListener('change', updatePreview);
    rangeInput.addEventListener('change', updatePreview);
    purposeInput.addEventListener('input', updatePreview);

    document.addEventListener('DOMContentLoaded', function() {
        if (window.flatpickr && rangeInput) {
            const fp = flatpickr(rangeInput, {
                mode: 'range',
                allowInput: true,
                dateFormat: 'd/m/Y',
                locale: flatpickr.l10ns.pt,
                minDate: 'today',
                rangeSeparator: ' até ',
                onChange: function(selectedDates, dateStr) {
                    startInput.value = selectedDates[0] ? flatpickr.formatDate(selectedDates[0], 'd/m/Y') : '';
                    endInput.value = selectedDates[1] ? flatpickr.formatDate(selectedDates[1], 'd/m/Y') : '';
                    rangeInput.value = dateStr;
                    updatePreview();
                }
            });

            if (startInput.value && endInput.value) {
                fp.setDate([startInput.value, endInput.value], false, 'd/m/Y');
            }
        }
        updatePreview();

        // Initialize cascading course selects
        initializeCourseSelects();
    });

    // ====== Cascading Course Selection Logic ======
    const schoolSelect = document.getElementById('school');
    const courseTypeSelect = document.getElementById('course_type');
    const courseNameSelect = document.getElementById('course_name');
    const classYearSelect = document.getElementById('class_year');

    // Course data structure by institution with specific turmas
    const courseData = {
        istec: {
            'CTeSP': {
                'CiberSegurança': ['1ºCS', '2ºCS'],
                'Informática de Gestão': ['1ºIG', '2ºIG'],
                'Desenvolvimento de Produtos Multimédia': ['1ºDPM', '2ºDPM'],
                'Redes e Sistemas Informáticos': ['1ºRSI', '2ºRSI'],
                'Desenvolvimento para Dispositivos Móveis': ['1ºDDM', '2ºDDM'],
                'Desenvolvimento de Software': ['1ºDS', '2ºDS'],
                'Design e Multimédia': ['1ºDM', '2ºDM'],
                'Robótica e Inteligência Artificial': ['1ºRIA', '2ºRIA']
            },
            'Licenciatura': {
                'Engenharia Informática': ['1º EI', '2º EI', '3º EI'],
                'Engenharia Multimédia': ['1º EM', '2º EM', '3º EM'],
                'Ciência e Visualização de Dados': ['1º CVD', '2º CVD', '3º CVD'],
                'Engenharia de Redes e Segurança Informática': ['1º ERSI', '2º ERSI', '3º ERSI']
            }
        },
        ipta: {
            'Profissional': {
                'Técnico de Som': ['1º TS', '2º TS'],
                'Técnico de Multimédia': ['1º TM', '2º TM'],
                'Técnico de Gestão de Equipamentos Informáticos': ['1º TGEI', '2º TGEI'],
                'Técnico de Informática - Instalação e Gestão de Redes': ['1º TIGR', '2º TIGR']
            },
            'CET (Especialização Tecnológica)': {
                'CET - Desenvolvimento de Produtos Multimédia': ['1º CET-DPM'],
                'CET - Cibersegurança': ['1º CET-CS']
            }
        }
    };

    function initializeCourseSelects() {
        // Set up event listeners
        schoolSelect.addEventListener('change', updateCourseTypes);
        courseTypeSelect.addEventListener('change', updateCourseNames);
        courseNameSelect.addEventListener('change', updateClassYears);

        // Initialize if school is already selected (e.g., on form validation error)
        if (schoolSelect.value) {
            updateCourseTypes();
            if (courseTypeSelect.value) {
                updateCourseNames();
                if (courseNameSelect.value) {
                    updateClassYears();
                }
            }
        }
    }

    function updateCourseTypes() {
        const selectedSchool = schoolSelect.value;
        courseTypeSelect.innerHTML = '<option value="">Selecione o tipo de curso</option>';
        courseNameSelect.innerHTML = '<option value="">Selecione o curso</option>';
        classYearSelect.innerHTML = '<option value="">Selecione a turma</option>';
        courseNameSelect.disabled = true;
        classYearSelect.disabled = true;

        if (selectedSchool === 'outro') {
            courseTypeSelect.disabled = true;
            courseTypeSelect.value = '';
        } else if (selectedSchool && courseData[selectedSchool]) {
            courseTypeSelect.disabled = false;
            Object.keys(courseData[selectedSchool]).forEach(type => {
                const option = document.createElement('option');
                option.value = type;
                option.textContent = type;
                courseTypeSelect.appendChild(option);
            });

            // Restore previous selection if available
            const oldValue = '{{ old("course_type") }}';
            if (oldValue) {
                courseTypeSelect.value = oldValue;
                if (courseTypeSelect.value === oldValue) {
                    updateCourseNames();
                }
            }
        } else {
            courseTypeSelect.disabled = true;
        }
    }

    function updateCourseNames() {
        const selectedSchool = schoolSelect.value;
        const selectedType = courseTypeSelect.value;
        courseNameSelect.innerHTML = '<option value="">Selecione o curso</option>';
        classYearSelect.innerHTML = '<option value="">Selecione a turma</option>';
        classYearSelect.disabled = true;

        if (selectedSchool && selectedType && courseData[selectedSchool] && courseData[selectedSchool][selectedType]) {
            courseNameSelect.disabled = false;
            Object.keys(courseData[selectedSchool][selectedType]).forEach(course => {
                const option = document.createElement('option');
                option.value = course;
                option.textContent = course;
                courseNameSelect.appendChild(option);
            });

            // Restore previous selection if available
            const oldValue = '{{ old("course_name") }}';
            if (oldValue) {
                courseNameSelect.value = oldValue;
                if (courseNameSelect.value === oldValue) {
                    updateClassYears();
                }
            }
        } else {
            courseNameSelect.disabled = true;
        }
    }

    function updateClassYears() {
        const selectedSchool = schoolSelect.value;
        const selectedType = courseTypeSelect.value;
        const selectedCourse = courseNameSelect.value;
        classYearSelect.innerHTML = '<option value="">Selecione a turma</option>';

        if (selectedSchool && selectedType && selectedCourse &&
            courseData[selectedSchool] && courseData[selectedSchool][selectedType] &&
            courseData[selectedSchool][selectedType][selectedCourse]) {
            classYearSelect.disabled = false;
            courseData[selectedSchool][selectedType][selectedCourse].forEach(turma => {
                const option = document.createElement('option');
                option.value = turma;
                option.textContent = turma;
                classYearSelect.appendChild(option);
            });

            // Restore previous selection if available
            const oldValue = '{{ old("class_year") }}';
            if (oldValue) {
                classYearSelect.value = oldValue;
            }
        } else {
            classYearSelect.disabled = true;
        }
    }
</script>
@endsection
