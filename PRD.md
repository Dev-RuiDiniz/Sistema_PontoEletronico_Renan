# PRD — Sistema de Ponto Eletrônico

**Estado:** telas e fluxos principais implementados no repositório. A restrição por IP está disponível, mas começa desligada. A aplicação ainda precisa ser validada com os responsáveis antes de qualquer uso real.

## 1. Objetivo

Permitir que uma equipe registre quatro horários por jornada e consulte esses registros com clareza. O administrador acompanha a equipe, cadastra funcionários e pode limitar as marcações ao IP público fixo da empresa.

## 2. Perfis e permissões

| Perfil | Permissões |
| --- | --- |
| Funcionário | Registrar a própria jornada na sequência aprovada e consultar o próprio histórico mensal. |
| Administrador | Acompanhar as jornadas de todos, filtrar por mês e funcionário, cadastrar funcionários e configurar a restrição de IP. |

O login é único. Após validar uma conta ativa, o servidor direciona cada perfil à sua área. Rotas administrativas verificam o perfil no servidor; esconder um link na interface não é tratado como autorização.

## 3. Telas e rotas

1. **`/` — Início:** apresentação comercial, descrição das quatro marcações e acesso ao login.
2. **`/login` — Acesso:** CPF e senha para funcionários e administradores. O perfil da conta define o destino.
3. **`/cadastro` — Funcionários:** formulário administrativo para criar conta ativa com nome, CPF, e-mail, cargo, local e senha inicial.
4. **`/dashboard` — Jornada pessoal:** próxima marcação, quatro horários do dia e histórico mensal do funcionário autenticado.
5. **`/admin` — Acompanhamento:** jornadas de todos os funcionários, filtro mensal e individual, lista de contas e configuração do IP.

Os endereços antigos sob `/painel` redirecionam para as áreas novas; as telas antigas de ajustes e relatórios não fazem parte deste fluxo.

## 4. Regras do ponto

- A ordem é **entrada → início do intervalo → retorno do intervalo → saída**.
- Uma jornada é guardada por funcionário e data do servidor.
- O funcionário só pode salvar a próxima marcação esperada. Repetições e ações fora de ordem são recusadas no servidor.
- A hora vem do servidor; o formulário não envia uma hora para ser confiada.
- O fuso horário configurado é `America/Sao_Paulo`.
- O histórico do funcionário é limitado à própria conta. A administração pode consultar a equipe inteira ou filtrar um funcionário.
- Registros antigos com entrada e saída continuam visíveis. O sistema não inventa horários de intervalo para dados históricos.
- A versão atual não atende jornadas que cruzam a meia-noite; cada data local é tratada como uma jornada separada.
- Correções de ponto e recuperação de senha não estão disponíveis nesta versão.

## 5. Restrição pelo IP da empresa

- O endereço configurado deve ser o **IP público de saída** do Wi-Fi da empresa, não o endereço privado do roteador.
- A opção começa desligada. Um administrador autorizado informa o IP e decide ativar a restrição.
- Com a opção ligada, todas as quatro marcações comparam o IP observado pelo servidor com o valor configurado. Se não houver endereço salvo ou houver divergência, a marcação é negada.
- Login e consulta de histórico continuam disponíveis de outras redes.
- O servidor não confia em `X-Forwarded-For` por padrão. Se a hospedagem usar proxy reverso, configure somente os proxies reais como confiáveis; sem essa configuração, o IP observado poderá ser o do proxy e o acesso será negado.
- A regra identifica a conexão pública, não prova que alguém esteja pessoalmente no local. VPN, proxy ou compartilhamento de conexão podem alterar a rede aparente.
- O usuário confirmou que a conexão da empresa possui IP fixo. O endereço numérico ainda precisa ser informado.

## 6. Contas e senhas

- Somente contas ativas podem autenticar.
- Contas novas recebem hash bcrypt; a senha nunca é enviada de volta às telas depois do cadastro.
- Contas legadas com senha SHA-1 podem migrar para bcrypt automaticamente depois de uma autenticação válida.
- O primeiro administrador é criado por `php artisan ponto:administrador` (ou `docker compose exec web php artisan ponto:administrador`). A senha é solicitada interativamente e não vem pré-configurada no repositório.
- O cadastro comum não oferece escolha de perfil administrativo.

## 7. Dados

A tabela `ponto` mantém uma linha por funcionário e dia com `entrada`, `inicio_intervalo`, `retorno_intervalo` e `saida`. Uma migration aditiva acrescenta os horários de intervalo e preserva os registros já existentes.

A tabela `configuracao_sistema` guarda um único IP autorizado e o estado da restrição. A configuração inicial não ativa o bloqueio. A tabela `usuario` guarda os dados de conta e a indicação do perfil.

## 8. Critérios de aceite

- Uma instalação nova consegue criar um administrador por comando interativo.
- Funcionário ativo entra pela tela única e chega ao próprio painel; administrador chega ao acompanhamento geral.
- Conta inativa não autentica e funcionário não acessa as rotas administrativas diretamente.
- O registro exige a ordem de quatro etapas, usa data/hora do servidor e mostra confirmação ou erro compreensível.
- A tela do funcionário consulta somente os próprios registros do mês escolhido.
- O painel administrativo consulta os registros de todos e permite filtrar por mês ou funcionário.
- Cadastro cria uma conta ativa, única por CPF/e-mail, com senha armazenada em hash.
- Com a restrição de IP desligada, o registro segue disponível segundo as demais regras.
- Com a restrição ligada, só o IP público configurado consegue gravar as quatro etapas; login e histórico continuam acessíveis de fora.

## 9. Pendências antes de produção

- Informar e configurar o IP público fixo da empresa; não ativar a restrição antes disso.
- Identificar se a hospedagem usará proxy reverso e, se sim, configurar os proxies confiáveis reais.
- Decidir como lidar com esquecimento ou repetição de batida e como corrigir jornada incorreta.
- Definir o tratamento de jornadas que atravessam a meia-noite.
- Validar as regras e a experiência com os responsáveis e funcionários antes de uso oficial.
- Planejar recuperação de senha, backup e rotina de revogação de contas quando forem necessárias.

## 10. Fora do escopo atual

Solicitação e aprovação de ajustes, anexos, exportações, escalas complexas, cálculo de banco de horas, múltiplos IPs/unidades, biometria, geolocalização, aplicativo móvel e integração com folha.
