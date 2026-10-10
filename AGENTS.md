# Instruções para trabalhar neste projeto

## Fonte da verdade

- Leia `PRD.md` e `ROADMAP.md` antes de alterar regras ou fluxos.
- O produto tem cinco páginas principais: `/`, `/login`, `/cadastro`, `/dashboard` e `/admin`.
- Preserve o fluxo de quatro marcações, o histórico pessoal, o acompanhamento administrativo e a opção de IP descritos no PRD.
- Não reative justificativas, anexos, aprovações ou exportações legadas sem pedido explícito e revisão de segurança.
- Mantenha o `README.md` alinhado ao código e atualize `FLUXOGRAMA.md` quando o fluxo mudar.
- Regras ainda pendentes no PRD não são decisões aprovadas.

## Idioma e alterações

- Escreva documentação, interface e mensagens em português do Brasil.
- Prefira alterações pequenas que atendam ao pedido; não refatore áreas sem relação.
- Não adicione dependências sem necessidade aprovada.
- Para mudanças no banco, crie migration aditiva e preserve os registros existentes.
- Não apague dados nem execute migrations destrutivas em bancos com dados reais.

## Segurança e dados

- Nunca coloque senhas, tokens, chaves, dados reais ou conteúdo de `.env` no Git, em logs ou exemplos.
- Use banco local descartável e dados fictícios durante o desenvolvimento.
- Crie o primeiro administrador pelo comando interativo `php artisan ponto:administrador`; não coloque credenciais administrativas fixas no código.
- Senhas novas devem usar `Hash::make`. Não introduza novos hashes SHA-1; a compatibilidade legada existe somente para rehash durante login válido.
- Cheque perfil administrativo no servidor em todas as rotas de gestão. Cheque o status ativo durante autenticação e autorização.
- As quatro marcações devem usar data/hora do servidor e validar a sequência no servidor.
- A restrição compara `$request->ip()` com o IP público configurado. Não aceite IP do formulário nem confie em `X-Forwarded-For` sem configurar proxies reais e confiáveis.
- Login e histórico não dependem do IP autorizado; a verificação de IP vale para cada registro de ponto.
- Registros de ponto são evidências; não os sobrescreva sem uma regra aprovada.

## Executar localmente

Com Docker Desktop e Docker Compose v2:

```powershell
Copy-Item .env.example .env
docker compose up -d --build
docker compose exec web php artisan key:generate
docker compose exec web php artisan migrate --force
docker compose exec web php artisan ponto:administrador
```

Abra `http://127.0.0.1:8001`. O `.env` é local e não deve ser commitado. O banco do Compose é descartável e usa um volume Docker local.

## Verificações

- Para a suíte PHP existente: `vendor/bin/phpunit`.
- Os exemplos de teste presentes não cobrem todas as regras do produto.
- Não afirme que algo foi verificado sem executar a verificação e observar o resultado.
- Ao implementar regra de negócio, registre como validar o aceite e atualize a cobertura apropriada quando houver solicitação de testes.

## Estrutura útil

- `routes/web.php`: rotas das páginas atuais.
- `app/Http/Controllers/PontoEletronico/`: login, dashboard, cadastro, registro e acompanhamento.
- `app/Http/Middleware/`: autorização dos perfis.
- `app/Console/Commands/CriarAdministrador.php`: criação interativa do primeiro administrador.
- `app/Usuario.php`, `app/Ponto.php`, `app/ConfiguracaoSistema.php`: modelos.
- `database/migrations/`: esquema, inclusive horários do intervalo e configuração do IP.
- `resources/views/pontoeletronico/`: páginas Blade.
- `public/css/ponto.css`: estilos das telas atuais.

## Git e entrega

- Confira `git status` e a branch antes de preparar um commit.
- Não inclua `.env`, arquivos de credenciais nem dados reais.
- Use Conventional Commits em pt-BR, por exemplo `feat(ponto): implementa painel da jornada`.
