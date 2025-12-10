@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="bi bi-box-seam text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="fw-bold">LabStock</h3>
                    <p class="text-muted">Sistema de Gestão de Inventário</p>
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

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Lembrar-me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>

                <div class="text-center mt-3">
                    <p class="text-muted">
                        Não tem conta? <a href="{{ route('register') }}" class="text-decoration-none">Registar</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
