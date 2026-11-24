<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AgroNexo reúne gestão de propriedades, equipes, tarefas, estoque e inteligência climática em uma plataforma para o dia a dia agrícola.">
    <meta name="theme-color" content="#123b2e">
    <title>AgroNexo — Gestão agrícola conectada</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --agro-ink:#17362d; --agro-green:#176b50; --agro-lime:#d7ef87; --agro-muted:#66796f; }
        * { box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body { margin:0; color:var(--agro-ink); background:#fbfcf9; font-family:'DM Sans',sans-serif; }
        h1,h2,h3,.brand { font-family:'Manrope',sans-serif; }
        a { text-decoration:none; }
        .site-nav { position:absolute; z-index:2; inset:0 0 auto; color:#fff; }
        .nav-inner { max-width:1240px; min-height:84px; margin:auto; padding:0 28px; display:flex; align-items:center; justify-content:space-between; gap:24px; }
        .brand { display:inline-flex; align-items:center; gap:11px; color:white; font-size:21px; font-weight:800; letter-spacing:-.7px; }
        .brand-mark { width:38px; height:38px; display:grid; place-items:center; border:1px solid #ffffff55; border-radius:13px; color:var(--agro-lime); background:#ffffff12; }
        .nav-links { display:flex; align-items:center; gap:28px; color:#ffffffe0; font-size:14px; font-weight:600; }
        .nav-links a { color:inherit; transition:color .2s; }
        .nav-links a:hover { color:var(--agro-lime); }
        .nav-actions { display:flex; align-items:center; gap:12px; }
        .btn-outline-light { border-color:#ffffff9c; color:#fff; }
        .btn-outline-light:hover { color:#153b2f; background:white; }
        .btn-lime { border:0; color:#19382b; background:var(--agro-lime); font-weight:700; }
        .btn-lime:hover { color:#19382b; background:#e3f6a5; }
        .hero { min-height:690px; position:relative; display:flex; align-items:center; overflow:hidden; color:white; background:#18382e; }
        .hero::before { content:""; position:absolute; inset:0; background-image:linear-gradient(90deg,rgba(9,32,25,.91) 0%,rgba(12,39,30,.76) 43%,rgba(12,39,30,.28) 100%),linear-gradient(0deg,rgba(9,32,25,.42),transparent 36%),url('https://images.unsplash.com/photo-1719679642793-26bf780693ea?auto=format&fit=crop&w=2400&q=85'); background-position:center,center,center 56%; background-size:cover; transform:scale(1.01); }
        .hero-content { position:relative; width:min(1240px,100%); margin:0 auto; padding:146px 28px 105px; }
        .eyebrow { display:inline-flex; align-items:center; gap:9px; margin-bottom:22px; color:#e2f3ba; font-size:12px; font-weight:700; letter-spacing:1.45px; text-transform:uppercase; }
        .eyebrow::before { content:""; width:24px; height:1px; background:var(--agro-lime); }
        .hero h1 { max-width:740px; margin:0; color:white; font-size:clamp(44px,6.1vw,76px); font-weight:800; letter-spacing:-3.5px; line-height:1.04; }
        .hero h1 span { color:var(--agro-lime); }
        .hero-copy { max-width:605px; margin:24px 0 30px; color:#f1f5f1; font-size:18px; line-height:1.75; }
        .hero-actions { display:flex; align-items:center; flex-wrap:wrap; gap:12px; }
        .hero-actions .btn { padding:13px 19px; border-radius:9px; }
        .hero-note { margin-top:24px; color:#ffffffb8; font-size:12px; }
        .photo-credit { position:absolute; right:18px; bottom:12px; color:#ffffffbf; font-size:10px; }
        .photo-credit a { color:inherit; text-decoration:underline; }
        .trustbar { background:white; border-bottom:1px solid #e8ede7; }
        .trust-inner { max-width:1240px; margin:auto; padding:24px 28px; display:flex; align-items:center; justify-content:space-between; gap:25px; }
        .trust-label { color:#718078; font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; }
        .trust-items { display:flex; flex-wrap:wrap; gap:14px 30px; color:#314a3f; font-size:14px; font-weight:700; }
        .trust-items span { display:inline-flex; align-items:center; gap:8px; }
        .trust-items svg { color:var(--agro-green); }
        .section { padding:94px 28px; }
        .section-inner { max-width:1184px; margin:auto; }
        .section-heading { max-width:690px; margin-bottom:44px; }
        .section-kicker { margin-bottom:12px; color:var(--agro-green); font-size:12px; font-weight:800; letter-spacing:1.2px; text-transform:uppercase; }
        .section h2 { margin:0; color:#19372d; font-size:clamp(30px,4vw,45px); font-weight:800; letter-spacing:-1.8px; line-height:1.14; }
        .section-heading p { margin:17px 0 0; color:#63766b; font-size:16px; line-height:1.75; }
        .feature-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:18px; }
        .feature-card { min-height:242px; padding:25px; border:1px solid #e6ece4; border-radius:15px; background:white; box-shadow:0 8px 26px #1a3a2c08; transition:transform .2s,box-shadow .2s; }
        .feature-card:hover { transform:translateY(-3px); box-shadow:0 15px 32px #1a3a2c12; }
        .feature-icon { width:46px; height:46px; display:grid; place-items:center; margin-bottom:23px; border-radius:13px; color:#176b50; background:#eaf5e8; }
        .feature-card h3 { margin:0 0 10px; color:#19372d; font-size:18px; font-weight:800; letter-spacing:-.35px; }
        .feature-card p { margin:0; color:#66796f; font-size:14px; line-height:1.75; }
        .workflow { padding:0 28px 94px; }
        .workflow-panel { max-width:1184px; margin:auto; padding:44px; display:grid; grid-template-columns:.9fr 1.1fr; align-items:center; gap:50px; overflow:hidden; border-radius:20px; color:white; background:#173d30; }
        .workflow-panel h2 { margin:0; color:white; font-size:clamp(29px,4vw,42px); font-weight:800; letter-spacing:-1.5px; line-height:1.13; }
        .workflow-panel p { margin:16px 0 0; color:#d2dfd6; line-height:1.75; }
        .workflow-list { display:grid; gap:14px; }
        .workflow-item { display:flex; align-items:flex-start; gap:14px; padding:16px; border:1px solid #ffffff24; border-radius:12px; background:#ffffff0a; }
        .workflow-number { flex:0 0 30px; width:30px; height:30px; display:grid; place-items:center; border-radius:9px; color:#19382c; background:var(--agro-lime); font-weight:800; }
        .workflow-item strong { display:block; margin:1px 0 4px; color:#fff; font-size:14px; }
        .workflow-item span { display:block; color:#c5d6ca; font-size:13px; line-height:1.55; }
        .closing { padding:0 28px 90px; }
        .closing-inner { max-width:1184px; margin:auto; padding:42px; display:flex; align-items:center; justify-content:space-between; gap:28px; border:1px solid #e4ecdf; border-radius:18px; background:#f1f6e9; }
        .closing h2 { margin:0; color:#19372d; font-size:28px; font-weight:800; letter-spacing:-1px; }
        .closing p { margin:9px 0 0; color:#63766b; }
        .site-footer { padding:26px 28px; border-top:1px solid #e6ece4; background:#fff; }
        .footer-inner { max-width:1184px; margin:auto; display:flex; justify-content:space-between; align-items:center; gap:20px; color:#718078; font-size:12px; }
        .footer-brand { display:flex; align-items:center; gap:9px; color:#294438; font-weight:800; font-size:14px; }
        .footer-brand .brand-mark { width:29px; height:29px; border-color:#dce8d8; color:#176b50; background:#eff6e8; }
        .has-reveal .reveal { opacity:0; transform:translateY(18px); transition:opacity .65s ease,transform .65s cubic-bezier(.2,.7,.2,1); }
        .has-reveal .reveal.is-visible { opacity:1; transform:translateY(0); }
        .feature-card.reveal:nth-child(3n + 2), .workflow-item.reveal:nth-child(2) { transition-delay:.08s; }
        .feature-card.reveal:nth-child(3n), .workflow-item.reveal:nth-child(3) { transition-delay:.15s; }
        @media(max-width:850px) { .nav-inner{padding:0 20px}.nav-links{display:none}.hero{min-height:650px}.hero-content{padding:135px 22px 100px}.hero h1{letter-spacing:-2px}.trust-inner{align-items:flex-start;flex-direction:column}.section{padding:72px 20px}.feature-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.workflow{padding:0 20px 72px}.workflow-panel{padding:32px;grid-template-columns:1fr;gap:28px}.closing{padding:0 20px 72px}.closing-inner{align-items:flex-start;flex-direction:column;padding:30px}.site-footer{padding:23px 20px} }
        @media(max-width:560px) { .nav-inner{min-height:72px}.brand{font-size:18px}.brand-mark{width:34px;height:34px}.nav-actions{gap:7px}.nav-actions .btn{padding:.45rem .65rem;font-size:12px}.hero{min-height:670px}.hero::before{background-position:center,center,62% center;background-image:linear-gradient(90deg,rgba(9,32,25,.88),rgba(12,39,30,.54)),linear-gradient(0deg,rgba(9,32,25,.5),transparent 45%),url('https://images.unsplash.com/photo-1719679642793-26bf780693ea?auto=format&fit=crop&w=1600&q=80')}.hero-content{padding:130px 20px 96px}.hero h1{font-size:46px}.hero-copy{font-size:16px;line-height:1.65}.trust-inner{padding:21px 20px}.trust-items{gap:13px 18px;font-size:12px}.feature-grid{grid-template-columns:1fr}.feature-card{min-height:auto}.workflow-panel{padding:26px 21px}.closing h2{font-size:24px}.footer-inner{align-items:flex-start;flex-direction:column} }
        @media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}*,*::before,*::after{transition:none!important}}
    </style>
</head>
<body>
    <header class="site-nav">
        <nav class="nav-inner" aria-label="Navegação principal">
            <a class="brand" href="{{ url('/') }}" aria-label="AgroNexo, página inicial">
                <span class="brand-mark" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 4c-8 0-13 3.5-13 10a6 6 0 0 0 6 6c6.5 0 7-8 7-16Z"/><path d="M4 21c2-5 6-8 12-11"/></svg></span>
                AgroNexo
            </a>
            <div class="nav-links"><a href="#recursos">Recursos</a><a href="#como-funciona">Como funciona</a><a href="#acesso">Acesso</a></div>
            <div class="nav-actions">
                @auth
                    @php
                        $painel = match (auth()->user()->perfil) {
                            'admin' => route('admin.dashboard'),
                            'agronomo' => route('agronomo.dashboard'),
                            'operador' => route('operador.dashboard'),
                            default => route('dashboard'),
                        };
                    @endphp
                    <a class="btn btn-lime" href="{{ $painel }}">Ir para o painel <span aria-hidden="true">→</span></a>
                @else
                    <a class="btn btn-outline-light" href="{{ route('login') }}">Login</a>
                    @if (Route::has('register'))
                        <a class="btn btn-lime" href="{{ route('register') }}">Criar conta</a>
                    @endif
                @endauth
            </div>
        </nav>
    </header>

    <main>
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-content">
                <div class="eyebrow">Gestão agrícola em um só lugar</div>
                <h1 id="hero-title">Mais clareza para cuidar <span>de cada etapa.</span></h1>
                <p class="hero-copy">Organize propriedades, acompanhe tarefas da equipe e mantenha os dados da operação conectados — do planejamento ao trabalho no campo.</p>
                <div class="hero-actions">
                    @auth
                        <a class="btn btn-lime" href="{{ $painel }}">Ir para o painel <span aria-hidden="true">→</span></a>
                    @else
                        <a class="btn btn-lime" href="{{ route('login') }}">Acessar minha conta <span aria-hidden="true">→</span></a>
                        @if (Route::has('register'))
                            <a class="btn btn-outline-light" href="{{ route('register') }}">Conhecer o sistema</a>
                        @endif
                    @endauth
                </div>
                <p class="hero-note">Propriedades, equipe e operação trabalhando em sintonia.</p>
            </div>
            <div class="photo-credit">Imagem: <a href="https://unsplash.com/photos/an-aerial-view-of-a-farm-field-with-crops-2OYgrZAmvMA" target="_blank" rel="noopener noreferrer">Francesco Ungaro / Unsplash</a></div>
        </section>

        <section class="trustbar" aria-label="Visão geral dos recursos">
            <div class="trust-inner">
                <div class="trust-label">A operação conectada</div>
                <div class="trust-items">
                    <span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4 6 4v14M9 9h1m4 0h1m-6 4h1m4 0h1m-5 8v-4h4v4"/></svg>Propriedades</span>
                    <span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 5h16M4 12h16M4 19h16M8 3v4m8 3v4m-8 3v4"/></svg>Tarefas</span>
                    <span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16v14H4zM8 7V4h8v3m-8 5h8m-8 4h5"/></svg>Estoque</span>
                    <span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v2m0 14v2M4.2 7l1.7 1m12.2 8 1.7 1M3 12h2m14 0h2M4.2 17l1.7-1m12.2-8 1.7-1M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10Z"/></svg>Clima</span>
                </div>
            </div>
        </section>

        <section class="section" id="recursos" aria-labelledby="features-title">
            <div class="section-inner">
                <div class="section-heading reveal">
                    <div class="section-kicker">Uma visão integrada da fazenda</div>
                    <h2 id="features-title">Informação organizada para a rotina acontecer.</h2>
                    <p>O AgroNexo reúne cadastros e acompanhamento operacional em áreas conectadas, com acesso conforme o papel de cada pessoa na propriedade.</p>
                </div>
                <div class="feature-grid">
                    <article class="feature-card reveal"><div class="feature-icon"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 10h.01M15 10h.01M9 14h.01M15 14h.01M10 21v-4h4v4"/></svg></div><h3>Propriedades e talhões</h3><p>Cadastre propriedades, organize talhões e culturas e mantenha os dados da produção agrupados por área.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3"/><path d="M3 20v-1a6 6 0 0 1 12 0v1m3-9a3 3 0 1 0-2-5.2M18 14a5 5 0 0 1 3 4.6V20"/></svg></div><h3>Equipe e responsabilidades</h3><p>Administre os acessos de administradores, agrônomos e operadores vinculados às propriedades.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16M4 12h16M4 19h16M8 3v4m8 3v4m-8 3v4"/></svg></div><h3>Tarefas do campo</h3><p>Crie e atribua atividades, acompanhe o andamento e registre conclusão ou cancelamento com seu motivo.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16v14H4zM8 7V4h8v3m-8 5h8m-8 4h5"/></svg></div><h3>Produtos e estoque</h3><p>Controle produtos e saldos por propriedade; aplicações concluídas registram a saída correspondente do estoque.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19h16M5 16l4-5 3 2 6-8M15 5h3v3"/></svg></div><h3>Aplicações e recursos</h3><p>Registre aplicações realizadas, condições observadas e uso de recursos associados às tarefas.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v2m0 14v2M4.2 7l1.7 1m12.2 8 1.7 1M3 12h2m14 0h2M4.2 17l1.7-1m12.2-8 1.7-1M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10Z"/></svg></div><h3>Clima e inteligência</h3><p>Consulte previsões da Open-Meteo e orientações para pulverização, aração e calagem, com indicação da atualização dos dados.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16v14H4zM8 9h8M8 13h5m-9 6 4-4"/></svg></div><h3>Recomendações técnicas</h3><p>O agrônomo encaminha recomendações ao administrador, que pode transformá-las em tarefas ou registrar um parecer.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 13h4"/></svg></div><h3>Painéis e notificações</h3><p>Visualize indicadores e atividades conforme o perfil e receba atualizações sobre tarefas e recomendações.</p></article>
                    <article class="feature-card reveal"><div class="feature-icon"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4zM8 8h8M8 12h8m-8 4h5"/></svg></div><h3>Histórico operacional</h3><p>Acompanhe tarefas e aplicações registradas para apoiar a consulta e a organização das atividades realizadas.</p></article>
                </div>
            </div>
        </section>

        <section class="workflow" id="como-funciona" aria-labelledby="workflow-title">
            <div class="workflow-panel">
                <div><div class="section-kicker" style="color:#d7ef87">Do planejamento à execução</div><h2 id="workflow-title">Cada pessoa acompanha o que precisa fazer.</h2><p>O sistema conecta as decisões de gestão e orientação técnica ao trabalho atribuído em campo, mantendo registros da operação.</p></div>
                <div class="workflow-list">
                    <div class="workflow-item reveal"><div class="workflow-number">1</div><div><strong>Organize a estrutura</strong><span>Cadastre propriedades, talhões, culturas, equipe, recursos e produtos.</span></div></div>
                    <div class="workflow-item reveal"><div class="workflow-number">2</div><div><strong>Planeje e oriente</strong><span>Crie tarefas e encaminhe recomendações técnicas para análise.</span></div></div>
                    <div class="workflow-item reveal"><div class="workflow-number">3</div><div><strong>Execute e registre</strong><span>Acompanhe o status, aplicações realizadas, estoque e condições da atividade.</span></div></div>
                    <div class="workflow-item reveal"><div class="workflow-number">4</div><div><strong>Consulte as condições climáticas</strong><span>Veja dados meteorológicos e as orientações calculadas pelo módulo de inteligência.</span></div></div>
                </div>
            </div>
        </section>

        <section class="closing" id="acesso" aria-labelledby="closing-title">
            <div class="closing-inner"><div><h2 id="closing-title">Sua operação, mais conectada.</h2><p>Acesse o AgroNexo para acompanhar a gestão e as atividades da propriedade.</p></div>
                <div class="hero-actions">
                    @auth
                        <a class="btn btn-success" href="{{ $painel }}">Ir para o painel <span aria-hidden="true">→</span></a>
                    @else
                        <a class="btn btn-success" href="{{ route('login') }}">Fazer login <span aria-hidden="true">→</span></a>
                        @if (Route::has('register')) <a class="btn btn-outline-success" href="{{ route('register') }}">Criar conta</a> @endif
                    @endauth
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner"><a class="footer-brand" href="{{ url('/') }}"><span class="brand-mark" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 4c-8 0-13 3.5-13 10a6 6 0 0 0 6 6c6.5 0 7-8 7-16Z"/><path d="M4 21c2-5 6-8 12-11"/></svg></span>AgroNexo</a><span>Gestão agrícola conectada.</span><span>© {{ date('Y') }} AgroNexo</span></div>
    </footer>
    <script>
        (() => {
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reducedMotion || !('IntersectionObserver' in window)) return;

            const elements = document.querySelectorAll('.reveal');
            if (!elements.length) return;

            document.documentElement.classList.add('has-reveal');
            const observer = new IntersectionObserver((entries, currentObserver) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    currentObserver.unobserve(entry.target);
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -36px 0px' });

            elements.forEach((element) => observer.observe(element));
        })();
    </script>
</body>
</html>
