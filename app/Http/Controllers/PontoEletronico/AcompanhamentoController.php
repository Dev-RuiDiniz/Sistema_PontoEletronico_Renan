<?php namespace App\Http\Controllers\PontoEletronico;

use App\Http\Controllers\Controller;
use App\ConfiguracaoSistema;
use App\Ponto;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AcompanhamentoController extends Controller
{
    public function admin()
    {
        $mesSelecionado = $this->mesSelecionado(request('mes', Carbon::now()->format('Y-m')));

        $inicio = $mesSelecionado . '-01';
        $fim = Carbon::parse($inicio)->endOfMonth()->toDateString();
        $funcionarioSelecionado = request('funcionario');
        $todosFuncionarios = Usuario::where('admin', 0)->orderBy('nome')->get();
        $funcionarios = Usuario::where('admin', 0)
            ->orderBy('nome')
            ->paginate(10)
            ->appends(request()->except('page'));

        $consulta = Ponto::with('usuario')->whereBetween('data', [$inicio, $fim]);
        if (is_string($funcionarioSelecionado) && $funcionarioSelecionado !== '' && ctype_digit($funcionarioSelecionado)) {
            $consulta->where('usuario_id', (int) $funcionarioSelecionado);
        } else {
            $funcionarioSelecionado = '';
        }
        $registros = $consulta->orderBy('data', 'desc')->orderBy('entrada', 'desc')->get();
        $jornadasIncompletas = $registros->filter(function ($registro) {
            return !empty($registro->entrada) && empty($registro->saida);
        })->count();
        $funcionariosAtivos = Usuario::where('admin', 0)->where('ativo', 1)->count();
        $configuracao = ConfiguracaoSistema::find(1);

        return view('pontoeletronico.admin-geral', compact(
            'mesSelecionado', 'funcionarioSelecionado', 'funcionarios', 'todosFuncionarios', 'registros',
            'jornadasIncompletas', 'funcionariosAtivos', 'configuracao'
        ));
    }

    public function pontosFuncionario($id)
    {
        $funcionario = Usuario::where('admin', 0)->findOrFail($id);
        $mesSelecionado = $this->mesSelecionado(request('mes', Carbon::now()->format('Y-m')));
        $inicio = $mesSelecionado . '-01';
        $fim = Carbon::parse($inicio)->endOfMonth()->toDateString();
        $registros = Ponto::where('usuario_id', $funcionario->id)
            ->whereBetween('data', [$inicio, $fim])
            ->orderBy('data')
            ->get();

        return view('pontoeletronico.admin-funcionario-pontos', compact(
            'funcionario', 'mesSelecionado', 'registros'
        ));
    }

    public function espelho()
    {
        $mesSelecionado = $this->mesSelecionado(request('mes', Carbon::now()->format('Y-m')));
        list($funcionarios, $registrosPorFuncionario) = $this->carregarEspelho($mesSelecionado);
        $mesRotulo = Carbon::parse($mesSelecionado . '-01')->format('m/Y');
        $emitidoEm = Carbon::now()->format('d/m/Y H:i');

        return view('pontoeletronico.espelho-mensal', compact(
            'mesSelecionado', 'mesRotulo', 'emitidoEm', 'funcionarios', 'registrosPorFuncionario'
        ));
    }

    public function exportarEspelho()
    {
        $mesSelecionado = $this->mesSelecionado(request('mes', Carbon::now()->format('Y-m')));
        list($funcionarios, $registrosPorFuncionario) = $this->carregarEspelho($mesSelecionado);
        $nomeArquivo = 'espelho-ponto-equipe-' . $mesSelecionado . '.csv';

        return response()->stream(function () use ($funcionarios, $registrosPorFuncionario) {
            $arquivo = fopen('php://output', 'w');
            fwrite($arquivo, "\xEF\xBB\xBF");
            fputcsv($arquivo, ['Funcionário', 'Cargo', 'Data', 'Entrada', 'Início do intervalo', 'Retorno do intervalo', 'Saída', 'Situação'], ';');

            foreach ($funcionarios as $funcionario) {
                $registros = $registrosPorFuncionario->get($funcionario->id, collect());
                if ($registros->isEmpty()) {
                    fputcsv($arquivo, [
                        $this->valorCsv($funcionario->nome),
                        $this->valorCsv($funcionario->cargo ?: 'Funcionário'),
                        '', '', '', '', '', 'Sem registro no mês',
                    ], ';');
                    continue;
                }

                foreach ($registros as $registro) {
                    $situacao = $registro->saida
                        ? 'Concluída'
                        : ($registro->entrada ? 'Em andamento' : 'Incompleta');
                    fputcsv($arquivo, [
                        $this->valorCsv($funcionario->nome),
                        $this->valorCsv($funcionario->cargo ?: 'Funcionário'),
                        Carbon::parse($registro->data)->format('d/m/Y'),
                        $registro->entrada ? substr($registro->entrada, 0, 5) : '',
                        $registro->inicio_intervalo ? substr($registro->inicio_intervalo, 0, 5) : '',
                        $registro->retorno_intervalo ? substr($registro->retorno_intervalo, 0, 5) : '',
                        $registro->saida ? substr($registro->saida, 0, 5) : '',
                        $situacao,
                    ], ';');
                }
            }

            fclose($arquivo);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nomeArquivo . '"',
        ]);
    }

    public function salvarRestricaoIp(Request $request)
    {
        $dados = $request->validate([
            'restricao_ativa' => 'required|in:0,1',
            'ip_autorizado' => 'nullable|ip',
        ], [
            'restricao_ativa.required' => 'Escolha se a restrição por IP ficará ativa.',
            'restricao_ativa.in' => 'A opção de restrição por IP é inválida.',
            'ip_autorizado.ip' => 'Informe um endereço IPv4 ou IPv6 válido.',
        ]);

        $ip = trim((string) ($dados['ip_autorizado'] ?? ''));
        if ($dados['restricao_ativa'] === '1' && $ip === '') {
            return back()->withErrors(['ip_autorizado' => 'Informe o IP público da empresa antes de ativar a restrição.']);
        }
        if ($ip !== '' && !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return back()->withErrors(['ip_autorizado' => 'Informe um endereço IP público válido, não o IP local do roteador.']);
        }

        $configuracao = ConfiguracaoSistema::find(1);
        if (!$configuracao) {
            $configuracao = new ConfiguracaoSistema();
            $configuracao->id = 1;
        }
        $configuracao->restricao_ip_ativa = $dados['restricao_ativa'] === '1';
        $configuracao->ip_autorizado = $ip !== '' ? $ip : null;
        $configuracao->save();

        return redirect('/admin')->with('sucesso', 'Configuração de acesso por IP atualizada.');
    }

    private function mesValido($mes)
    {
        if (!is_string($mes) || !preg_match('/^(\d{4})-(\d{2})$/', $mes, $partes)) {
            return false;
        }

        return checkdate((int) $partes[2], 1, (int) $partes[1]);
    }

    private function mesSelecionado($mes)
    {
        return $this->mesValido($mes) ? $mes : Carbon::now()->format('Y-m');
    }

    private function carregarEspelho($mes)
    {
        $inicio = $mes . '-01';
        $fim = Carbon::parse($inicio)->endOfMonth()->toDateString();
        $funcionarios = Usuario::where('admin', 0)->orderBy('nome')->get();
        $registrosPorFuncionario = Ponto::whereIn('usuario_id', $funcionarios->pluck('id')->all())
            ->whereBetween('data', [$inicio, $fim])
            ->orderBy('data')
            ->get()
            ->groupBy('usuario_id');

        return [$funcionarios, $registrosPorFuncionario];
    }

    private function valorCsv($valor)
    {
        $valor = (string) $valor;
        return preg_match('/^[\s\x00-\x1F]*[=+\-@]/', $valor) ? "'" . $valor : $valor;
    }
}
