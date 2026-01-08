@extends('layouts.app')

@section('title', 'Lembretes')
@section('page-title', 'Lembretes')
@section('page-subtitle', 'Notificações e avisos importantes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-bell"></i> Todas as Notificações
                    @if($unreadCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                    @endif
                </h5>
                <div class="d-flex gap-2 align-items-center">
                    @if($unreadCount > 0)
                        <form action="{{ route('reminders.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success action-btn">
                                <i class="bi bi-check2-all"></i> Marcar lidas
                            </button>
                        </form>
                    @endif
                    @if($notifications->count() > 0)
                        <button type="button" class="btn btn-sm btn-danger action-btn" id="toggleDeleteMode" title="Selecionar notificações para eliminar" aria-label="Selecionar notificações para eliminar">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                        <button type="submit" form="selectForm" class="btn btn-sm btn-warning action-btn text-dark" id="deleteSelectedBtn" style="display: none;">
                            <i class="bi bi-trash"></i> Selecionadas
                        </button>
                        <button type="button" class="btn btn-sm btn-warning action-btn text-dark" id="deleteAllBtn" style="display: none;" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
                            <i class="bi bi-trash-fill"></i> Tudo
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary action-btn" id="cancelDeleteMode" style="display: none;">
                            <i class="bi bi-x"></i> Cancelar
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                @if($notifications->count() > 0)
                    <form id="selectForm" method="POST" action="{{ route('reminders.delete-selected') }}">
                        @csrf
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                @php
                                    $reservationId = $notification->data['reservation_id'] ?? null;
                                    $reservationStatus = $reservationId ? optional(\App\Models\Reservation::find($reservationId))->status : null;
                                    $isLocked = $notification->type === \App\Notifications\ReturnReminderNotification::class && $reservationStatus && $reservationStatus !== 'completed';
                                @endphp
                                <div class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light' }} d-flex align-items-start gap-3 py-3 notification-item">
                                    <div class="form-check mt-2 checkbox-container" style="display: none;">
                                        <input class="form-check-input notification-checkbox" type="checkbox" name="notification_ids[]" value="{{ $notification->id }}" id="notif-{{ $notification->id }}" @if($isLocked) disabled title="Este lembrete só pode ser removido após a devolução." @endif>
                                    </div>
                                    <div class="notification-icon">
                                        <i class="bi bi-{{ $notification->data['icon'] ?? 'bell' }} text-{{ $notification->data['color'] ?? 'primary' }} fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex w-100 justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">
                                                {{ $notification->data['title'] ?? 'Notificação' }}
                                                @if(!$notification->read_at)
                                                    <span class="badge bg-danger rounded-pill ms-2">Novo</span>
                                                @endif
                                                @if($isLocked)
                                                    <span class="badge bg-warning text-dark ms-2">Aguardar devolução</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted flex-shrink-0 ms-2">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-2 text-muted small">{{ $notification->data['message'] }}</p>
                                        <div class="d-flex gap-2 action-buttons">
                                            @if(isset($notification->data['action_url']))
                                                <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-primary action-btn">
                                                    {{ $notification->data['action_text'] ?? 'Ver detalhes' }}
                                                </a>
                                            @endif
                                            @if(!$notification->read_at)
                                                <form action="{{ route('reminders.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success action-btn">
                                                        <i class="bi bi-check"></i> Marcar como lida
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="card-footer bg-white">
                            {{ $notifications->links() }}
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Não há notificações para mostrar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar limpeza total -->
<div class="modal fade" id="deleteAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar todas as notificações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem a certeza que pretende eliminar <strong>todas</strong> as notificações? Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('reminders.delete-all') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Sim, Eliminar Tudo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(var(--bs-primary-rgb), 0.1);
        border-radius: 50%;
    }

    .action-btn {
        transition: all 0.15s ease;
        font-weight: 500;
        border: none;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    }

    .action-btn:active {
        transform: translateY(0);
    }

    .action-btn.btn-success {
        background-color: #198754;
    }

    .action-btn.btn-success:hover {
        background-color: #157347;
    }

    .action-btn.btn-danger {
        background-color: #dc3545;
    }

    .action-btn.btn-danger:hover {
        background-color: #bb2d3b;
    }

    .action-btn.btn-warning {
        background-color: #ffc107;
    }

    .action-btn.btn-warning:hover {
        background-color: #ffb300;
    }

    .action-btn.btn-secondary {
        background-color: #6c757d;
    }

    .action-btn.btn-secondary:hover {
        background-color: #5c636a;
    }

    .action-btn.btn-primary {
        background-color: #0d6efd;
    }

    .action-btn.btn-primary:hover {
        background-color: #0b5ed7;
    }

    .list-group-item-action:hover {
        background-color: #f8f9fa !important;
    }

    .list-group-item.bg-light {
        border-left: 3px solid #3498db;
    }

    .delete-mode .checkbox-container {
        display: block !important;
    }

    .delete-mode .delete-single {
        display: none !important;
    }
</style>

<script>
    const toggleDeleteBtn = document.getElementById('toggleDeleteMode');
    const cancelDeleteBtn = document.getElementById('cancelDeleteMode');
    const deleteAllBtn = document.getElementById('deleteAllBtn');
    const selectForm = document.getElementById('selectForm');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const notificationItems = document.querySelectorAll('.notification-item');

    if (toggleDeleteBtn && cancelDeleteBtn && selectForm) {
        // Entrar no modo eliminar
        toggleDeleteBtn.addEventListener('click', function() {
            selectForm.classList.add('delete-mode');
            toggleDeleteBtn.style.display = 'none';
            deleteAllBtn.style.display = 'inline-block';
            cancelDeleteBtn.style.display = 'inline-block';
            notificationItems.forEach(item => item.style.cursor = 'pointer');
        });

        // Sair do modo eliminar
        cancelDeleteBtn.addEventListener('click', function() {
            selectForm.classList.remove('delete-mode');
            toggleDeleteBtn.style.display = 'inline-block';
            deleteAllBtn.style.display = 'none';
            cancelDeleteBtn.style.display = 'none';
            deleteSelectedBtn.style.display = 'none';
            document.querySelectorAll('.notification-checkbox').forEach(cb => cb.checked = false);
            notificationItems.forEach(item => item.style.cursor = 'default');
        });

        // Atualizar button ao mudar checkboxes
        document.querySelectorAll('.notification-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateDeleteButton);
        });
    }

    function updateDeleteButton() {
        const checkedCount = document.querySelectorAll('.notification-checkbox:enabled:checked').length;
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        if (deleteBtn) {
            if (checkedCount > 0) {
                deleteBtn.style.display = 'inline-block';
                deleteBtn.innerHTML = `<i class="bi bi-trash"></i> Eliminar ${checkedCount} selecionada${checkedCount !== 1 ? 's' : ''}`;
            } else {
                deleteBtn.style.display = 'none';
            }
        }
    }
</script>
@endsection
