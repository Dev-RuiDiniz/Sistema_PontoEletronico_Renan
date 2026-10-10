# iLAB4 Ponto Eletrônico

Sistema web de registro de ponto para equipes pequenas. O projeto já contém uma aplicação Laravel com telas para funcionários e um painel de acompanhamento. Esta documentação descreve o código encontrado no repositório e separa o que ainda precisa ser validado antes de uso real.

> **Estado atual:** protótipo legado. O primeiro produto será um relógio de ponto simples. O código contém telas antigas de ajustes e relatórios, mas elas ficam fora do MVP até serem solicitadas e revisadas. Há lacunas de segurança; não coloque em produção antes de concluir os itens prioritários do [roadmap](ROADMAP.md).

## O que o sistema pretende resolver

- O funcionário registra quatro marcações: entrada, início do intervalo, retorno do intervalo e saída.
- O funcionário consulta o próprio histórico.
- O administrador cadastra e desativa funcionários.
- Opcionalmente, o administrador restringe as marcações ao IP público fixo do Wi-Fi autorizado (um endereço nesta primeira versão).

O fluxo assume uma marcação de cada tipo, nesta ordem: entrada → início do intervalo → retorno → saída. A restrição identifica a conexão de saída da empresa para a internet, não o nome da rede Wi-Fi. O IP fixo foi confirmado; o endereço numérico ainda precisa ser fornecido para configuração. Login e consulta de histórico podem continuar funcionando fora da rede; a restrição proposta se aplica ao ato de registrar o ponto.

O detalhamento está no [PRD](PRD.md); o caminho das telas e decisões está no [fluxograma](FLUXOGRAMA.md). As funções antigas de justificativa e relatório estão listadas como etapas futuras no [roadmap](ROADMAP.md).

## Perfis

- **Funcionário:** registra os quatro horários e consulta seus próprios registros.
- **Administrador:** mantém as contas dos funcionários e configura a restrição de IP.

Esses são os perfis desejados. No código atual, a autorização precisa ser corrigida: algumas rotas de gestão só verificam se existe uma sessão de painel e não confirmam que a conta é administradora.

## Tecnologias encontradas

- PHP e Laravel 7 (requisito declarado no `composer.json`).
- MySQL como banco configurado no `.env.example`.
- Blade nas telas e assets AdminLTE incluídos em `public/adminlte`.
- PHPUnit 8 no conjunto de desenvolvimento; os testes existentes são apenas exemplos e não cobrem as regras de ponto.

## Como iniciar localmente

Pré-requisitos: PHP compatível com `composer.json`, Composer e MySQL. Confira também as extensões PHP exigidas pelo Laravel/Composer se a instalação apontar alguma ausente.

1. Instale as dependências:

   ```powershell
   composer install
   ```

2. Crie seu arquivo local de ambiente e ajuste a conexão MySQL. Não compartilhe nem versione `.env`:

   ```powershell
   Copy-Item .env.example .env
   ```

   Preencha `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD` com valores locais.

3. Gere a chave da aplicação e crie as tabelas:

   ```powershell
   php artisan key:generate
   php artisan migrate
   ```

4. Inicie o servidor de desenvolvimento:

   ```powershell
   php artisan serve
   ```

   Abra `http://127.0.0.1:8000`. O acesso ao painel começa em `/painel`.

**Atenção:** as contas de demonstração foram removidas da migration para não publicar dados pessoais nem senhas fixas. Uma instalação nova não cria usuários automaticamente; o processo seguro de criação do primeiro administrador ainda precisa ser implementado antes de usar o sistema. Bancos que já tenham sido instalados anteriormente podem manter as contas antigas; troque/remova essas credenciais antes de conectar esse banco a qualquer ambiente compartilhado.

Os comandos acima descrevem a configuração pretendida, não uma validação de que o projeto foi instalado ou executado neste ambiente.

## Documentos do projeto

- [PRD.md](PRD.md): objetivo, escopo, regras observadas e decisões que ainda precisam de resposta.
- [AGENTS.md](AGENTS.md): instruções para contribuir e executar o projeto.
- [ROADMAP.md](ROADMAP.md): sequência de trabalho para levar o protótipo a uma versão confiável.
- [FLUXOGRAMA.md](FLUXOGRAMA.md): visão visual dos fluxos do funcionário e do painel.
- [ACESSOS.md](ACESSOS.md): inventário seguro de acessos; não contém senhas reais.

## Demonstração e licença

O README anterior apontava a demonstração `https://pontoeletronico.ilab4.me` e licença MIT. O endereço não foi verificado nesta análise; confirme sua disponibilidade e a titularidade da licença antes de divulgar ou reutilizar.
