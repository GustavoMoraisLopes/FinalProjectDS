@extends('layouts.app')

@section('title', 'Erro no Servidor')
@section('page-title', '500 - Erro no Servidor')
@section('page-subtitle', 'Algo correu mal.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg text-center py-5">
            <div class="card-body">
                <i class="bi bi-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                <h2 class="mt-4 mb-3">Erro no Servidor</h2>
                <p class="text-muted mb-4">
                    Desculpe, algo correu mal no servidor.
                    Por favor, tente novamente mais tarde ou contacte o suporte.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-house"></i> Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
