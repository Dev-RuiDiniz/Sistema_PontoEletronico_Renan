@extends('pontoeletronico.layout')

@section('title', 'Cadastrar funcionário')
@section('body-class', 'workspace-page registration-page')
@section('nav')
    <a href="{{ url('/admin') }}">Acompanhamento</a>
    <a class="nav-current" href="{{ url('/cadastro') }}">Novo funcionário</a>
    <a class="header-logout" href="{{ url('/sair') }}">Sair ↗</a>
@endsection

@section('content')
<section class="page-container workspace-container">
    <div class="page-heading">
        <div><div class="eyebrow"><span class="eyebrow-dot"></span> ÁREA ADMINISTRATIVA</div><h1>Novo <span>funcionário.</span></h1><p>Crie o acesso individual para a pessoa acompanhar e registrar a própria jornada.</p></div>
        <a class="button button-outline" href="{{ url('/admin') }}">← Voltar ao acompanhamento</a>
    </div>

    <div class="form-card registration-card">
        <div class="section-title-row"><div><div class="eyebrow">DADOS DO ACESSO</div><h2>Informações do funcionário</h2></div><span class="required-note">* Campos obrigatórios</span></div>
        @if ($errors->any())
            <div class="form-alert" role="alert">Confira os campos destacados e tente novamente.</div>
        @endif

        <form method="post" action="{{ url('/cadastro') }}" class="registration-form">
            @csrf
            <div class="field-grid">
                <div class="field-section"><span aria-hidden="true">01</span><div><b>Identificação</b><small>Dados para localizar e identificar a pessoa na equipe.</small></div></div>
                <div class="field field-wide"><label for="nome">Nome completo <span>*</span></label><input id="nome" name="nome" type="text" value="{{ old('nome') }}" autocomplete="name" maxlength="100" placeholder="Ex.: Renan da Silva" required>@error('nome')<small class="field-error">{{ $message }}</small>@enderror</div>
                <div class="field"><label for="cpf">CPF <span>*</span></label><input id="cpf" name="cpf" type="text" inputmode="numeric" value="{{ old('cpf') }}" placeholder="000.000.000-00" maxlength="14" required>@error('cpf')<small class="field-error">{{ $message }}</small>@enderror</div>
                <div class="field"><label for="email">E-mail <span>*</span></label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="100" placeholder="nome@empresa.com" required>@error('email')<small class="field-error">{{ $message }}</small>@enderror</div>
                <div class="field"><label for="cargo">Cargo</label><input id="cargo" name="cargo" type="text" value="{{ old('cargo') }}" maxlength="100" placeholder="Ex.: Assistente"></div>
                <div class="field"><label for="local">Local de trabalho</label><input id="local" name="local" type="text" value="{{ old('local') }}" maxlength="100" placeholder="Ex.: Unidade principal"></div>
                <div class="field-section"><span aria-hidden="true">02</span><div><b>Acesso ao sistema</b><small>Crie uma senha inicial para o funcionário.</small></div></div>
                <div class="field"><label for="senha">Senha inicial <span>*</span></label><input id="senha" name="senha" type="password" autocomplete="new-password" minlength="8" placeholder="Pelo menos 8 caracteres" required>@error('senha')<small class="field-error">{{ $message }}</small>@enderror</div>
                <div class="field"><label for="senha_confirmation">Confirmar senha <span>*</span></label><input id="senha_confirmation" name="senha_confirmation" type="password" autocomplete="new-password" minlength="8" placeholder="Digite a senha novamente" required></div>
            </div>
            <div class="form-actions"><p>O funcionário entrará no sistema com o CPF e a senha definidos aqui.</p><button class="button button-primary" type="submit">Cadastrar funcionário <span aria-hidden="true">↗</span></button></div>
        </form>
    </div>
</section>
@endsection
