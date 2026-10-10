# Ponto Conceito

Aplicação web do Café Conceito para registro simples da jornada de trabalho. O produto se chama **Ponto Conceito** e tem cinco endereços principais: página inicial, login, cadastro de funcionários, painel do funcionário e painel administrativo.

## O que já está implementado

- `/`: apresentação do sistema e acesso ao login.
- `/login`: entrada única com CPF e senha. O perfil da conta leva o usuário ao painel correspondente.
- `/cadastro`: cadastro de funcionários, disponível somente para administradores.
- `/dashboard`: registro de entrada, início do intervalo, retorno e saída, sempre na ordem, mais histórico mensal pessoal.
- `/admin`: acompanhamento mensal, lista de funcionários com 10 pessoas por página, acesso ao histórico individual, configuração opcional do IP público e espelho mensal consolidado.
- `/admin/espelho`: conferência do mês para toda a equipe, impressão/salvamento em PDF com campos de assinatura e exportação CSV.
- Horários definidos pelo servidor no fuso `America/Sao_Paulo`.
- Senhas novas armazenadas com hash bcrypt; senhas SHA-1 antigas são atualizadas para bcrypt após um login válido.

O fluxo de ponto cria uma jornada por funcionário e data. Jornadas que atravessam a meia-noite e correções de marcação ainda não estão definidas. A restrição por IP começa desligada; o administrador precisa informar o endereço público fixo da empresa para ativá-la. Se o servidor estiver atrás de proxy, configure os proxies confiáveis antes de usar essa restrição.

## Executar localmente com Docker

Pré-requisitos: Docker Desktop em execução e Docker Compose v2.

Na primeira inicialização:

```powershell
Copy-Item .env.example .env
docker compose up -d --build
docker compose exec web php artisan key:generate
docker compose exec web php artisan migrate --force
```

Crie a primeira conta administrativa pelo comando interativo. Ele solicita nome, CPF, e-mail e senha sem gravar a senha em comandos ou no repositório:

```powershell
docker compose exec web php artisan ponto:administrador
```

Abra [http://127.0.0.1:8001](http://127.0.0.1:8001) e entre em `/login` com a conta criada. No painel administrativo, cadastre um funcionário para testar o outro perfil. O Compose mantém o banco em volume local; para parar os serviços, execute `docker compose down`.

Para o desenvolvimento sem Docker, instale as dependências com `composer install`, copie `.env.example` para `.env`, configure um MySQL local descartável, execute `php artisan key:generate`, `php artisan migrate` e `php artisan ponto:administrador`, e inicie com `php artisan serve`.

## Como funciona o registro

1. O funcionário entra com CPF e senha.
2. O painel mostra a próxima marcação disponível.
3. O sistema valida a ordem e registra a hora do servidor: entrada → intervalo → retorno → saída.
4. O histórico mensal mostra somente os registros da própria conta.
5. O administrador consulta os registros da equipe, abre o histórico de cada funcionário, emite o espelho mensal para conferência e assinatura, cadastra funcionários e, se necessário, restringe novas marcações ao IP público fixo cadastrado.

Quando a restrição por IP está ligada, uma marcação de fora do endereço autorizado é recusada. Login e consulta do histórico continuam acessíveis. O IP identifica a conexão pública da empresa, não a identidade física da pessoa.

## Documentação

- [DESIGN.md](DESIGN.md): identidade visual, moodboard e regras de interface do Ponto Conceito.
- [PRD.md](PRD.md): regras, escopo, segurança e pendências do produto.
- [AGENTS.md](AGENTS.md): instruções de execução e contribuição.
- [ROADMAP.md](ROADMAP.md): concluído e próximos passos.
- [FLUXOGRAMA.md](FLUXOGRAMA.md): navegação e registro da jornada.
- [ACESSOS.md](ACESSOS.md): inventário de ambientes e credenciais sem segredos.

## Limites desta versão

- Não há recuperação de senha nem fluxo de correção de ponto.
- Não há jornada noturna que atravesse a meia-noite.
- O endereço numérico do IP fixo precisa ser fornecido pela empresa antes de ligar a restrição.
- Em hospedagem com proxy reverso, é necessário configurar os endereços de proxy confiáveis para que o servidor identifique corretamente o IP de origem sem confiar em cabeçalhos enviados pelo visitante.
- Use dados fictícios em desenvolvimento. Não conecte o banco local a dados de produção.
