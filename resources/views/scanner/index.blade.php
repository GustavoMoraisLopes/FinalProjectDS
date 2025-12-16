@extends('layouts.app')

@section('title', 'Scanner')
@section('page-title', 'Scanner')
@section('page-subtitle', 'Leitura de códigos para check-in/out.')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="scanner-area mb-4">
                    <div class="camera-placeholder">
                        <i class="bi bi-camera" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="mt-3 text-muted">Câmara inativa</p>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="search" class="form-label">Introduzir ID ou Serial...</label>
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            id="search"
                            placeholder="Escanear código QR ou introduzir serial..."
                            autocomplete="off"
                        >
                        <button class="btn btn-dark" type="button" id="searchBtn">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm" id="resultCard" style="display: none;">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detalhes do Equipamento</h5>
            </div>
            <div class="card-body" id="resultContent"></div>
        </div>

        <div id="noResult" class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-2">Nenhum equipamento pesquisado</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('search');
    const searchBtn = document.getElementById('searchBtn');
    const resultCard = document.getElementById('resultCard');
    const resultContent = document.getElementById('resultContent');
    const noResult = document.getElementById('noResult');

    function performSearch() {
        const query = searchInput.value.trim();
        if (!query) return;

        fetch('{{ route("scanner.search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ query: query })
        })
        .then(response => {
            if (!response.ok) throw new Error('Equipamento não encontrado');
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert(data.error);
                showNoResult();
                return;
            }

            const statusBadge = {
                'available': '<span class="badge status-available">Disponível</span>',
                'loaned': '<span class="badge status-loaned">Emprestado</span>',
                'maintenance': '<span class="badge status-maintenance">Manutenção</span>',
                'unavailable': '<span class="badge bg-secondary">Indisponível</span>',
            };

            resultContent.innerHTML = `
                <div class="mb-3">
                    <h6 class="text-muted">Equipamento</h6>
                    <p class="fw-bold">${data.name}</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted">Serial</h6>
                    <p><code>${data.serial_number}</code></p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted">Categoria</h6>
                    <p>${data.category}</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted">Estado</h6>
                    <p>${statusBadge[data.status]}</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted">Localização</h6>
                    <p>${data.location || '-'}</p>
                </div>
                <a href="/equipments/${data.id}" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-eye"></i> Ver Detalhes
                </a>
            `;

            resultCard.style.display = 'block';
            noResult.style.display = 'none';
        })
        .catch(error => {
            console.error('Erro:', error);
            showNoResult();
        });
    }

    function showNoResult() {
        resultCard.style.display = 'none';
        noResult.style.display = 'block';
    }

    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') performSearch();
    });
</script>
@endpush

@endsection
