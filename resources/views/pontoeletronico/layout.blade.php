<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f6f3">
    <title>@yield('title', 'Ponto') · Sistema de ponto</title>
    <link rel="stylesheet" href="{{ asset('css/ponto.css') }}">
</head>
<body class="@yield('body-class')">
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ url('/') }}" aria-label="Sistema de ponto, início">
                <span class="brand-symbol"><span></span></span>
                <span class="brand-name">ponto<span>.</span></span>
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
        <span><span class="brand-name">ponto<span>.</span></span> · Registro simples, jornada transparente.</span>
        <span>Horários registrados no fuso de Brasília.</span>
    </footer>
</body>
</html>
