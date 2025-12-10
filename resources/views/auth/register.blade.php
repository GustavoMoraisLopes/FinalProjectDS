@extends('layouts.guest')

@section('title', 'Registar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="bi bi-box-seam text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="fw-bold">Criar Conta</h3>
                    <p class="text-muted">LabStock - Sistema de Gestão de Inventário</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Telefone (Opcional)</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Departamento (Opcional)</label>
                            <input type="text" class="form-control" id="department" name="department" value="{{ old('department') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registar</button>
                </form>

                <div class="text-center mt-3">
                    <p class="text-muted">
                        Já tem conta? <a href="{{ route('login') }}" class="text-decoration-none">Entrar</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
