@extends('layouts.app')

@section('title', 'Nova Requisição')
@section('page-title', 'Nova Requisição')
@section('page-subtitle', 'Requisitar um equipamento.')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="equipment_id" class="form-label">Equipamento *</label>
                        <select class="form-select @error('equipment_id') is-invalid @enderror" id="equipment_id" name="equipment_id" required>
                            <option value="">Selecionar equipamento...</option>
                            @foreach($equipments as $equipment)
                                <option value="{{ $equipment->id }}" {{ old('equipment_id', $equipment_id) == $equipment->id ? 'selected' : '' }}>
                                    {{ $equipment->name }} ({{ $equipment->category->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Data de Início *</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Data de Fim *</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="purpose" class="form-label">Finalidade</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Ex: Projeto Final" value="{{ old('purpose') }}">
                        <small class="text-muted">Para que vai usar o equipamento?</small>
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Projeto (Opcional)</label>
                        <input type="text" class="form-control" id="project" name="project" value="{{ old('project') }}">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Requisitar Equipamento
                        </button>
                        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
