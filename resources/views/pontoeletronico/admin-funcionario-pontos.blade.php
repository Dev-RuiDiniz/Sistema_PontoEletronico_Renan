@extends('pontoeletronico.layout')

@section('title', 'Pontos do funcionário')
@section('body-class', 'workspace-page admin-page employee-records-page')
@section('nav')
    <a href="{{ url('/admin') }}">Acompanhamento</a>
    <a class="nav-current" href="{{ url('/admin#funcionarios') }}">Funcionários</a>
    <span class="nav-user">{{ session('login.ponto.usuario_nome') }}</span>
    <a class="header-logout" href="{{ url('/sair') }}">Sair ↗</a>
@endsection

@section('content')
<section class="page-container workspace-container">
    <div class="page-heading">
        <div>
            <div class="eyebrow"><span class="eyebrow-dot"></span> PONTOS DO FUNCIONÁRIO</div>
            <h1>{{ $funcionario->nome }}</h1>
            <p>{{ $funcionario->cargo ?: 'Funcionário' }}{{ $funcionario->local ? ' · ' . $funcionario->local : '' }}</p>
        </div>
        <a class="button button-outline" href="{{ url('/admin#funcionarios') }}">← Voltar à equipe</a>
    </div>

    <section class="panel admin-records-panel">
        <div class="panel-heading admin-panel-heading">
            <div><div class="eyebrow">HISTÓRICO INDIVIDUAL</div><h2>Jornada mensal</h2></div>
            <form method="get" action="{{ route('admin.funcionario.pontos', ['id' => $funcionario->id]) }}" class="admin-filters">
                <label for="mes-funcionario">Mês</label>
                <input id="mes-funcionario" type="month" name="mes" value="{{ $mesSelecionado }}">
                <button class="button button-dark button-small" type="submit">Consultar</button>
            </form>
        </div>
        <div class="table-wrap">
            <table class="data-table admin-table">
                <thead><tr><th>Data</th><th>Entrada</th><th>Intervalo</th><th>Retorno</th><th>Saída</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($registros as $registro)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($registro->data)->format('d/m/Y') }}</td>
                            <td>{{ $registro->entrada ? substr($registro->entrada, 0, 5) : '—' }}</td>
                            <td>{{ $registro->inicio_intervalo ? substr($registro->inicio_intervalo, 0, 5) : '—' }}</td>
                            <td>{{ $registro->retorno_intervalo ? substr($registro->retorno_intervalo, 0, 5) : '—' }}</td>
                            <td>{{ $registro->saida ? substr($registro->saida, 0, 5) : '—' }}</td>
                            <td>@if ($registro->saida)<span class="table-status table-status-done">Concluída</span>@elseif ($registro->entrada)<span class="table-status table-status-progress">Em andamento</span>@else<span class="table-status">Incompleta</span>@endif</td>
                        </tr>
                    @empty
                        <tr><td class="empty-state" colspan="6">Nenhum registro encontrado neste mês.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer"><span>{{ count($registros) }} {{ count($registros) === 1 ? 'jornada no mês' : 'jornadas no mês' }}</span><span>Horários em Brasília · America/Sao_Paulo</span></div>
    </section>
</section>
@endsection
