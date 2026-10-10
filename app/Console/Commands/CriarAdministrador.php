<?php

namespace App\Console\Commands;

use App\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CriarAdministrador extends Command
{
    protected $signature = 'ponto:administrador';
    protected $description = 'Cria a primeira conta administrativa do sistema';

    public function handle()
    {
        $nome = trim((string) $this->ask('Nome do administrador'));
        $cpf = preg_replace('/\D+/', '', (string) $this->ask('CPF do administrador'));
        $email = trim((string) $this->ask('E-mail do administrador'));

        if ($nome === '' || strlen($cpf) !== 11 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Informe um nome, CPF com 11 números e e-mail válido.');
            return 1;
        }

        if (Usuario::where('cpf', $cpf)->orWhere('email', $email)->exists()) {
            $this->error('Já existe uma conta com este CPF ou e-mail.');
            return 1;
        }

        $senha = (string) $this->secret('Senha (mínimo de 8 caracteres)');
        $confirmacao = (string) $this->secret('Confirme a senha');
        if (strlen($senha) < 8 || !hash_equals($senha, $confirmacao)) {
            $this->error('A senha deve ter pelo menos 8 caracteres e as confirmações devem coincidir.');
            return 1;
        }

        $administrador = new Usuario();
        $administrador->nome = $nome;
        $administrador->cpf = $cpf;
        $administrador->email = $email;
        $administrador->senha = Hash::make($senha);
        $administrador->admin = 1;
        $administrador->ativo = 1;
        $administrador->save();

        $this->info('Conta administrativa criada. Acesse /login com o CPF e a senha informados.');
        return 0;
    }
}
