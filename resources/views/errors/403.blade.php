@extends('layouts.app')

@section('title', 'Acesso Negado')
@section('page-title', '403 - Acesso Negado')
@section('page-subtitle', 'Não tem permissão para aceder a este recurso.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg text-center py-5">
            <div class="card-body">
                <i class="bi bi-shield-lock text-danger" style="font-size: 4rem;"></i>
                <h2 class="mt-4 mb-3">Acesso Negado</h2>
                <p class="text-muted mb-4">
                    Desculpe, não tem permissão para aceder a este recurso.
                    Se acredita que isto é um erro, contacte o administrador.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-house"></i> Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
