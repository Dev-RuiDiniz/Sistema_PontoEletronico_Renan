<?php namespace App\Http\Controllers\PontoEletronico;

use App\Http\Controllers\Controller;
use App\ConfiguracaoSistema;
use App\Ponto;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PontoController extends Controller
{
    private $etapas = [
        'entrada' => 'Entrada',
        'inicio_intervalo' => 'Início do intervalo',
        'retorno_intervalo' => 'Retorno do intervalo',
        'saida' => 'Saída',
    ];

    public function registrar_validando(Request $request)
    {
        $dados = $request->validate([
            'area' => 'required|in:entrada,inicio_intervalo,retorno_intervalo,saida',
        ], [
            'area.required' => 'Escolha uma marcação.',
            'area.in' => 'A marcação selecionada não é válida.',
        ]);

        $usuarioId = Session::get('login.ponto.usuario_id');
        $hoje = Carbon::now()->toDateString();
        $area = $dados['area'];
        $configuracao = ConfiguracaoSistema::find(1);

        if ($configuracao && $configuracao->restricao_ip_ativa) {
            $ipAutorizado = (string) $configuracao->ip_autorizado;
            if ($ipAutorizado === '' || !hash_equals($ipAutorizado, (string) $request->ip())) {
                return redirect('/dashboard')->withErrors([
                    'ponto' => 'O registro de ponto só está disponível na rede autorizada da empresa.',
                ]);
            }
        }

        $resultado = DB::transaction(function () use ($usuarioId, $hoje, $area) {
            $usuario = Usuario::where('id', $usuarioId)->lockForUpdate()->first();
            if (!$usuario || $usuario->ativo != 1 || $usuario->admin == 1) {
                return 'Esta conta não pode registrar ponto.';
            }

            $ponto = Ponto::where('usuario_id', $usuarioId)
                ->where('data', $hoje)
                ->lockForUpdate()
                ->first();

            if (!$ponto) {
                if ($area !== 'entrada') {
                    return 'A primeira marcação do dia precisa ser a entrada.';
                }

                $ponto = new Ponto();
                $ponto->usuario_id = $usuarioId;
                $ponto->data = $hoje;
            }

            $proximaEtapa = null;
            if (empty($ponto->entrada)) {
                $proximaEtapa = 'entrada';
            } elseif (empty($ponto->saida)) {
                foreach (array_keys($this->etapas) as $etapa) {
                    if (empty($ponto->{$etapa})) {
                        $proximaEtapa = $etapa;
                        break;
                    }
                }
            }

            if (!$proximaEtapa) {
                return 'As quatro marcações de hoje já foram concluídas.';
            }

            if ($area !== $proximaEtapa) {
                return 'A próxima marcação é: ' . $this->etapas[$proximaEtapa] . '.';
            }

            $ponto->{$area} = Carbon::now()->format('H:i:s');
            if ($area === 'entrada') {
                $ponto->entrada_status = 0;
                $ponto->status = 0;
            } elseif ($area === 'saida') {
                $ponto->saida_status = 0;
            }
            $ponto->save();

            return null;
        });

        if ($resultado) {
            return redirect('/dashboard')->withErrors(['ponto' => $resultado]);
        }

        $mensagensSucesso = [
            'entrada' => 'Entrada registrada.',
            'inicio_intervalo' => 'Intervalo iniciado.',
            'retorno_intervalo' => 'Retorno do intervalo registrado.',
            'saida' => 'Saída registrada.',
        ];

        return redirect('/dashboard')->with('sucesso', $mensagensSucesso[$area]);
    }
}
