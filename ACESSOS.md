# Acessos e senhas

Este arquivo é um inventário, **não um cofre de senhas**. Nenhuma credencial real foi fornecida junto com os arquivos analisados; por isso não há usuário, senha ou endereço de produção preenchido aqui.

## Regra para credenciais

- Guarde senhas, tokens, chaves e credenciais de produção em um gerenciador de segredos aprovado pela equipe.
- Não cole segredos neste arquivo, no Git, em tickets, prints, logs ou mensagens.
- Registre aqui apenas o nome do sistema, o responsável e a localização segura do segredo (por exemplo, o nome do item no cofre), nunca o valor.
- Para desenvolvimento, use contas fictícias e banco local descartável.
- Se um segredo real já tiver sido colocado no repositório ou compartilhado por engano, trate-o como exposto e troque-o.

## Inventário a preencher pela equipe

| Sistema | Ambiente/URL | Responsável | Onde obter o acesso |
| --- | --- | --- | --- |
| Aplicação | Desenvolvimento: `http://127.0.0.1:8000` | A definir | Conta local criada após preparar o ambiente |
| Aplicação | Homologação: a definir | A definir | Cofre de segredos da equipe |
| Aplicação | Produção: a definir | A definir | Cofre de segredos da equipe |
| Banco MySQL | Local: configuração no `.env` local | Desenvolvedor | Credenciais locais; não compartilhar |
| Banco MySQL | Homologação/produção: a definir | A definir | Cofre de segredos da equipe |
| Hospedagem/servidor | A definir | A definir | Cofre de segredos da equipe |
| E-mail/SMTP | A definir | A definir | Cofre de segredos da equipe |
| IP público permitido para registro | Um IP fixo do Wi-Fi da empresa (confirmado; endereço ainda pendente) | Responsável de TI/operadora | Cadastro administrativo da aplicação |

## Atenção sobre dados de demonstração

O arquivo de migration que inseria contas de exemplo com dados pessoais e senha em formato SHA-1 foi neutralizado antes da publicação inicial do repositório. Bancos que já tenham passado pela versão antiga ainda podem conter essas contas: remova-as ou troque suas senhas. A criação segura do primeiro administrador ainda precisa ser implementada; consulte o `ROADMAP.md`.

## Pendências

- [ ] Definir responsável pelo cofre e quem pode conceder/revogar acessos.
- [ ] Informar URLs de homologação e produção sem incluir senhas.
- [ ] Criar conta administrativa inicial por processo seguro e registrar apenas onde recuperá-la.
- [ ] Definir rotina de rotação e revogação de credenciais.
- [ ] Obter e configurar o endereço numérico do IP público fixo confirmado. Não registrar senha do Wi-Fi aqui.
