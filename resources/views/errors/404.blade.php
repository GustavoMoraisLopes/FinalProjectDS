@extends('layouts.app')

@section('title', 'Não Encontrado')
@section('page-title', '404 - Página Não Encontrada')
@section('page-subtitle', 'O recurso que procura não existe.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg text-center py-5">
            <div class="card-body">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                <h2 class="mt-4 mb-3">Página Não Encontrada</h2>
                <p class="text-muted mb-4">
                    Desculpe, o recurso que procura não existe ou foi removido.
                    Verifique o endereço e tente novamente.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-house"></i> Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
