# Roadmap — iLAB4 Ponto Eletrônico

O alvo inicial é simples: quatro batidas por jornada e uma trava opcional pelo IP fixo do Wi-Fi da empresa. Recursos antigos de ajustes, aprovações e relatórios ficam desativados até serem solicitados.

## Fase 0 — Preparar a base com segurança

- [ ] Usar apenas ambiente e banco locais descartáveis durante o desenvolvimento.
- [x] Tirar dados pessoais e credenciais fixas da migration de usuários.
- [ ] Criar o primeiro administrador por um processo seguro.
- [ ] Planejar a troca de senhas SHA-1 para o hash seguro do Laravel.
- [ ] Garantir que só administradores gerenciem contas e a configuração de rede; bloquear contas inativas.
- [ ] Desativar as rotas antigas de justificativa, anexos, aprovação e relatório no MVP.
- [ ] Configurar corretamente os proxies confiáveis da hospedagem antes de comparar IPs.
- [ ] Corrigir qualquer rota ativa que aponte para método inexistente e evitar ações destrutivas por GET.

**Pronto quando:** a aplicação puder ser iniciada com dados fictícios, sem contas compartilhadas, e as rotas fora do MVP não estiverem acessíveis.

## Fase 1 — Fechar as poucas regras que faltam

- [ ] Confirmar que haverá exatamente uma entrada, um início de intervalo, um retorno e uma saída em cada jornada.
- [ ] Decidir o que fazer se uma marcação for esquecida ou repetida; correção pelo sistema está fora do MVP por enquanto.
- [ ] Definir o fuso horário e como registrar uma jornada que atravesse a meia-noite.
- [ ] Definir o procedimento quando alguém esquecer ou repetir uma batida; não liberar sem uma forma segura de resolver esse caso.
- [ ] Receber o endereço numérico do IP público fixo confirmado.
- [ ] Definir se a trava começa ligada ou desligada e quem pode alterar o IP e a opção.
- [ ] Confirmar quais campos mínimos são necessários para criar uma conta de funcionário.

**Pronto quando:** essas respostas estiverem registradas no [PRD](PRD.md).

## Fase 2 — Implementar o relógio de ponto simples

- [ ] Atualizar o banco para guardar entrada, início do intervalo, retorno e saída, mantendo os registros antigos de entrada/saída.
- [ ] Mostrar apenas a próxima marcação válida e validar a sequência no servidor.
- [ ] Registrar a data e a hora no servidor, sem confiar no horário enviado pelo navegador.
- [ ] Permitir que funcionário consulte apenas o próprio histórico.
- [ ] Permitir que administrador cadastre/desative funcionários.
- [ ] Criar a opção administrativa para ligar/desligar a restrição e configurar o único IP público fixo.
- [ ] Com a restrição ligada, comparar o IP observado pelo servidor em todas as quatro ações de marcação; se o IP não estiver configurado ou não corresponder, não gravar.
- [ ] Manter login e consulta de histórico disponíveis fora do Wi-Fi autorizado.
- [ ] Atualizar as telas e o [fluxograma](FLUXOGRAMA.md) para o fluxo simples.

**Pronto quando:** cada etapa só puder ser gravada na ordem certa e a trava permitir/bloquear marcações conforme a configuração.

## Fase 3 — Preparar e liberar

- [ ] Revisar login, autorização e configuração de proxy antes de usar a regra de IP.
- [ ] Validar os quatro registros e a consulta do histórico com dados fictícios.
- [ ] Configurar o IP fixo real e decidir quem administrará as contas.
- [ ] Definir backup, recuperação e procedimento para IP indisponível ou alterado pela operadora.
- [ ] Definir como o funcionário avisa o administrador sobre marcação esquecida/repetida até existir correção dentro do sistema.
- [ ] Homologar com usuários e liberar gradualmente.

**Pronto quando:** os responsáveis conseguirem registrar a jornada completa na rede autorizada e a aplicação recusar marcações de outra rede quando a trava estiver ligada.

## Depois do MVP, somente se necessário

- [ ] Solicitar justificativas/correções dentro do sistema.
- [ ] Aprovar ou rejeitar ajustes com histórico.
- [ ] Anexar comprovantes.
- [ ] Criar relatórios e exportações.
- [ ] Suportar mais de um local/IP.

## Ordem prática

1. Resolver as questões da Fase 1.
2. Concluir segurança básica da Fase 0.
3. Implementar o registro de quatro horários e a regra de IP na Fase 2.
4. Validar e liberar pela Fase 3.
