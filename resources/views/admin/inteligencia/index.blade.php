@extends('layouts.admin.base')
@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><h1 class="page-title">Inteligência climática</h1><div class="text-secondary">Recomendações automáticas de operação para os próximos sete dias.</div></div><div class="col-auto"><form method="POST" action="{{ route('admin.inteligencia.sincronizar') }}">@csrf<button class="btn btn-primary">Atualizar previsão agora</button></form></div></div></div>
    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $class) @if (session($key))<div class="alert alert-{{ $class }}">{{ session($key) }}</div>@endif @endforeach
    <div class="alert alert-info">Regras automáticas do sistema: pulverização com vento de 3 a 10 km/h; aração após três dias ensolarados (mínimo provisório de 4 h de sol/dia) depois da chuva; calagem com vento até 8 km/h, umidade estimada do solo entre 0,12 e 0,30 m³/m³ e sem previsão de chuva intensa. Os limites provisórios consideram dia seco até 1 mm, estiagem após 7 dias ensolarados e período chuvoso após 3 dias com chuva; chuva intensa é estimada em 20 mm/dia, 10 mm/h ou trovoada. São estimativas meteorológicas; confirme as condições no campo. Última atualização: {{ $atualizadoEm ? \Illuminate\Support\Carbon::parse($atualizadoEm)->format('d/m/Y H:i') : 'ainda não sincronizada' }}.</div>
    @include('components.painel-inteligencia-clima')
</div></main>
@endsection
