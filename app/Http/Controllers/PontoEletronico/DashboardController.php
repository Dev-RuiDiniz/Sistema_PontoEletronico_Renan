<?php namespace App\Http\Controllers\PontoEletronico;

use App\Http\Controllers\Controller;
use App\Ponto;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $usuarioId = Session::get('login.ponto.usuario_id');
        $usuario = Usuario::findOrFail($usuarioId);
        $hoje = Carbon::now()->toDateString();
        $pontoHoje = Ponto::where('usuario_id', $usuarioId)->where('data', $hoje)->first();

        $etapas = [
            ['campo' => 'entrada', 'nome' => 'Entrada'],
            ['campo' => 'inicio_intervalo', 'nome' => 'Início do intervalo'],
            ['campo' => 'retorno_intervalo', 'nome' => 'Retorno do intervalo'],
            ['campo' => 'saida', 'nome' => 'Saída'],
        ];
        $proximaEtapa = null;

        if (!$pontoHoje) {
            $proximaEtapa = $etapas[0];
        } elseif (empty($pontoHoje->saida)) {
            foreach ($etapas as $etapa) {
                if (empty($pontoHoje->{$etapa['campo']})) {
                    $proximaEtapa = $etapa;
                    break;
                }
            }
        }

        $mesSelecionado = request('mes', Carbon::now()->format('Y-m'));
        if (!is_string($mesSelecionado) || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mesSelecionado)) {
            $mesSelecionado = Carbon::now()->format('Y-m');
        }
        $inicio = $mesSelecionado . '-01';
        $fim = Carbon::parse($inicio)->endOfMonth()->toDateString();
        $historico = Ponto::where('usuario_id', $usuarioId)
            ->whereBetween('data', [$inicio, $fim])
            ->orderBy('data', 'desc')
            ->get();

        return view('pontoeletronico.dashboard-funcionario', compact(
            'usuario', 'pontoHoje', 'proximaEtapa', 'historico', 'mesSelecionado'
        ));
    }
}
