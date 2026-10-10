<?php namespace App\Http\Controllers\PontoEletronico;

use App\Http\Controllers\Controller;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function novo()
    {
        return view('pontoeletronico.cadastro');
    }

    public function salvarNovo(Request $request)
    {
        $cpfInformado = $request->input('cpf');
        $request->merge(['cpf' => is_string($cpfInformado) ? preg_replace('/\D+/', '', $cpfInformado) : '']);

        $dados = $request->validate([
            'nome' => 'required|string|max:100',
            'cpf' => 'required|digits:11|unique:usuario,cpf',
            'email' => 'required|email|max:100|unique:usuario,email',
            'cargo' => 'nullable|string|max:100',
            'local' => 'nullable|string|max:100',
            'senha' => 'required|string|min:8|confirmed',
        ], [
            'nome.required' => 'Informe o nome completo.',
            'nome.string' => 'O nome informado é inválido.',
            'nome.max' => 'O nome deve ter até 100 caracteres.',
            'cpf.required' => 'Informe o CPF.',
            'cpf.digits' => 'Informe um CPF com 11 números.',
            'cpf.unique' => 'Já existe um cadastro com este CPF.',
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.max' => 'O e-mail deve ter até 100 caracteres.',
            'email.unique' => 'Já existe um cadastro com este e-mail.',
            'cargo.string' => 'O cargo informado é inválido.',
            'cargo.max' => 'O cargo deve ter até 100 caracteres.',
            'local.string' => 'O local informado é inválido.',
            'local.max' => 'O local deve ter até 100 caracteres.',
            'senha.required' => 'Defina uma senha inicial.',
            'senha.string' => 'A senha informada é inválida.',
            'senha.confirmed' => 'A confirmação da senha não confere.',
            'senha.min' => 'A senha deve ter pelo menos 8 caracteres.',
        ]);

        $usuario = new Usuario();
        $usuario->nome = $dados['nome'];
        $usuario->cpf = $dados['cpf'];
        $usuario->email = $dados['email'];
        $usuario->cargo = $dados['cargo'] ?? null;
        $usuario->local = $dados['local'] ?? null;
        $usuario->senha = Hash::make($dados['senha']);
        $usuario->admin = 0;
        $usuario->ativo = 1;
        $usuario->save();

        return redirect('/admin')->with('sucesso', 'Funcionário cadastrado.');
    }
}
