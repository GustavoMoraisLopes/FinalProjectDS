@extends('layouts.app')

@section('title', 'Meu Perfil')
@section('page-title', 'Meu Perfil')
@section('page-subtitle', 'Gerenciar informações da conta e preferências')

@section('content')
<div class="row">
    <!-- Card de Perfil -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center py-5">
                <div class="mb-3 position-relative">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-profile-img rounded-circle">
                    @else
                        <div class="avatar-profile">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    @endif
                </div>
                <h4 class="card-title mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">
                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                </p>
                <div class="text-start">
                    <small class="d-block mb-2">
                        <i class="bi bi-envelope"></i> <strong>Email:</strong> {{ $user->email }}
                    </small>
                    @if($user->phone)
                    <small class="d-block mb-2">
                        <i class="bi bi-telephone"></i> <strong>Telefone:</strong> {{ $user->phone }}
                    </small>
                    @endif
                    @if($user->department)
                    <small class="d-block mb-2">
                        <i class="bi bi-building"></i> <strong>Departamento:</strong> {{ $user->department }}
                    </small>
                    @endif
                    <small class="d-block">
                        <i class="bi bi-calendar"></i> <strong>Membro desde:</strong> {{ $user->created_at->format('d/m/Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulários de Edição -->
    <div class="col-lg-8">
        <!-- Editar Perfil -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square"></i> Editar Perfil
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Upload de Avatar -->
                    <div class="mb-4">
                        <label class="form-label">Foto de Perfil</label>
                        <div class="text-center">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar Preview"
                                     class="avatar-preview rounded-circle mb-3" id="avatarPreview">
                            @else
                                <div class="avatar-preview-placeholder rounded-circle mb-3 mx-auto" id="avatarPreviewPlaceholder">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <img src="" alt="Avatar Preview" class="avatar-preview rounded-circle mb-3 d-none" id="avatarPreview">
                            @endif
                        </div>
                        <div class="d-flex gap-2 justify-content-center">
                            <label for="avatar" class="btn btn-sm btn-primary action-btn">
                                <i class="bi bi-cloud-upload"></i> Escolher Foto
                            </label>
                            <input type="file" class="d-none @error('avatar') is-invalid @enderror"
                                   id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(event)">
                            @if($user->avatar)
                                <button type="button" class="btn btn-sm btn-danger action-btn" onclick="removeAvatar()">
                                    <i class="bi bi-trash"></i> Remover
                                </button>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-2">JPG, PNG ou GIF (máx. 2MB)</small>
                        @error('avatar')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="mb-3">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+351 xxx xxx xxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="department" class="form-label">Departamento</label>
                        <input type="text" class="form-control @error('department') is-invalid @enderror"
                               id="department" name="department" value="{{ old('department', $user->department) }}" placeholder="ex: TI, RH, Financeiro">
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($user->isStudent())
                    <hr class="mb-3">
                    <h6 class="text-muted mb-3"><i class="bi bi-book"></i> Dados Académicos</h6>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Estes dados serão utilizados automaticamente nas suas requisições.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 mb-3">
                            <label for="school" class="form-label">Instituição *</label>
                            <select class="form-select @error('school') is-invalid @enderror" id="school" name="school" required>
                                <option value="">Selecione</option>
                                <option value="istec" {{ old('school', $user->school) == 'istec' ? 'selected' : '' }}>ISTEC Porto</option>
                                <option value="ipta" {{ old('school', $user->school) == 'ipta' ? 'selected' : '' }}>IPTA Porto</option>
                                <option value="outro" {{ old('school', $user->school) == 'outro' ? 'selected' : '' }}>Outro</option>
                            </select>
                            @error('school')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="course_type" class="form-label">Tipo de Curso *</label>
                            <select class="form-select @error('course_type') is-invalid @enderror" id="course_type" name="course_type" required disabled>
                                <option value="">Selecione o tipo</option>
                            </select>
                            @error('course_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="course_name" class="form-label">Curso *</label>
                            <select class="form-select @error('course_name') is-invalid @enderror" id="course_name" name="course_name" required disabled>
                                <option value="">Selecione o curso</option>
                            </select>
                            @error('course_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="class_year" class="form-label">Turma / Ano *</label>
                            <select class="form-select @error('class_year') is-invalid @enderror" id="class_year" name="class_year" required disabled>
                                <option value="">Selecione a turma</option>
                            </select>
                            @error('class_year')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary action-btn">
                        <i class="bi bi-check-circle"></i> Guardar Alterações
                    </button>
                </form>
            </div>
        </div>

        <!-- Alterar Palavra-passe -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-lock"></i> Alterar Palavra-passe
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Palavra-passe Atual</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Palavra-passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Palavra-passe</label>
                        <input type="password" class="form-control" id="password_confirmation"
                               name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-warning action-btn">
                        <i class="bi bi-arrow-repeat"></i> Alterar Palavra-passe
                    </button>
                </form>
            </div>
        </div>

        @if($user->isStudent())
        <!-- Solicitar Acesso como Professor -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge"></i> Acesso como Professor
                </h5>
            </div>
            <div class="card-body">
                @if($user->hasPendingTeacherRequest())
                    <div class="alert alert-warning">
                        <i class="bi bi-clock-history"></i> <strong>Pedido Pendente</strong><br>
                        O seu pedido para acesso como professor está a aguardar aprovação do administrador.
                    </div>
                @else
                    <p class="text-muted mb-3">
                        <i class="bi bi-info-circle"></i> Se é docente ou membro do staff, pode solicitar acesso como professor. 
                        Esta funcionalidade permite inserir manualmente os dados académicos ao criar requisições.
                    </p>
                    <form action="{{ route('profile.request-teacher') }}" method="POST" onsubmit="return confirm('Tem a certeza que pretende solicitar acesso como professor? Este pedido será analisado por um administrador.');">
                        @csrf
                        <button type="submit" class="btn btn-info action-btn">
                            <i class="bi bi-send"></i> Solicitar Acesso como Professor
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .avatar-profile {
        font-size: 6rem;
        color: #667eea;
    }

    .avatar-profile-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 4px solid #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid #667eea;
    }

    .avatar-preview-placeholder {
        width: 150px;
        height: 150px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #cbd5e1;
    }

    .avatar-preview-placeholder i {
        font-size: 4rem;
        color: #94a3b8;
    }

    .action-btn {
        transition: all 0.15s ease;
        font-weight: 500;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .action-btn:active {
        transform: translateY(0);
    }
</style>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPreviewPlaceholder');

                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }

                // Atualiza também o avatar do cartão da esquerda
                const leftImg = document.querySelector('.avatar-profile-img');
                const leftPlaceholder = document.querySelector('.avatar-profile');
                if (leftImg) {
                    leftImg.src = e.target.result;
                } else if (leftPlaceholder) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Avatar';
                    img.className = 'avatar-profile-img rounded-circle';
                    leftPlaceholder.replaceWith(img);
                }
            }
            reader.readAsDataURL(file);
        }
    }

    function removeAvatar() {
        if (confirm('Tem a certeza que deseja remover a foto de perfil?')) {
            // Criar um input hidden para indicar remoção
            const form = document.querySelector('form[action="{{ route('profile.update') }}"]');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_avatar';
            input.value = '1';
            form.appendChild(input);
            form.submit();
        }
    }

    // Cascata de dados académicos (apenas se existir a secção)
    (function initAcademicCascade() {
        const schoolSelect = document.getElementById('school');
        const courseTypeSelect = document.getElementById('course_type');
        const courseNameSelect = document.getElementById('course_name');
        const classYearSelect = document.getElementById('class_year');

        if (!schoolSelect || !courseTypeSelect || !courseNameSelect || !classYearSelect) return;

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

        function updateCourseTypes() {
            const selectedSchool = schoolSelect.value;
            courseTypeSelect.innerHTML = '<option value="">Selecione o tipo de curso</option>';
            courseNameSelect.innerHTML = '<option value="">Selecione o curso</option>';
            classYearSelect.innerHTML = '<option value="">Selecione a turma</option>';
            courseNameSelect.disabled = true;
            classYearSelect.disabled = true;

            if (selectedSchool === 'outro') {
                courseTypeSelect.disabled = true;
            } else if (selectedSchool && courseData[selectedSchool]) {
                courseTypeSelect.disabled = false;
                Object.keys(courseData[selectedSchool]).forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;
                    option.textContent = type;
                    if (type === '{{ old("course_type", $user->course_type) }}') option.selected = true;
                    courseTypeSelect.appendChild(option);
                });

                const oldValue = '{{ old("course_type", $user->course_type) }}';
                if (oldValue && courseTypeSelect.value !== oldValue) {
                    courseTypeSelect.value = oldValue;
                }
                if (courseTypeSelect.value) {
                    updateCourseNames();
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
                    if (course === '{{ old("course_name", $user->course_name) }}') option.selected = true;
                    courseNameSelect.appendChild(option);
                });

                const oldValue = '{{ old("course_name", $user->course_name) }}';
                if (oldValue && courseNameSelect.value !== oldValue) {
                    courseNameSelect.value = oldValue;
                }
                if (courseNameSelect.value) {
                    updateClassYears();
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
                    if (turma === '{{ old("class_year", $user->class_year) }}') option.selected = true;
                    classYearSelect.appendChild(option);
                });
            } else {
                classYearSelect.disabled = true;
            }
        }

        schoolSelect.addEventListener('change', updateCourseTypes);
        courseTypeSelect.addEventListener('change', updateCourseNames);
        courseNameSelect.addEventListener('change', updateClassYears);

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            if (schoolSelect.value) {
                updateCourseTypes();
            }
        });
    })();
</script>
@endsection
