@extends('layouts.admin.base')

@section('content')
<main class="page-body"><div class="container-xl">
    @isset($header)
        <div class="page-header mb-4">{{ $header }}</div>
    @endisset
    {{ $slot }}
</div></main>
@endsection
