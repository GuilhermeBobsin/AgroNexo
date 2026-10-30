@extends('layouts.admin.base')
@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h1 class="page-title">Inteligência climática</h1><div class="text-secondary">Recomendações automáticas por talhão para os próximos sete dias.</div></div></div>
    <div class="alert alert-info">
        <strong>Critérios orientativos</strong>
        <div class="small mt-1">Pulverização: vento abaixo de 3 km/h gera atenção; 3–6,5 km/h é favorável; acima de 6,5 até 9,5 km/h gera atenção; acima de 9,5 km/h é desfavorável. Umidade relativa abaixo de 55% ou temperatura acima de 30 °C também torna a avaliação desfavorável. Chuva durante ou até duas horas após o horário avaliado gera alerta; respeite o intervalo sem chuva da bula. A Epagri recomenda cautela adicional acima de 28 °C para gotas finas. Aração: a umidade estimada do solo (3–9 cm) é o indicador principal. A faixa 0,12–0,30 m³/m³ é provisória e deve ser confirmada com teste do torrão, pois a friabilidade varia por solo. Calagem: abaixo de 10 km/h favorável; 10–15 atenção; acima de 15 até 30 desfavorável; acima de 30 alerta de perda elevada/proibitiva. Os limites de vento da calagem são parâmetros operacionais do AgroNexo, não faixas universais das fontes. As orientações alertam, mas não bloqueiam tarefas. O modelo usa grade próxima às coordenadas, não mede o talhão; confirme a condição local e siga recomendações técnicas e rótulo/bula.</div>
        <div class="small mt-2">Fontes: <a href="https://publicacoes.epagri.sc.gov.br/SP/article/download/1587/1458" target="_blank" rel="noopener">Epagri — Tecnologia de aplicação</a>, <a href="https://www.infoteca.embrapa.br/infoteca/bitstream/doc/1179274/1/DOC-477.pdf" target="_blank" rel="noopener">Embrapa — Tecnologia de aplicação</a>, <a href="https://www.infoteca.cnptia.embrapa.br/infoteca/bitstream/doc/1040211/1/ID43609-2016LVTrigo.pdf" target="_blank" rel="noopener">Embrapa — avaliação de friabilidade</a> e <a href="https://www.infoteca.cnptia.embrapa.br/infoteca/bitstream/doc/1015113/1/p59.pdf" target="_blank" rel="noopener">Embrapa — manejo do calcário</a>. Última atualização geral: {{ $atualizadoEm ? \Illuminate\Support\Carbon::parse($atualizadoEm)->format('d/m/Y H:i') : 'ainda não sincronizada' }}.</div>
    </div>
    @include('components.painel-inteligencia-clima')
</div></main>
@endsection
