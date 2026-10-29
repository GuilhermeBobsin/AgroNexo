@extends('layouts.admin.base')
@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h1 class="page-title">Inteligência climática</h1><div class="text-secondary">Recomendações automáticas por talhão para os próximos sete dias.</div></div></div>
    <div class="alert alert-info">Regras automáticas provisórias: pulverização com vento de 3 a 10 km/h e sem precipitação prevista no período; aração bloqueada se houver precipitação no dia anterior e aguardando três dias ensolarados (mínimo provisório de 4 h/dia) depois da chuva. Calagem: vento até 8 km/h, umidade estimada nos 3–9 cm de 0,12 a 0,30 m³/m³ e sem chuva intensa estimada em 20 mm/dia, 10 mm/h ou trovoada. O modelo representa uma grade meteorológica próxima das coordenadas e não mede o talhão diretamente; confirme as condições no campo e siga a bula/receita. Última atualização geral: {{ $atualizadoEm ? \Illuminate\Support\Carbon::parse($atualizadoEm)->format('d/m/Y H:i') : 'ainda não sincronizada' }}.</div>
    @include('components.painel-inteligencia-clima')
</div></main>
@endsection
