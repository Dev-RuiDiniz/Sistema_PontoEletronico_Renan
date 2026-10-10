@extends('pontoeletronico.layout')

@section('title', 'Acompanhamento da equipe')
@section('body-class', 'workspace-page')
@section('nav')
    <a class="nav-current" href="{{ url('/admin') }}">Acompanhamento</a>
    <a href="#funcionarios">Funcionários</a>
    <a class="button button-small button-primary" href="{{ url('/cadastro') }}">+ Cadastrar</a>
    <span class="nav-user">{{ session('login.ponto.usuario_nome') }}</span>
    <a class="header-logout" href="{{ url('/sair') }}">Sair ↗</a>
@endsection

@section('content')
<section class="page-container workspace-container">
    <div class="page-heading">
        <div><div class="eyebrow"><span class="eyebrow-dot"></span> VISÃO ADMINISTRATIVA</div><h1>A equipe, <span>em um só lugar.</span></h1><p>Acompanhe as jornadas registradas e encontre os dados de cada funcionário.</p></div>
        <a class="button button-primary" href="{{ url('/cadastro') }}">+ Novo funcionário</a>
    </div>

    <div class="admin-stats">
        <article class="stat-card"><span class="stat-icon stat-icon-green">◎</span><div><small>FUNCIONÁRIOS ATIVOS</small><strong>{{ $funcionariosAtivos }}</strong><span>com acesso ao sistema</span></div></article>
        <article class="stat-card"><span class="stat-icon stat-icon-yellow">▤</span><div><small>REGISTROS NO PERÍODO</small><strong>{{ count($registros) }}</strong><span>jornadas com alguma marcação</span></div></article>
        <article class="stat-card"><span class="stat-icon stat-icon-blue">◷</span><div><small>JORNADAS EM ANDAMENTO</small><strong>{{ $jornadasIncompletas }}</strong><span>sem horário de saída registrado</span></div></article>
    </div>

    <section class="panel admin-records-panel">
        <div class="panel-heading admin-panel-heading"><div><div class="eyebrow">PONTO DA EQUIPE</div><h2>Acompanhamento completo</h2></div>
            <form method="get" action="{{ url('/admin') }}" class="admin-filters">
                <label for="mes">Mês</label><input id="mes" type="month" name="mes" value="{{ $mesSelecionado }}">
                <label for="funcionario">Funcionário</label><select id="funcionario" name="funcionario"><option value="">Todos</option>@foreach ($funcionarios as $funcionario)<option value="{{ $funcionario->id }}" {{ (string) $funcionarioSelecionado === (string) $funcionario->id ? 'selected' : '' }}>{{ $funcionario->nome }}</option>@endforeach</select>
                <button class="button button-dark button-small" type="submit">Filtrar</button>
            </form>
        </div>
        <div class="table-wrap">
            <table class="data-table admin-table">
                <thead><tr><th>Funcionário</th><th>Data</th><th>Entrada</th><th>Intervalo</th><th>Retorno</th><th>Saída</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($registros as $registro)
                        <tr>
                            <td><div class="employee-cell"><span class="employee-avatar">{{ $registro->usuario ? strtoupper(substr($registro->usuario->nome, 0, 1)) : '?' }}</span><span><b>{{ $registro->usuario ? $registro->usuario->nome : 'Funcionário não encontrado' }}</b><small>{{ $registro->usuario ? ($registro->usuario->cargo ?: 'Funcionário') : '' }}</small></span></div></td>
                            <td>{{ \Carbon\Carbon::parse($registro->data)->format('d/m/Y') }}</td>
                            <td>{{ $registro->entrada ? substr($registro->entrada, 0, 5) : '—' }}</td>
                            <td>{{ $registro->inicio_intervalo ? substr($registro->inicio_intervalo, 0, 5) : '—' }}</td>
                            <td>{{ $registro->retorno_intervalo ? substr($registro->retorno_intervalo, 0, 5) : '—' }}</td>
                            <td>{{ $registro->saida ? substr($registro->saida, 0, 5) : '—' }}</td>
                            <td>@if ($registro->saida)<span class="table-status table-status-done">Concluída</span>@elseif ($registro->entrada)<span class="table-status table-status-progress">Em andamento</span>@else<span class="table-status">Incompleta</span>@endif</td>
                        </tr>
                    @empty
                        <tr><td class="empty-state" colspan="7">Não há jornadas para os filtros selecionados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer"><span>Exibindo {{ count($registros) }} {{ count($registros) === 1 ? 'jornada' : 'jornadas' }} no período selecionado.</span><span>Horários em Brasília · America/Sao_Paulo</span></div>
    </section>

    <section class="panel ip-settings-panel">
        <div class="panel-heading"><div><div class="eyebrow">ACESSO À REDE</div><h2>Restrição por IP do Wi-Fi</h2></div>@if ($configuracao && $configuracao->restricao_ip_ativa)<span class="status-pill status-complete"><i></i> Ativa</span>@else<span class="status-pill status-waiting"><i></i> Desativada</span>@endif</div>
        <p class="ip-settings-copy">Quando ativada, somente funcionários conectados pela internet da empresa poderão registrar o ponto. Informe o IP público fixo da conexão.</p>
        @if ($errors->has('ip_autorizado') || $errors->has('restricao_ativa'))<div class="form-alert" role="alert">{{ $errors->first('ip_autorizado') ?: $errors->first('restricao_ativa') }}</div>@endif
        <form method="post" action="{{ url('/admin/restricao-ip') }}" class="ip-settings-form">
            @csrf
            <div class="field ip-field"><label for="ip_autorizado">IP público autorizado</label><input id="ip_autorizado" name="ip_autorizado" type="text" value="{{ old('ip_autorizado', $configuracao ? $configuracao->ip_autorizado : '') }}" placeholder="Digite o IP público fixo da empresa" maxlength="45"><small>Use o endereço público fornecido pela operadora, não o IP local do roteador.</small></div>
            <div class="ip-toggle-block"><input type="hidden" name="restricao_ativa" value="0"><label class="toggle-label"><input type="checkbox" name="restricao_ativa" value="1" {{ old('restricao_ativa', $configuracao && $configuracao->restricao_ip_ativa ? '1' : '0') === '1' ? 'checked' : '' }}><span class="toggle-ui"></span><span><b>Exigir o IP autorizado para bater ponto</b><small>Login e consulta do histórico continuam disponíveis fora da empresa.</small></span></label><button type="submit" class="button button-dark button-small">Salvar configuração</button></div>
        </form>
    </section>

    <section class="panel staff-panel" id="funcionarios">
        <div class="panel-heading"><div><div class="eyebrow">ACESSOS DA EQUIPE</div><h2>Funcionários cadastrados</h2></div><a class="text-link" href="{{ url('/cadastro') }}">Cadastrar funcionário <span aria-hidden="true">↗</span></a></div>
        <div class="staff-grid">
            @forelse ($funcionarios as $funcionario)
                <article class="staff-card"><span class="employee-avatar">{{ strtoupper(substr($funcionario->nome, 0, 1)) }}</span><div><b>{{ $funcionario->nome }}</b><small>{{ $funcionario->cargo ?: 'Funcionário' }}{{ $funcionario->local ? ' · ' . $funcionario->local : '' }}</small></div><span class="staff-status {{ $funcionario->ativo == 1 ? 'staff-active' : 'staff-inactive' }}">{{ $funcionario->ativo == 1 ? 'Ativo' : 'Inativo' }}</span></article>
            @empty
                <p class="empty-state">Nenhum funcionário cadastrado.</p>
            @endforelse
        </div>
    </section>
</section>
@endsection
