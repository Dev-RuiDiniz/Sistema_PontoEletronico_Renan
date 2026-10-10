# Instruções para trabalhar neste projeto

## Fonte da verdade

- Leia primeiro `PRD.md` e `ROADMAP.md` antes de alterar comportamento.
- Mantenha o primeiro produto pequeno: quatro batidas, histórico pessoal, gestão de contas e restrição opcional por um IP.
- Não reative justificativas, anexos, aprovações ou relatórios antigos sem pedido explícito e revisão de segurança.
- O PRD separa comportamento encontrado de regras ainda pendentes. Não transforme hipótese em regra aprovada.
- Mantenha `README.md` alinhado com o que realmente existe. Atualize o `FLUXOGRAMA.md` quando um fluxo mudar.
- Se instruções globais do usuário ou do ambiente forem mais específicas, elas têm prioridade.

## Idioma e mudanças

- Escreva documentação, textos da interface e mensagens em português do Brasil.
- Prefira mudanças pequenas ligadas ao pedido. Não refatore áreas próximas sem necessidade.
- Não adicione funcionalidades ou dependências sem um requisito explícito.
- Ao alterar dados, crie migration compatível com o histórico; não edite migration já aplicada em ambientes compartilhados.
- Não apague dados nem execute migrations destrutivas em banco com dados reais.

## Segurança e dados

- Nunca coloque senhas, tokens, chaves, dados reais de funcionários ou conteúdo de `.env` em commits, logs, exemplos ou respostas.
- Use valores fictícios e banco local descartável para desenvolvimento.
- Não copie credenciais dos usuários de demonstração que aparecem em migrations. Remova esse mecanismo antes de implantação.
- Mudanças em login, papéis, ajustes, upload e exportação precisam preservar autorização no servidor, não apenas esconder botões na tela.
- A restrição de rede deve comparar o IP observado pelo servidor em todas as rotas de gravação de ponto. Nunca confie em IP enviado pelo formulário ou em cabeçalho `X-Forwarded-For` vindo de proxy não configurado como confiável.
- Registre data e hora no servidor para as quatro marcações (entrada, início do intervalo, retorno, saída), conforme a regra aprovada.
- A restrição por IP identifica a saída pública da rede e não substitui login individual nem comprova qual pessoa ou dispositivo está no local.
- Registros de ponto e decisões de ajuste devem manter autoria e histórico; não sobrescreva evidências sem uma regra aprovada.

## Como executar localmente

Pré-requisitos: PHP compatível com `composer.json`, Composer e MySQL. Veja o `README.md` para a configuração completa.

```powershell
composer install
Copy-Item .env.example .env
# Edite .env com um banco local descartável
php artisan key:generate
php artisan migrate
php artisan serve
```

O repositório entregue não continha `.env` e a migration de usuários insere dados de demonstração. Não use banco de produção nesta configuração.

## Verificações

- Para a suíte PHP existente: `vendor/bin/phpunit`.
- Os testes presentes são exemplos e não demonstram que os fluxos do produto estão corretos.
- Ao implementar uma regra nova, descreva o cenário manual ou automatizado que comprova o aceite e atualize a cobertura correspondente.
- Não afirme que algo foi testado sem executar a verificação e observar o resultado.

## Estrutura útil

- `app/Http/Controllers/PontoEletronico/`: fluxos de login, ponto, painel e relatórios.
- `app/Http/Middleware/`: checagens de sessão atuais.
- `app/*.php`: modelos Eloquent.
- `database/migrations/`: tabelas e dados iniciais.
- `resources/views/pontoeletronico/`: telas Blade.
- `routes/web.php`: rotas da aplicação.
- `public/adminlte/`: assets de interface já incluídos.

## Git e entrega

Esta cópia foi recebida sem diretório `.git`; confirme o repositório e a branch antes de preparar commits. Quando aplicável, siga o padrão existente; se não houver padrão, use Conventional Commits em português, por exemplo `docs: documenta regras e fluxos do ponto`.
