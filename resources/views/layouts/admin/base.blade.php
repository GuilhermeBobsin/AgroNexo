<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroNexo - Gestão Agrícola</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
</head>
<body>
    @include('layouts.admin.header')
        @yield('content')
    @include('layouts.admin.footer')
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
    <script src="{{ asset('js/tabler-theme.js') }}"></script>
</body>
</html>