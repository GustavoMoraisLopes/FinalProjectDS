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
                @if($unreadCount > 0)
                    <form action="{{ route('reminders.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-check2-all"></i> Marcar todas como lidas
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body p-0">
                @if($notifications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light' }}">
                                <div class="d-flex w-100 align-items-start">
                                    <div class="notification-icon me-3">
                                        <i class="bi bi-{{ $notification->data['icon'] ?? 'bell' }} text-{{ $notification->data['color'] ?? 'primary' }} fs-3"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex w-100 justify-content-between align-items-start mb-1">
                                            <h6 class="mb-1">
                                                {{ $notification->data['title'] ?? 'Notificação' }}
                                                @if(!$notification->read_at)
                                                    <span class="badge bg-danger rounded-pill ms-2">Novo</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-2 text-muted">{{ $notification->data['message'] }}</p>
                                        <div class="d-flex gap-2">
                                            @if(isset($notification->data['action_url']))
                                                <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-primary">
                                                    {{ $notification->data['action_text'] ?? 'Ver detalhes' }}
                                                </a>
                                            @endif
                                            @if(!$notification->read_at)
                                                <form action="{{ route('reminders.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-check"></i> Marcar como lida
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white">
                        {{ $notifications->links() }}
                    </div>
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

<style>
    .notification-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(var(--bs-primary-rgb), 0.1);
        border-radius: 50%;
    }

    .list-group-item-action:hover {
        background-color: #f8f9fa !important;
    }

    .list-group-item.bg-light {
        border-left: 3px solid #3498db;
    }
</style>
@endsection
