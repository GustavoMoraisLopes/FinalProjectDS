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
                    <div class="mb-2">
                        <strong>Instituição:</strong>
                        <div class="text-muted" id="preview-school">-</div>
                    </div>
                    <div class="mb-2">
                        <strong>Tipo de Curso:</strong>
                        <div class="text-muted" id="preview-course-type">-</div>
                    </div>
                    <div class="mb-2">
                        <strong>Curso:</strong>
                        <div class="text-muted" id="preview-course-name">-</div>
                    </div>
                    <div class="mb-2">
                        <strong>Turma:</strong>
                        <div class="text-muted" id="preview-class-year">-</div>
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
                            <label for="pickup_time_slider" class="form-label">Hora de Levantamento *</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" class="form-range" id="pickup_time_slider" min="8" max="22" value="9" step="0.5">
                                <div class="time-display-pickup" style="min-width: 60px; text-align: center; font-weight: bold; font-size: 1.1rem;">
                                    09:00
                                </div>
                            </div>
                            <input type="hidden" id="pickup_time" name="pickup_time" value="{{ old('pickup_time', '09:00') }}">
                            @error('pickup_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-2">Arraste para selecionar a hora</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="return_time_slider" class="form-label">Hora de Devolução *</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" class="form-range" id="return_time_slider" min="8" max="23" value="17" step="0.5">
                                <div class="time-display-return" style="min-width: 60px; text-align: center; font-weight: bold; font-size: 1.1rem;">
                                    17:00
                                </div>
                            </div>
                            <input type="hidden" id="return_time" name="return_time" value="{{ old('return_time', '17:00') }}">
                            @error('return_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-2">Arraste para selecionar a hora</small>
                        </div>
                    </div>

                    <style>
                        .form-range {
                            height: 8px;
                            border-radius: 5px;
                        }
                        .form-range::-webkit-slider-thumb {
                            width: 22px;
                            height: 22px;
                            background: linear-gradient(135deg, #667eea 0%, #667eea 100%);
                            border: 2px solid #fff;
                            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
                            cursor: pointer;
                        }
                        .form-range::-moz-range-thumb {
                            width: 22px;
                            height: 22px;
                            background: linear-gradient(135deg, #667eea 0%, #667eea 100%);
                            border: 2px solid #fff;
                            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
                            cursor: pointer;
                        }
                        .form-range::-webkit-slider-runnable-track {
                            background: linear-gradient(to right, #e9ecef 0%, #dee2e6 100%);
                            border-radius: 5px;
                            height: 8px;
                        }
                        .form-range::-moz-range-track {
                            background: linear-gradient(to right, #e9ecef 0%, #dee2e6 100%);
                            border-radius: 5px;
                        }
                    </style>

                    <hr class="my-4">

                    <h6 class="text-muted mb-3">
                        <i class="bi bi-card-text"></i> Finalidade / Curso
                    </h6>
                    @if(auth()->user()->isStudent())
                        <!-- Aluno: Auto-preencher dados académicos -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="school" class="form-label">Instituição</label>
                                <input type="text" class="form-control" id="school" value="{{ auth()->user()->school ? (auth()->user()->school === 'istec' ? 'ISTEC Porto' : (auth()->user()->school === 'ipta' ? 'IPTA Porto' : 'Outro')) : '-' }}" disabled>
                                <input type="hidden" name="school" value="{{ auth()->user()->school }}">
                            </div>
                            <div class="col-md-3">
                                <label for="course_type" class="form-label">Tipo de Curso</label>
                                <input type="text" class="form-control" id="course_type" value="{{ auth()->user()->course_type ?? '-' }}" disabled>
                                <input type="hidden" name="course_type" value="{{ auth()->user()->course_type }}">
                            </div>
                            <div class="col-md-3">
                                <label for="course_name" class="form-label">Curso</label>
                                <input type="text" class="form-control" id="course_name" value="{{ auth()->user()->course_name ?? '-' }}" disabled>
                                <input type="hidden" name="course_name" value="{{ auth()->user()->course_name }}">
                            </div>
                            <div class="col-md-3">
                                <label for="class_year" class="form-label">Turma / Ano</label>
                                <input type="text" class="form-control" id="class_year" value="{{ auth()->user()->class_year ?? '-' }}" disabled>
                                <input type="hidden" name="class_year" value="{{ auth()->user()->class_year }}">
                            </div>
                        </div>
                        <small class="text-muted">Dados académicos do seu perfil. Para atualizar, <a href="{{ route('profile.show') }}">edite seu perfil</a>.</small>
                    @else
                        <!-- Professor: Seleção manual -->
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
                    @endif

                    <div class="mb-3">
                        <label for="purpose" class="form-label">Finalidade / Descrição (Opcional)</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Ex: Projeto Final, Trabalho Prático, etc." value="{{ old('purpose') }}">
                        <small class="text-muted">Detalhes adicionais sobre a finalidade do equipamento</small>
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Projeto (Opcional)</label>
                        <input type="text" class="form-control" id="project" name="project" value="{{ old('project') }}">
                    </div>

                    <hr class="my-4">

                    <h6 class="text-muted mb-3">
                        <i class="bi bi-cart-check"></i> Acessórios Recomendados (Carrinho)
                    </h6>
                    <div id="accessories-container">
                        <div class="alert alert-info" id="accessories-empty-message" style="display: block;">
                            <i class="bi bi-info-circle"></i> Selecione um equipamento para ver os acessórios recomendados
                        </div>
                        <div id="accessories-list" style="display: none;"></div>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-muted mb-3">
                        <i class="bi bi-card-text"></i> Observações
                    </h6>

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

    .accessories-cart {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .accessory-item {
        transition: all 0.2s ease;
    }

    .accessory-item:hover {
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        border-left-color: #5bd1e1 !important;
    }

    .accessory-item .form-check-input {
        cursor: pointer;
        border: 2px solid #ddd;
        transition: all 0.2s ease;
    }

    .accessory-item .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .accessory-item .form-check-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .accessory-item input[type="number"] {
        border: 1px solid #ddd;
        text-align: center;
    }

    .accessory-item input[type="number"]:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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

        // Update course preview
        updateCoursePreview();
    }

    function updateCoursePreview() {
        const schoolSelect = document.getElementById('school');
        const courseTypeSelect = document.getElementById('course_type');
        const courseNameSelect = document.getElementById('course_name');
        const classYearSelect = document.getElementById('class_year');

        // Map school values to display names
        const schoolMap = {
            'istec': 'ISTEC Porto',
            'ipta': 'IPTA Porto',
            'outro': 'Outro'
        };

        document.getElementById('preview-school').textContent = schoolSelect.value ? schoolMap[schoolSelect.value] : '-';
        document.getElementById('preview-course-type').textContent = courseTypeSelect.value || '-';
        document.getElementById('preview-course-name').textContent = courseNameSelect.value || '-';
        document.getElementById('preview-class-year').textContent = classYearSelect.value || '-';
    }

    equipmentSelect.addEventListener('change', updatePreview);
    rangeInput.addEventListener('change', updatePreview);
    purposeInput.addEventListener('input', updatePreview);

    // Load accessories when equipment is selected
    equipmentSelect.addEventListener('change', loadAccessories);

    function loadAccessories() {
        const equipmentId = equipmentSelect.value;
        const emptyMessage = document.getElementById('accessories-empty-message');
        const accessoriesList = document.getElementById('accessories-list');

        if (!equipmentId) {
            emptyMessage.style.display = 'block';
            accessoriesList.style.display = 'none';
            return;
        }

        // Fetch recommended accessories by equipment type
        fetch(`/api/type-accessories/equipment/${equipmentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.accessories.length > 0) {
                    emptyMessage.style.display = 'none';
                    accessoriesList.style.display = 'block';
                    renderAccessories(data.accessories);
                } else {
                    emptyMessage.innerHTML = '<i class="bi bi-info-circle"></i> Sem acessórios recomendados para este equipamento';
                    emptyMessage.style.display = 'block';
                    accessoriesList.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Erro ao carregar acessórios:', error);
                emptyMessage.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Erro ao carregar acessórios';
                emptyMessage.style.display = 'block';
                emptyMessage.classList.add('alert-danger');
                accessoriesList.style.display = 'none';
            });
    }

    function renderAccessories(accessories) {
        const accessoriesList = document.getElementById('accessories-list');
        accessoriesList.innerHTML = '';

        let cartHtml = '<div class="accessories-cart">';

        accessories.forEach((accessory, index) => {
            const checked = true; // Por defeito, vêm marcados
            const statusBadge = accessory.status === 'available' ? 'bg-success' : (accessory.status === 'loaned' ? 'bg-warning' : 'bg-danger');
            const statusText = accessory.status === 'available' ? 'Disponível' : (accessory.status === 'loaned' ? 'Emprestado' : 'Manutenção');

            cartHtml += `
                <div class="accessory-item p-3 border rounded mb-2" style="background-color: #f8f9fa; border-left: 4px solid #667eea;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="accessory_${index}"
                                       name="accessories[${index}][equipment_id]" value="${accessory.id}" ${checked ? 'checked' : ''}>
                                <input type="hidden" name="accessories[${index}][quantity]" value="1">
                                <label class="form-check-label" for="accessory_${index}">
                                    <strong>${escapeHtml(accessory.name)}</strong>
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-bookmark"></i> ${escapeHtml(accessory.category)}
                                <span class="mx-1">•</span>
                                <i class="bi bi-hash"></i> ${escapeHtml(accessory.serial_number)}
                            </small>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge ${statusBadge}" style="font-size: 0.8rem;">${statusText}</span>
                        </div>
                    </div>
                    ${accessory.notes ? `<small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> ${escapeHtml(accessory.notes)}</small>` : ''}
                </div>
            `;
        });

        cartHtml += '</div>';
        accessoriesList.innerHTML = cartHtml;
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

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
        schoolSelect.addEventListener('change', function() {
            updateCourseTypes();
            updateCoursePreview();
        });
        courseTypeSelect.addEventListener('change', function() {
            updateCourseNames();
            updateCoursePreview();
        });
        courseNameSelect.addEventListener('change', function() {
            updateClassYears();
            updateCoursePreview();
        });
        classYearSelect.addEventListener('change', updateCoursePreview);

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

    // Slider de horas
    const pickupTimeSlider = document.getElementById('pickup_time_slider');
    const returnTimeSlider = document.getElementById('return_time_slider');
    const pickupTimeInput = document.getElementById('pickup_time');
    const returnTimeInput = document.getElementById('return_time');
    const pickupTimeDisplay = document.querySelector('.time-display-pickup');
    const returnTimeDisplay = document.querySelector('.time-display-return');
    const startDateInput = document.getElementById('start_date');

    // Função para converter slider value em HH:MM
    function sliderToTime(sliderValue) {
        const hours = Math.floor(sliderValue);
        const minutes = (sliderValue % 1) * 60;
        return `${String(hours).padStart(2, '0')}:${String(Math.round(minutes)).padStart(2, '0')}`;
    }

    // Update pickup time
    pickupTimeSlider.addEventListener('input', function() {
        const timeStr = sliderToTime(this.value);
        pickupTimeInput.value = timeStr;
        pickupTimeDisplay.textContent = timeStr;
    });

    // Update return time
    returnTimeSlider.addEventListener('input', function() {
        const timeStr = sliderToTime(this.value);
        returnTimeInput.value = timeStr;
        returnTimeDisplay.textContent = timeStr;
    });

    // Desativar pickup_time se for hoje e antes da hora atual
    function updatePickupTimeSlider() {
        const startDate = startDateInput.value;
        const today = new Date().toISOString().split('T')[0];
        const currentTime = new Date();
        const currentHours = currentTime.getHours();
        const currentMinutes = currentTime.getMinutes();
        const currentDecimal = currentHours + (currentMinutes / 60);

        if (startDate === today) {
            // Se for hoje, o mínimo é a hora atual
            pickupTimeSlider.min = Math.ceil(currentDecimal * 2) / 2; // Arredondar para 0.5

            // Se o valor atual for menor que o mínimo, ajusta
            if (parseFloat(pickupTimeSlider.value) < parseFloat(pickupTimeSlider.min)) {
                pickupTimeSlider.value = pickupTimeSlider.min;
                const timeStr = sliderToTime(pickupTimeSlider.value);
                pickupTimeInput.value = timeStr;
                pickupTimeDisplay.textContent = timeStr;
            }
        } else if (startDate && startDate > today) {
            pickupTimeSlider.min = 8;
        }
    }

    startDateInput.addEventListener('change', updatePickupTimeSlider);
    document.addEventListener('DOMContentLoaded', updatePickupTimeSlider);
</script>
@endsection
