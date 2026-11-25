<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f4f8f1">
    <title>@yield('title', 'Acessar · AgroNexo')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@1.6.0/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --auth-ink:#18372d; --auth-green:#176b50; --auth-muted:#6b7e74; }
        * { box-sizing:border-box; }
        body.auth-body { min-height:100vh; margin:0; color:var(--auth-ink); background:radial-gradient(ellipse at 10% 10%,#e8f2df 0,transparent 38%),radial-gradient(ellipse at 96% 90%,#e7f0e5 0,transparent 35%),#f7f9f5; font-family:'DM Sans',sans-serif; }
        .auth-page { min-height:100vh; padding:34px 22px; display:flex; align-items:center; justify-content:center; }
        .auth-wrap { width:min(1040px,100%); }
        .auth-topbar { margin-bottom:18px; display:flex; align-items:center; justify-content:space-between; gap:16px; }
        .auth-brand { display:inline-flex; align-items:center; gap:10px; color:#18372d; font-family:'Manrope',sans-serif; font-size:20px; font-weight:800; letter-spacing:-.6px; }
        .auth-logo { width:38px; height:38px; display:grid; place-items:center; border-radius:12px; color:#d8ef9a; background:#176b50; box-shadow:0 7px 16px #176b5026; }
        .auth-toplink { color:#527063; font-size:13px; font-weight:600; }
        .auth-toplink:hover { color:var(--auth-green); }
        .auth-card { min-height:590px; display:grid; grid-template-columns:1fr 1fr; overflow:hidden; border:1px solid #e1e9df; border-radius:23px; background:#fff; box-shadow:0 24px 70px #18372d16; }
        .auth-aside { position:relative; min-height:590px; padding:52px; display:flex; flex-direction:column; justify-content:space-between; overflow:hidden; color:white; background:linear-gradient(145deg,#174c3b 0%,#12392e 72%,#102f27 100%); }
        .auth-aside::before,.auth-aside::after { content:""; position:absolute; border:1px solid #ffffff18; border-radius:50%; pointer-events:none; }
        .auth-aside::before { width:460px;height:460px;right:-160px;bottom:-160px; }
        .auth-aside::after { width:330px;height:330px;right:-95px;bottom:-95px; }
        .aside-content,.aside-foot { position:relative; z-index:1; }
        .aside-label { display:inline-flex; align-items:center; gap:8px; color:#d9eead; font-size:11px; font-weight:800; letter-spacing:1.4px; text-transform:uppercase; }
        .aside-label::before { content:""; width:20px; height:1px; background:#d9eead; }
        .auth-aside h1 { max-width:390px; margin:23px 0 15px; color:white; font-family:'Manrope',sans-serif; font-size:42px; font-weight:800; letter-spacing:-1.9px; line-height:1.12; }
        .auth-aside p { max-width:370px; margin:0; color:#d2e1d7; font-size:15px; line-height:1.75; }
        .aside-points { margin-top:34px; display:grid; gap:13px; }
        .aside-point { display:flex; align-items:center; gap:11px; color:#f2f7f2; font-size:13px; }
        .aside-check { width:24px;height:24px;display:grid;place-items:center;flex:0 0 24px;border:1px solid #ffffff38;border-radius:8px;color:#d9eead; }
        .aside-foot { color:#aec8b7; font-size:11px; }
        .auth-form-panel { padding:52px clamp(28px,5vw,62px); display:flex; align-items:center; }
        .auth-form-inner { width:100%; max-width:390px; margin:0 auto; }
        .auth-form-title { margin:0; color:#18372d; font-family:'Manrope',sans-serif; font-size:29px; font-weight:800; letter-spacing:-1px; }
        .auth-form-subtitle { margin:8px 0 29px; color:var(--auth-muted); font-size:14px; line-height:1.65; }
        .auth-label { margin-bottom:8px; color:#2b463a; font-size:13px; font-weight:700; }
        .auth-control { min-height:48px; border-color:#dce6de; border-radius:9px; color:#18372d; box-shadow:none!important; }
        .auth-control:focus { border-color:#68a68a; box-shadow:0 0 0 3px #176b5018!important; }
        .auth-control::placeholder { color:#a1afa6; }
        .auth-input-wrap { position:relative; }
        .auth-input-wrap .auth-control { padding-right:50px; }
        .password-toggle { position:absolute; top:50%; right:7px; width:36px; height:36px; display:grid; place-items:center; transform:translateY(-50%); border:0; border-radius:7px; color:#708279; background:transparent; }
        .password-toggle:hover,.password-toggle:focus-visible { color:var(--auth-green); background:#edf5ed; outline:none; }
        .password-toggle svg { width:19px;height:19px; }
        .auth-help-row { margin:11px 0 22px; display:flex; align-items:center; justify-content:space-between; gap:14px; }
        .auth-check { display:flex; align-items:center; gap:8px; color:#687a70; font-size:12px; }
        .auth-check input { width:15px;height:15px; accent-color:var(--auth-green); }
        .auth-link { color:var(--auth-green); font-size:12px; font-weight:700; }
        .auth-link:hover { color:#104c38; text-decoration:underline; }
        .auth-submit { min-height:48px; display:flex; align-items:center; justify-content:center; gap:9px; border:0; border-radius:9px; color:#fff; background:#176b50; font-size:14px; font-weight:700; box-shadow:0 8px 18px #176b5024; transition:background .18s,transform .18s,box-shadow .18s; }
        .auth-submit:hover { color:white; transform:translateY(-1px); background:#125940; box-shadow:0 11px 21px #176b5030; }
        .auth-divider { margin:21px 0; display:flex; align-items:center; gap:13px; color:#9aa89f; font-size:11px; }
        .auth-divider::before,.auth-divider::after { content:""; height:1px; flex:1; background:#e8eee8; }
        .auth-switch { margin:0; color:#75847b; text-align:center; font-size:13px; }
        .auth-security-note { margin-top:21px; display:flex; justify-content:center; align-items:center; gap:7px; color:#8a9990; font-size:11px; }
        .auth-alert { margin-bottom:18px; padding:12px 14px; border:1px solid #f1d4d0; border-radius:9px; color:#9a3428; background:#fff6f4; font-size:13px; }
        .auth-success { border-color:#d2e7d4; color:#24633a; background:#f2faf1; }
        .auth-error { margin-top:6px; color:#b9382b; font-size:12px; }
        .auth-footer { margin-top:18px; color:#849189; text-align:center; font-size:11px; }
        @media(max-width:760px) { .auth-page{padding:24px 16px}.auth-card{min-height:0;grid-template-columns:1fr}.auth-aside{display:none}.auth-form-panel{padding:38px 25px 34px}.auth-form-inner{max-width:440px}.auth-topbar{margin-bottom:13px} }
        @media(max-width:420px) { .auth-page{padding:18px 12px}.auth-form-panel{padding:30px 19px}.auth-form-title{font-size:26px}.auth-help-row{align-items:flex-start;flex-direction:column} }
        @media(prefers-reduced-motion:reduce) { *,*::before,*::after{scroll-behavior:auto!important;transition:none!important} }
    </style>
</head>
<body class="auth-body">
    <main class="auth-page">
        <div class="auth-wrap">
            <header class="auth-topbar">
                <a class="auth-brand" href="{{ url('/') }}">
                    <span class="auth-logo" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 4c-8 0-13 3.5-13 10a6 6 0 0 0 6 6c6.5 0 7-8 7-16Z"/><path d="M4 21c2-5 6-8 12-11"/></svg></span>
                    AgroNexo
                </a>
                <a class="auth-toplink" href="{{ url('/') }}">Voltar ao início <span aria-hidden="true">→</span></a>
            </header>

            <section class="auth-card">
                <aside class="auth-aside" aria-label="Sobre o AgroNexo">
                    <div class="aside-content">
                        <div class="aside-label">Gestão agrícola conectada</div>
                        <h1>O campo organizado começa por aqui.</h1>
                        <p>Tenha propriedade, equipe e atividades em um só lugar e acompanhe cada etapa da operação agrícola.</p>
                        <div class="aside-points">
                            <div class="aside-point"><span class="aside-check"><i class="ti ti-check"></i></span>Planejamento e acompanhamento de tarefas</div>
                            <div class="aside-point"><span class="aside-check"><i class="ti ti-check"></i></span>Gestão de propriedades, produtos e recursos</div>
                            <div class="aside-point"><span class="aside-check"><i class="ti ti-check"></i></span>Recomendações e inteligência climática</div>
                        </div>
                    </div>
                    <div class="aside-foot">AgroNexo · Gestão para o dia a dia no campo</div>
                </aside>

                <div class="auth-form-panel">
                    <div class="auth-form-inner">@yield('content')</div>
                </div>
            </section>
            <footer class="auth-footer">© {{ date('Y') }} AgroNexo · Gestão agrícola</footer>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                if (!input) return;

                const showPassword = input.type === 'password';
                input.type = showPassword ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(showPassword));
                button.setAttribute('aria-label', showPassword ? 'Ocultar senha' : 'Mostrar senha');
                button.setAttribute('title', showPassword ? 'Ocultar senha' : 'Mostrar senha');
                button.innerHTML = showPassword
                    ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M9.9 5.2A10.7 10.7 0 0 1 12 5c5 0 8.5 4 9.5 7-.4 1.1-1.1 2.2-2.1 3.2M6.2 6.2C4.4 7.4 3.1 9.3 2.5 12c1 3 4.5 7 9.5 7 1.4 0 2.7-.3 3.8-.9"/></svg>'
                    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"/><circle cx="12" cy="12" r="2.5"/></svg>';
            });
        });
    </script>
</body>
</html>
