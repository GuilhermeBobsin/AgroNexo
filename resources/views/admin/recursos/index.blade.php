@extends('layouts.admin.base')

@section('content')
@php
    $tipos = ['trator' => 'Trator', 'implemento' => 'Implemento', 'pulverizador' => 'Pulverizador', 'colheitadeira' => 'Colheitadeira', 'outro' => 'Outro'];
    $statusLabels = ['disponivel' => 'Disponível', 'em_uso' => 'Em uso', 'manutencao' => 'Em manutenção'];
    $statusColors = ['disponivel' => 'green', 'em_uso' => 'blue', 'manutencao' => 'orange'];
@endphp
<main id="content" class="page-body">
    <div class="container-xl">
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center"><div class="col">
                <h2 class="page-title">Recursos</h2>
                <div class="text-secondary mt-1">Gerencie os equipamentos vinculados às propriedades.</div>
            </div><div class="col-auto ms-auto">
                <a href="{{ route('admin.recursos.create') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                    Novo recurso
                </a>
            </div></div>
        </div>

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="row row-deck row-cards mb-4">
            @foreach ([['Recursos cadastrados', $contagens->total ?? 0, 'blue'], ['Disponíveis', $contagens->disponiveis ?? 0, 'green'], ['Em uso', $contagens->em_uso ?? 0, 'azure'], ['Em manutenção', $contagens->manutencao ?? 0, 'orange']] as [$titulo, $valor, $cor])
                <div class="col-sm-6 col-xl-3"><div class="card"><div class="card-body"><div class="d-flex align-items-center">
                    <span class="avatar bg-{{ $cor }}-lt me-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M3 17h2l2-5h8l3 5h3"></path><path d="M5 17a2 2 0 1 0 4 0"></path><path d="M16 17a2 2 0 1 0 4 0"></path><path d="M7 12l1-5h7l2 5"></path></svg></span>
                    <div><div class="text-secondary">{{ $titulo }}</div><div class="h2 mb-0">{{ $valor }}</div></div>
                </div></div></div></div>
            @endforeach
        </div>

        <div class="card">
            <div class="table-responsive"><table class="table table-vcenter card-table">
                <thead><tr><th>Recurso</th><th>Tipo</th><th>Propriedade</th><th>Status</th><th class="w-1">Ações</th></tr></thead>
                <tbody>
                    @forelse ($recursos as $recurso)
                        <tr>
                            <td><a class="fw-bold" href="{{ route('admin.recursos.show', $recurso) }}">{{ $recurso->nome }}</a></td>
                            <td>{{ $tipos[$recurso->tipo] ?? 'Outro' }}</td>
                            <td>{{ $recurso->propriedade->nome }}</td>
                            <td><span class="badge bg-{{ $statusColors[$recurso->status] ?? 'secondary' }}-lt">{{ $statusLabels[$recurso->status] ?? $recurso->status }}</span></td>
                            <td><div class="btn-list flex-nowrap">
                                <a href="{{ route('admin.recursos.show', $recurso) }}" class="btn btn-sm">Ver</a>
                                <a href="{{ route('admin.recursos.edit', $recurso) }}" class="btn btn-sm">Editar</a>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-5">Nenhum recurso cadastrado ainda. <a href="{{ route('admin.recursos.create') }}">Cadastre o primeiro recurso.</a></td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </div>

        @if ($recursos->hasPages())<div class="d-flex mt-4"><ul class="pagination ms-auto">
            <li class="page-item {{ $recursos->onFirstPage() ? 'disabled' : '' }}">@if ($recursos->onFirstPage())<span class="page-link">Anterior</span>@else<a class="page-link" href="{{ $recursos->previousPageUrl() }}">Anterior</a>@endif</li>
            @foreach ($recursos->getUrlRange(1, $recursos->lastPage()) as $page => $url)<li class="page-item {{ $page == $recursos->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>@endforeach
            <li class="page-item {{ !$recursos->hasMorePages() ? 'disabled' : '' }}">@if ($recursos->hasMorePages())<a class="page-link" href="{{ $recursos->nextPageUrl() }}">Próximo</a>@else<span class="page-link">Próximo</span>@endif</li>
        </ul></div>@endif
    </div>
</main>
@endsection
