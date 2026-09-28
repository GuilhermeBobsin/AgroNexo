@extends('layouts.admin.base')
@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h1 class="page-title">Inteligência climática</h1><div class="text-secondary">Recomendações automáticas por talhão para os próximos sete dias.</div></div></div>
    <div class="alert alert-info">Pulverização: vento de 3 a 10 km/h. Aração: três dias ensolarados (mínimo provisório de 4 h de sol/dia) após chuva; dia seco até 1 mm, estiagem após 7 dias ensolarados e período chuvoso após 3 dias com chuva. Calagem: vento até 8 km/h, umidade estimada do solo de 0,12 a 0,30 m³/m³; chuva intensa estimada em 20 mm/dia, 10 mm/h ou trovoada. Confirme as condições no campo. Última atualização: {{ $atualizadoEm ? \Illuminate\Support\Carbon::parse($atualizadoEm)->format('d/m/Y H:i') : 'ainda não sincronizada' }}.</div>
    @include('components.painel-inteligencia-clima')
</div></main>
@endsection
