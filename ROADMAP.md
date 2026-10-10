# Roadmap — Sistema de Ponto Eletrônico

O produto solicitado tem cinco telas: início, login, cadastro, painel pessoal e acompanhamento administrativo. Este documento separa o que já existe no código da validação operacional que falta.

## Concluído no código

- [x] Criar a landing page em `/` e o login compartilhado em `/login`.
- [x] Direcionar funcionários e administradores aos seus painéis conforme o perfil da conta.
- [x] Proteger o painel administrativo e o cadastro no servidor.
- [x] Criar o cadastro administrativo de funcionário em `/cadastro`, validando CPF/e-mail únicos e senha.
- [x] Criar a tela pessoal em `/dashboard`, com entrada, intervalo, retorno, saída e histórico mensal.
- [x] Validar as quatro marcações em sequência e usar data/hora do servidor.
- [x] Acrescentar os campos de início e retorno do intervalo sem remover registros antigos.
- [x] Criar `/admin` com acompanhamento mensal, filtro por funcionário, lista de contas e status das jornadas.
- [x] Criar configuração administrativa opcional do IP público fixo e verificar esse IP nas quatro marcações.
- [x] Começar a restrição de IP desativada e negar a marcação quando ativa sem IP correspondente.
- [x] Criar o comando interativo de primeiro administrador, sem senha padrão no repositório.
- [x] Atualizar a interface e os cinco documentos do projeto.
- [x] Adicionar configuração Docker local documentada no README.

## Antes de usar com funcionários reais

- [ ] Validar cadastro, login de cada perfil e sequência de marcações com contas fictícias.
- [ ] Confirmar o IP público fixo com a operadora e configurá-lo no painel.
- [ ] Validar o bloqueio a partir de outra rede, antes de ativar a restrição em uso real.
- [ ] Se houver proxy reverso na hospedagem, cadastrar somente os proxies reais como confiáveis e validar o IP observado pelo Laravel.
- [ ] Definir uma rotina segura para correção quando uma marcação for esquecida ou repetida.
- [ ] Definir o tratamento de jornada que atravesse a meia-noite.
- [ ] Planejar backup, recuperação de senha e desligamento de contas.
- [ ] Homologar as regras com os responsáveis pela operação.

## Depois, se necessário

- [ ] Permitir desativação e reativação de contas pela interface administrativa.
- [ ] Solicitar e aprovar correções de ponto com histórico de autoria.
- [ ] Adicionar relatórios/exportações e suporte a outras unidades/IPs.
- [ ] Planejar escalas, banco de horas ou integrações somente com regras aprovadas.

## Critério de liberação

A primeira versão estará pronta para operação depois que os fluxos de funcionário e administrador forem homologados, o IP fixo real estiver configurado se a empresa optar pela restrição, a origem de IP estiver correta na hospedagem e existir uma regra operacional para lidar com batidas incompletas.
