<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f1eb">
    <title>@yield('title', 'Início') · Ponto Conceito</title>
    <link rel="stylesheet" href="{{ asset('css/ponto.css') }}?v={{ filemtime(public_path('css/ponto.css')) }}">
</head>
<body class="@yield('body-class')">
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ url('/') }}" aria-label="Ponto Conceito, início">
                <span class="brand-logo-frame"><img src="{{ asset('img/cafe-conceito-logo.png') }}" alt="Café Conceito"></span>
                <span class="brand-divider" aria-hidden="true"></span>
                <span class="brand-name">Ponto <b>Conceito</b></span>
            </a>
            <nav class="header-nav" aria-label="Navegação principal">
                @yield('nav')
            </nav>
        </div>
    </header>

    <main>
        @if (session('sucesso'))
            <div class="flash-message" role="status">{{ session('sucesso') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer">
        <span><span class="brand-name">Ponto Conceito</span> · Um sistema do Café Conceito.</span>
        <span>Horários registrados no fuso de Brasília.</span>
    </footer>
</body>
</html>
