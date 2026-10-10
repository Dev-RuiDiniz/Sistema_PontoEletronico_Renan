@extends('pontoeletronico.layout')

@section('title', 'Espelho mensal da equipe')
@section('body-class', 'workspace-page admin-page mirror-page')
@section('nav')
    <a href="{{ url('/admin') }}">Acompanhamento</a>
    <a class="nav-current" href="{{ route('admin.espelho', ['mes' => $mesSelecionado]) }}">Espelho mensal</a>
    <span class="nav-user">{{ session('login.ponto.usuario_nome') }}</span>
    <a class="header-logout" href="{{ url('/sair') }}">Sair ↗</a>
@endsection

@section('content')
<section class="page-container workspace-container mirror-container">
    <div class="page-heading mirror-screen-heading">
        <div>
            <div class="eyebrow"><span class="eyebrow-dot"></span> CONFERÊNCIA DA EQUIPE</div>
            <h1>Espelho de ponto <span>{{ $mesRotulo }}</span></h1>
            <p>Confira as marcações de cada funcionário e imprima o documento para assinatura.</p>
        </div>
        <a class="button button-outline" href="{{ url('/admin') }}">← Voltar ao acompanhamento</a>
    </div>

    <div class="panel mirror-controls">
        <form method="get" action="{{ route('admin.espelho') }}" class="admin-filters">
            <label for="mes-espelho">Mês do espelho</label>
            <input id="mes-espelho" type="month" name="mes" value="{{ $mesSelecionado }}">
            <button class="button button-dark button-small" type="submit">Ver espelho</button>
        </form>
        <div class="mirror-export-actions">
            <a class="button button-outline button-small" href="{{ route('admin.espelho.exportar', ['mes' => $mesSelecionado]) }}">Exportar CSV</a>
            <button class="button button-primary button-small" type="button" onclick="window.print()">Imprimir / salvar PDF</button>
        </div>
    </div>

    <p class="mirror-note"><b>Atenção:</b> o documento mostra as marcações salvas. Datas sem registro não significam automaticamente falta ou folga; a tela não calcula horas trabalhadas.</p>

    <article class="mirror-document">
        <header class="mirror-document-heading">
            <div class="mirror-company">PONTO CONCEITO <span>· Café Conceito</span></div>
            <h2>Espelho mensal de ponto da equipe</h2>
            <p>Competência: <strong>{{ $mesRotulo }}</strong> <span>·</span> Emitido em {{ $emitidoEm }}</p>
        </header>

        @forelse ($funcionarios as $funcionario)
            @php($pontosFuncionario = $registrosPorFuncionario->get($funcionario->id, collect()))
            <section class="mirror-employee">
                <div class="mirror-employee-heading">
                    <div><h3>{{ $funcionario->nome }}</h3><p>{{ $funcionario->cargo ?: 'Funcionário' }}{{ $funcionario->local ? ' · ' . $funcionario->local : '' }}</p></div>
                    <span class="staff-status {{ $funcionario->ativo == 1 ? 'staff-active' : 'staff-inactive' }}">{{ $funcionario->ativo == 1 ? 'Ativo' : 'Inativo' }}</span>
                </div>
                <div class="table-wrap mirror-table-wrap">
                    <table class="data-table mirror-table">
                        <thead><tr><th>Data</th><th>Entrada</th><th>Início do intervalo</th><th>Retorno</th><th>Saída</th><th>Situação</th></tr></thead>
                        <tbody>
                            @forelse ($pontosFuncionario as $registro)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($registro->data)->format('d/m/Y') }}</td>
                                    <td>{{ $registro->entrada ? substr($registro->entrada, 0, 5) : '—' }}</td>
                                    <td>{{ $registro->inicio_intervalo ? substr($registro->inicio_intervalo, 0, 5) : '—' }}</td>
                                    <td>{{ $registro->retorno_intervalo ? substr($registro->retorno_intervalo, 0, 5) : '—' }}</td>
                                    <td>{{ $registro->saida ? substr($registro->saida, 0, 5) : '—' }}</td>
                                    <td>@if ($registro->saida)<span class="table-status table-status-done">Concluída</span>@elseif ($registro->entrada)<span class="table-status table-status-progress">Em andamento</span>@else<span class="table-status">Incompleta</span>@endif</td>
                                </tr>
                            @empty
                                <tr><td class="empty-state" colspan="6">Sem registro de ponto neste mês.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mirror-confirmation">Declaro que conferi os registros apresentados para esta competência.</div>
                <div class="mirror-signature"><span></span><b>{{ $funcionario->nome }}</b><small>Assinatura do(a) funcionário(a) e data</small></div>
            </section>
        @empty
            <p class="empty-state">Nenhum funcionário cadastrado.</p>
        @endforelse

        <div class="mirror-manager-signature"><span></span><b>Responsável pela conferência</b><small>Assinatura e data</small></div>
        <footer class="mirror-document-footer">Documento gerado pelo Ponto Conceito · Horários em Brasília (America/Sao_Paulo).</footer>
    </article>
</section>
@endsection
