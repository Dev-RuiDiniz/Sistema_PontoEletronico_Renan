@extends('pontoeletronico.layout')

@section('title', 'Minha jornada')
@section('body-class', 'workspace-page')
@section('nav')
    <a class="nav-current" href="{{ url('/dashboard') }}">Minha jornada</a>
    <a href="#historico">Histórico mensal</a>
    <span class="nav-user">{{ $usuario->nome }}</span>
    <a class="header-logout" href="{{ url('/sair') }}">Sair ↗</a>
@endsection

@section('content')
<section class="page-container workspace-container">
    <div class="dashboard-welcome">
        <div><div class="eyebrow"><span class="eyebrow-dot"></span> ÁREA DO FUNCIONÁRIO</div><h1>Olá, {{ $usuario->nome }}.</h1><p>Um passo de cada vez. Sua jornada fica registrada aqui.</p></div>
        <div class="today-chip"><span class="today-icon">◷</span><span><b>HOJE</b><small>{{ now()->format('d/m/Y') }}</small></span></div>
    </div>

    @if ($errors->any())
        <div class="form-alert workspace-alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="dashboard-grid">
        <section class="panel journey-panel">
            <div class="panel-heading"><div><div class="eyebrow">REGISTROS DE HOJE</div><h2>Sua jornada</h2></div>
                @if ($proximaEtapa)
                    <span class="status-pill status-progress"><i></i> Em andamento</span>
                @elseif ($pontoHoje && $pontoHoje->saida)
                    <span class="status-pill status-complete"><i></i> Concluída</span>
                @else
                    <span class="status-pill status-waiting"><i></i> Ainda não iniciada</span>
                @endif
            </div>

            <div class="marking-list">
                @foreach ([['campo' => 'entrada', 'nome' => 'Entrada', 'descricao' => 'Início do expediente'], ['campo' => 'inicio_intervalo', 'nome' => 'Intervalo', 'descricao' => 'Início da pausa'], ['campo' => 'retorno_intervalo', 'nome' => 'Retorno', 'descricao' => 'Volta do intervalo'], ['campo' => 'saida', 'nome' => 'Saída', 'descricao' => 'Fim do expediente']] as $indice => $etapa)
                    <?php $horarioEtapa = $pontoHoje ? $pontoHoje->{$etapa['campo']} : null; ?>
                    <div class="marking-row {{ $horarioEtapa ? 'marking-done' : (($proximaEtapa && $proximaEtapa['campo'] === $etapa['campo']) ? 'marking-current' : '') }}">
                        <span class="marking-index">{{ $horarioEtapa ? '✓' : '0' . ($indice + 1) }}</span>
                        <span class="marking-name"><b>{{ $etapa['nome'] }}</b><small>{{ $etapa['descricao'] }}</small></span>
                        <span class="marking-time">{{ $horarioEtapa ? substr($horarioEtapa, 0, 5) : '— — : — —' }}</span>
                        @if ($proximaEtapa && $proximaEtapa['campo'] === $etapa['campo'])
                            <form method="post" action="{{ url('/registrar') }}" class="marking-action">
                                @csrf
                                <input type="hidden" name="area" value="{{ $etapa['campo'] }}">
                                <button type="submit" class="button button-primary button-mark">Registrar</button>
                            </form>
                        @else
                            <span class="marking-state">{{ $horarioEtapa ? 'Registrado' : 'Aguardando' }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="panel-footnote"><span>i</span> A próxima marcação aparece disponível depois que a anterior for registrada.</div>
        </section>

        <aside class="dashboard-side">
            <section class="panel today-summary">
                <div class="eyebrow">RESUMO DO DIA</div>
                <h2>{{ $pontoHoje && $pontoHoje->entrada ? 'Jornada iniciada' : 'Pronto para começar?' }}</h2>
                <p>{{ $pontoHoje && $pontoHoje->entrada ? 'Seus horários ficam salvos automaticamente após cada registro.' : 'Registre sua entrada para começar a jornada de hoje.' }}</p>
                <div class="summary-line"><span>Primeira marcação</span><b>{{ $pontoHoje && $pontoHoje->entrada ? substr($pontoHoje->entrada, 0, 5) : '— — : — —' }}</b></div>
                <div class="summary-line"><span>Última marcação</span><b>{{ $pontoHoje && $pontoHoje->saida ? substr($pontoHoje->saida, 0, 5) : '— — : — —' }}</b></div>
            </section>
            <section class="panel month-summary">
                <div class="eyebrow">SEU MÊS</div>
                <div class="month-count">{{ count($historico) }}<span> {{ count($historico) === 1 ? 'dia' : 'dias' }} com registro</span></div>
                <p>Consulte as marcações registradas neste mês.</p>
                <a class="text-link" href="#historico">Ver histórico <span aria-hidden="true">↓</span></a>
            </section>
        </aside>
    </div>

    <section class="panel history-panel" id="historico">
        <div class="panel-heading history-heading"><div><div class="eyebrow">ACOMPANHAMENTO PESSOAL</div><h2>Histórico mensal</h2></div>
            <form method="get" action="{{ url('/dashboard') }}" class="month-filter"><label for="mes">Mês</label><input id="mes" type="month" name="mes" value="{{ $mesSelecionado }}"><button class="button button-dark button-small" type="submit">Consultar</button></form>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Data</th><th>Entrada</th><th>Intervalo</th><th>Retorno</th><th>Saída</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($historico as $registro)
                        <tr>
                            <td><b>{{ \Carbon\Carbon::parse($registro->data)->format('d/m/Y') }}</b></td>
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
    </section>
</section>
@endsection
