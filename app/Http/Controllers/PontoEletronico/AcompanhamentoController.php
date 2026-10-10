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
        $mesSelecionado = request('mes', Carbon::now()->format('Y-m'));
        if (!$this->mesValido($mesSelecionado)) {
            $mesSelecionado = Carbon::now()->format('Y-m');
        }

        $inicio = $mesSelecionado . '-01';
        $fim = Carbon::parse($inicio)->endOfMonth()->toDateString();
        $funcionarioSelecionado = request('funcionario');
        $funcionarios = Usuario::where('admin', 0)->orderBy('nome')->get();

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
        $funcionariosAtivos = $funcionarios->filter(function ($funcionario) {
            return $funcionario->ativo == 1;
        })->count();
        $configuracao = ConfiguracaoSistema::find(1);

        return view('pontoeletronico.admin-geral', compact(
            'mesSelecionado', 'funcionarioSelecionado', 'funcionarios', 'registros',
            'jornadasIncompletas', 'funcionariosAtivos', 'configuracao'
        ));
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
}
