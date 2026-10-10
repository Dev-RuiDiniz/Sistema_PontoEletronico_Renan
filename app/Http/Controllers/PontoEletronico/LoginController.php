<?php namespace App\Http\Controllers\PontoEletronico;

use App\Http\Controllers\Controller;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function show()
    {
        if (Session::has('login.ponto.usuario_id')) {
            return Session::get('login.ponto.admin') == 1
                ? redirect('/admin')
                : redirect('/dashboard');
        }

        return view('pontoeletronico.login');
    }

    public function login(Request $request)
    {
        $dados = $request->validate([
            'cpf' => 'required|string|max:20',
            'senha' => 'required|string',
        ], [
            'cpf.required' => 'Informe seu CPF.',
            'cpf.string' => 'Informe um CPF válido.',
            'cpf.max' => 'O CPF informado é inválido.',
            'senha.required' => 'Informe sua senha.',
            'senha.string' => 'A senha informada é inválida.',
        ]);

        $cpf = preg_replace('/\D+/', '', $dados['cpf']);
        $usuario = Usuario::where('cpf', $cpf)->where('ativo', 1)->first();

        if (!$usuario || !$this->senhaValida($usuario, $dados['senha'])) {
            return back()->withErrors(['login' => 'CPF ou senha inválidos.'])->withInput($request->only('cpf'));
        }

        $request->session()->regenerate();
        Session::put('login.ponto.usuario_id', $usuario->id);
        Session::put('login.ponto.admin', (int) $usuario->admin);
        Session::put('login.ponto.usuario_nome', $usuario->nome);

        return $usuario->admin == 1 ? redirect('/admin') : redirect('/dashboard');
    }

    private function senhaValida(Usuario $usuario, $senha)
    {
        $armazenada = (string) $usuario->senha;

        if (preg_match('/^\$2[aby]\$/', $armazenada)) {
            return Hash::check($senha, $armazenada);
        }

        // Migra automaticamente a senha SHA-1 do protótipo após um login válido.
        if (preg_match('/^[a-f0-9]{40}$/i', $armazenada) && hash_equals($armazenada, sha1($senha))) {
            $usuario->senha = Hash::make($senha);
            $usuario->save();
            return true;
        }

        return false;
    }

    public function sair(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
