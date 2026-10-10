# PRD — iLAB4 Ponto Eletrônico

**Status:** levantamento inicial a partir do código disponível. Não foram encontrados PRD anterior nem arquivos de áudio nesta pasta. Regras marcadas como “a confirmar” não devem ser tratadas como decisão aprovada.

## 1. Resumo

O MVP será um relógio de ponto simples. O funcionário registra entrada, início do intervalo, retorno e saída, e consulta o próprio histórico. Opcionalmente, a empresa restringe as marcações ao IP público fixo do Wi-Fi autorizado. Justificativas, aprovações e relatórios existentes no protótipo ficam para uma etapa futura.

## 2. Problema e resultado esperado

Hoje, o projeto é um protótipo web. O resultado esperado da primeira etapa é ter quatro marcações simples na ordem certa, com data/hora confiáveis, acesso seguro e bloqueio opcional por IP.

## 3. Usuários e permissões pretendidas

| Perfil | Deve poder fazer |
| --- | --- |
| Funcionário | Entrar na própria conta; registrar entrada, início do intervalo, retorno e saída; consultar os próprios registros. |
| Administrador | Cadastrar e desativar funcionários; ativar/desativar a restrição por IP e configurar o IP autorizado. |

O código atual não aplica essa separação com segurança em todas as rotas do painel. A correção das permissões é requisito prioritário, não uma melhoria opcional.

## 4. Escopo funcional

### Incluído no produto pretendido

1. Login de funcionário e administrador.
2. Cadastro e desativação de contas de funcionários.
3. Uma entrada, um início de intervalo, um retorno e uma saída por jornada, nessa ordem.
4. Consulta do próprio histórico pelo funcionário.
5. Restrição opcional das quatro marcações a um único IP público fixo, configurável pelo administrador.

### Fora do MVP

Solicitações e aprovações de ajuste, upload de comprovantes, relatórios/exportações, várias unidades/IPs, reconhecimento facial/biometria, geolocalização, aplicativo móvel, integração com folha, escalas complexas, cálculo de horas extras/banco de horas e conformidade trabalhista ficam fora do MVP simples. O protótipo tem algumas telas antigas dessas funções; elas não devem ser expostas até que sejam pedidas e revisadas.

## 5. Regras observadas no código

Estas regras descrevem o comportamento encontrado, não necessariamente o comportamento desejado:

- O login usa CPF e senha; o CPF digitado tem pontuação removida.
- O funcionário registra pontos para a data corrente do servidor.
- O banco atual guarda entrada e saída; ainda não há campos para início e retorno do intervalo. O fluxo de quatro marcações é uma regra nova que precisa ser implementada.
- O código tenta alertar para algumas sequências incompletas ou repetidas de entrada/saída. A validação é feita no servidor de forma incompleta e precisa ser redesenhada.
- Há motivos iniciais de justificativa, como consulta médica, falha no equipamento e “Outra”.
- Solicitações de ajuste usam status numérico: `0` pendente, `1` aprovado, `2` rejeitado (inferido dos filtros e ações existentes).
- Os registros distinguem marcação comum e ajustada por indicadores numéricos. O significado completo desses indicadores ainda precisa ser formalizado.
- O painel tem consultas por intervalo; a rota chamada `excel-acompanhamento` existe, mas formato, conteúdo e comportamento da exportação precisam ser confirmados.
- O README anterior anuncia inclusão, exclusão e edição de funcionários. A rota de exclusão está declarada, mas não foi encontrada a ação correspondente no controlador.

## 6. Fluxos pretendidos

### Registrar ponto

1. Funcionário entra com sua conta.
2. Se a restrição de rede estiver ativa, o servidor confere se o IP público de origem corresponde ao IP autorizado.
3. Escolhe a próxima marcação na ordem: entrada → início do intervalo → retorno → saída.
4. O sistema registra data e hora do servidor, valida a sequência e grava a marcação.
5. O funcionário confere as quatro marcações no próprio histórico.

### Restrição por IP da rede

- **Objetivo:** quando ativada, somente permitir criar uma das quatro marcações se a requisição vier do IP público autorizado pela empresa.
- **Configuração pretendida:** administrador autorizado ativa/desativa a regra e configura um único IP público fixo. O usuário confirmou que o IP do Wi-Fi é fixo; falta informar o endereço numérico. A configuração não pode ser alterada por funcionário comum.
- **Escopo proposto:** bloquear as quatro marcações feitas pelo funcionário. Login e consulta do histórico continuam disponíveis fora da rede. Solicitações de correção estão fora do MVP.
- **Validação:** comparar o IP obtido pelo servidor. Não aceitar o IP enviado em campo de formulário ou confiar cegamente em `X-Forwarded-For`; se existir proxy ou balanceador, configurar e confiar somente nos proxies reais.
- **Falha segura:** se a regra estiver ativa e o IP não estiver configurado, negar novas marcações e mostrar orientação ao usuário; nunca liberar tudo silenciosamente.
- **Limite:** a regra identifica a rede pública de saída, não a rede Wi-Fi pelo nome nem a pessoa/dispositivo. Autenticação individual continua obrigatória. VPN, proxy ou compartilhamento de rede podem fazer uma pessoa remota aparecer com um IP permitido.
- **Dependência operacional:** a empresa precisa informar o IP público estável do Wi-Fi autorizado. O responsável confirmou que esse IP é fixo. Um IP privado do roteador, como `192.168.x.x`, não identifica a conexão do lado de fora da rede. Suporte a outras unidades pode ser decidido depois, caso necessário.

### Recursos existentes que ficam para depois

O protótipo tem rotas e telas para pedir ajustes, anexar comprovantes, aprovar/rejeitar solicitações e consultar/exportar períodos. Esses recursos não fazem parte do fluxo simples inicial. Antes de reativá-los, será necessário definir suas regras e proteger as permissões.

## 7. Dados principais encontrados

- **Usuário:** nome, CPF, e-mail, senha, cargo, regime, local, indicador de administrador e ativo.
- **Ponto:** usuário, data, entrada, início do intervalo, retorno, saída e indicadores de status.
- **Campos novos necessários em `ponto`:** `inicio_intervalo` e `retorno_intervalo`. A proposta simples é manter uma linha por funcionário/jornada, com os quatro horários: `entrada`, `inicio_intervalo`, `retorno_intervalo` e `saida`. A migration deve preservar registros antigos que só têm entrada/saída.
- **Tabelas antigas fora do MVP:** solicitação de ajuste e motivos de justificativa.

## 8. Requisitos de qualidade

- Uma conta comum só pode consultar seus próprios registros.
- Contas inativas não podem autenticar.
- Senhas devem usar o mecanismo seguro de hash do framework; nunca SHA-1 nem texto simples.
- Data, hora, CPF e sequência das quatro marcações devem ser validados no servidor.
- Erros devem ser claros para o usuário e úteis para diagnóstico, sem expor dados pessoais ou segredos.

## 9. Regras de negócio que precisam de decisão

Antes de fechar o comportamento, o responsável pelo produto deve responder:

1. A primeira versão assume uma entrada, um início de intervalo, um retorno e uma saída por jornada, nessa ordem. Confirmar se pode haver mais de um intervalo ou jornada atravessando a meia-noite.
2. O que acontece se a pessoa esquecer ou repetir uma marcação?
3. Qual é o endereço numérico do IP público fixo? Quem pode ativar/desativar a restrição e atualizar o IP se a operadora mudar o endereço?
4. A restrição começa ligada ou desligada? Se desligada, qualquer funcionário autenticado pode marcar de qualquer rede?
5. Quais campos mínimos são necessários no cadastro do funcionário? Quem pode criar administradores?
6. Qual fuso horário deve ser usado e como identificar a jornada que atravessa a meia-noite?
7. Como comunicar/corrigir uma marcação errada nesta versão simples? (Esse fluxo ainda não está definido.)

## 10. Critérios de aceite da primeira versão confiável

- Funcionário ativo consegue registrar e consultar apenas os próprios pontos.
- Com a restrição de IP ativa, marcação só é salva a partir do IP público autorizado; de outro IP, nenhuma das quatro marcações é criada.
- Com a restrição desligada, o sistema mantém o comportamento de rede decidido pela empresa; a configuração só pode ser alterada por administrador.
- O fluxo válido registra entrada, início do intervalo, retorno e saída em sequência, conforme a regra que o responsável aprovar.
- Marcação fora da ordem ou repetida não é gravada; o funcionário recebe mensagem clara.
- Usuário sem perfil administrativo não consegue chamar diretamente nenhuma rota de gestão.
- Usuário inativo não consegue entrar.
- A criação inicial de administrador não depende de credencial fixa no código.

## 11. Riscos conhecidos do código atual

- O login legado compara senhas por SHA-1. As contas de demonstração foram removidas da migration antes da publicação do repositório, mas bancos criados pela versão anterior podem mantê-las.
- Middleware do painel checa sessão, mas não basta para proteger ações administrativas; login do painel aceita qualquer conta.
- O campo `ativo` não é usado como condição no login observado.
- Há rotas com ações não encontradas e operações destrutivas via GET.
- O registro confia em data/hora enviados pelo formulário e não aplica validação robusta.
- O ponto atual não tem os dois horários do intervalo, nem validação de quatro etapas.
- O middleware `TrustProxies` está ativo e declara cabeçalhos `X-Forwarded-*`; a configuração de proxies confiáveis precisa ser conferida antes de usar o IP para autorização.
- O sistema precisa de um IP público fixo configurado; o responsável confirmou que a conexão é fixa, mas ainda não informou o endereço numérico.
- Upload usa validação incompleta e grava em caminho público.
- Recursos de ajuste e relatório estão presentes no protótipo, mas não são parte do MVP e precisam ficar desativados até serem revisados.
- Testes atuais são exemplos e não cobrem as regras de produto.

## 12. Fontes e limites deste levantamento

Fontes examinadas: `README.md`, rotas, modelos, controladores, middleware, migrations, views principais, `.env.example`, `composer.json`, `package.json` e testes existentes. Não foram localizados `PRD.md`, `AGENTS.md`, `ROADMAP.md`, `FLUXOGRAMA.md`, `ACESSOS.md` anteriores, nem arquivos de áudio. Os documentos novos registram o que o código permite afirmar e deixam o restante como pergunta aberta.
