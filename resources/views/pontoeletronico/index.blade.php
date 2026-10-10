@extends('pontoeletronico.layout')

@section('title', 'Ponto eletrônico para a equipe Café Conceito')
@section('body-class', 'landing-page')

@section('nav')
    <a href="#como-funciona">Como funciona</a>
    <a href="#para-equipes">Para equipes</a>
    <a class="button button-small button-dark" href="{{ url('/login') }}">Acessar sistema <span aria-hidden="true">↗</span></a>
@endsection

@section('content')
<section class="hero-section page-container">
    <div class="hero-copy">
        <div class="eyebrow"><span class="eyebrow-dot"></span> A ROTINA DA EQUIPE, COM A CLAREZA DO CAFÉ CONCEITO</div>
        <h1>A jornada da equipe.<br><span>Simples, do começo ao fim.</span></h1>
        <p>Ponto Conceito é o sistema do Café Conceito para registrar entrada, intervalo, retorno e saída — com horários claros e histórico em um só lugar.</p>
        <div class="hero-actions">
            <a class="button button-primary" href="{{ url('/login') }}">Entrar no sistema <span aria-hidden="true">↗</span></a>
            <a class="text-link" href="#como-funciona">Conhecer o fluxo <span aria-hidden="true">↓</span></a>
        </div>
        <div class="hero-note"><span class="note-check">✓</span> Pensado para a rotina das unidades Café Conceito</div>
    </div>

    <div class="hero-visual" aria-label="Prévia ilustrativa de uma jornada de trabalho">
        <div class="visual-orbit orbit-one"></div>
        <div class="visual-orbit orbit-two"></div>
        <div class="clock-card">
            <div class="clock-card-top"><span>PONTO CONCEITO · JORNADA DE HOJE</span><span class="live-pill"><i></i> AO VIVO</span></div>
            <div class="clock-time">{{ now()->format('H:i') }}<span>:{{ now()->format('s') }}</span></div>
            <div class="clock-date">HOJE · {{ now()->format('d/m/Y') }}</div>
            <div class="mini-progress"><span></span></div>
            <div class="clock-steps">
                <div class="clock-step step-done"><span class="step-icon">✓</span><span><b>Entrada</b><small>08:02</small></span></div>
                <div class="clock-step step-next"><span class="step-icon">02</span><span><b>Intervalo</b><small>Próxima etapa</small></span></div>
                <div class="clock-step"><span class="step-icon">03</span><span><b>Retorno</b><small>Aguardando</small></span></div>
                <div class="clock-step"><span class="step-icon">04</span><span><b>Saída</b><small>Aguardando</small></span></div>
            </div>
            <div class="clock-card-foot"><span class="foot-avatar">C</span><span>Um registro claro a cada momento.</span></div>
        </div>
        <div class="floating-tag tag-one"><span>✓</span> Registro salvo</div>
        <div class="floating-tag tag-two"><span class="tag-dot"></span> Tudo em ordem</div>
        <div class="visual-caption">PRÉVIA ILUSTRATIVA DA JORNADA</div>
    </div>
</section>

<section class="steps-section" id="como-funciona">
    <div class="page-container">
        <div class="section-heading">
            <div><div class="eyebrow">DO PRIMEIRO AO ÚLTIMO REGISTRO</div><h2>Quatro momentos.<br><span>Uma jornada bem cuidada.</span></h2></div>
            <p>O sistema mostra qual é o próximo passo. Você registra, confere e segue o dia com tranquilidade.</p>
        </div>
        <div class="step-cards">
            <article class="step-card"><span class="card-number">01</span><div class="step-symbol">↗</div><h3>Entrada</h3><p>Comece a jornada registrando seu início de expediente.</p></article>
            <article class="step-card"><span class="card-number">02</span><div class="step-symbol">Ⅱ</div><h3>Intervalo</h3><p>Marque o momento em que você inicia sua pausa.</p></article>
            <article class="step-card"><span class="card-number">03</span><div class="step-symbol">▶</div><h3>Retorno</h3><p>Registre a volta do intervalo e continue sua jornada.</p></article>
            <article class="step-card"><span class="card-number">04</span><div class="step-symbol">↙</div><h3>Saída</h3><p>Finalize o dia com o horário de encerramento.</p></article>
        </div>
    </div>
</section>

<section class="team-section" id="para-equipes">
    <div class="page-container team-panel">
        <div><div class="eyebrow eyebrow-light">FEITO PARA O DIA A DIA DO CAFÉ CONCEITO</div><h2>Uma equipe em sintonia,<br>em cada unidade.</h2><p>Funcionários acompanham o próprio histórico mensal. A administração consulta os registros da equipe e cadastra novos funcionários em um só lugar.</p></div>
        <div class="team-side"><div class="team-stat"><strong>01</strong><span>acesso individual</span></div><div class="team-stat"><strong>04</strong><span>marcações por jornada</span></div><a class="button button-light" href="{{ url('/login') }}">Acessar o sistema <span aria-hidden="true">↗</span></a></div>
        <div class="team-decoration">P</div>
    </div>
</section>
@endsection
