<footer class="footer footer-transparent d-print-none">
    <div class="container-xl">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 py-3">
            <a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none text-secondary fw-semibold">
                <span class="avatar avatar-xs bg-green-lt text-green" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 4c-8 0-13 3.5-13 10a6 6 0 0 0 6 6c6.5 0 7-8 7-16Z"/><path d="M4 21c2-5 6-8 12-11"/></svg>
                </span>
                AgroNexo
            </a>
            <span class="text-secondary small">Gestão agrícola conectada</span>
            <span class="text-secondary small">© {{ date('Y') }} AgroNexo</span>
        </div>
    </div>
</footer>
