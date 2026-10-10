@extends('pontoeletronico.layout')

@section('title', 'Entrar')
@section('body-class', 'auth-page')
@section('nav')<a class="header-back" href="{{ url('/') }}">← Voltar ao início</a>@endsection

@section('content')
<section class="auth-layout page-container">
    <div class="auth-intro">
        <div class="eyebrow"><span class="eyebrow-dot"></span> BEM-VINDO DE VOLTA</div>
        <h1>Sua jornada<br>começa <span>por aqui.</span></h1>
        <p>Entre com seu CPF e senha. O sistema identifica seu perfil e abre a área certa para você.</p>
        <div class="profile-options">
            <div class="profile-option"><span class="profile-icon">F</span><span><b>Funcionário</b><small>Registre o ponto e veja seu histórico.</small></span><span class="option-arrow">↗</span></div>
            <div class="profile-option"><span class="profile-icon profile-icon-admin">A</span><span><b>Administrador</b><small>Acompanhe a equipe e cadastre funcionários.</small></span><span class="option-arrow">↗</span></div>
        </div>
    </div>

    <div class="form-card login-card">
        <div class="form-card-heading"><span class="form-icon">↗</span><div><div class="eyebrow">ACESSO SEGURO</div><h2>Entrar no sistema</h2></div></div>
        <p class="form-intro">Use o CPF e a senha cadastrados pela administração.</p>

        @if ($errors->any())
            <div class="form-alert" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ url('/login') }}" class="form-stack">
            @csrf
            <label for="cpf">CPF</label>
            <input id="cpf" name="cpf" type="text" inputmode="numeric" autocomplete="username" value="{{ old('cpf') }}" placeholder="000.000.000-00" required maxlength="14">

            <div class="label-row"><label for="senha">Senha</label></div>
            <input id="senha" name="senha" type="password" autocomplete="current-password" placeholder="Digite sua senha" required>

            <button type="submit" class="button button-primary button-full">Entrar <span aria-hidden="true">↗</span></button>
        </form>
        <div class="secure-note"><span>●</span> Seu acesso é pessoal e protegido.</div>
    </div>
</section>
@endsection
