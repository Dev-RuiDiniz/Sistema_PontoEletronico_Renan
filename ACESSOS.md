# Acessos e credenciais

Este arquivo é um inventário, não um cofre. Não registre senhas, tokens, chaves, CPF real ou o conteúdo de `.env` no Git.

## Ambientes

| Sistema | Ambiente/URL | Responsável | Como obter acesso |
| --- | --- | --- | --- |
| Aplicação | Desenvolvimento Docker: `http://127.0.0.1:8001` | Desenvolvedor | Criar o primeiro administrador pelo comando abaixo |
| Aplicação | Homologação | A definir | Cofre de segredos da equipe; URL a definir |
| Aplicação | Produção | A definir | Cofre de segredos da equipe; URL a definir |
| Banco de dados | Desenvolvimento Docker | Desenvolvedor | Serviço `db` do Compose; banco local descartável |
| Hospedagem e domínio | A definir | A definir | Cofre de segredos da equipe |
| IP público autorizado | Wi-Fi da empresa; endereço numérico pendente | Responsável de TI/operadora | Confirmar com a operadora e cadastrar em `/admin` |

## Primeiro administrador local

Depois de iniciar o Docker e aplicar as migrations, execute:

```powershell
docker compose exec web php artisan ponto:administrador
```

O comando solicita os dados no terminal, oculta a senha e cria uma conta ativa com perfil administrativo. Não existe senha padrão compartilhada. Para execução sem Docker, use `php artisan ponto:administrador` no diretório do projeto.

## Regras para segredos

- Use um gerenciador de senhas/segredos aprovado pela equipe para acessos de homologação e produção.
- Registre neste documento somente responsáveis, nomes de itens no cofre e URLs públicas.
- `.env` contém configuração local; não o envie nem o inclua em commits.
- Se uma credencial real aparecer no Git ou em uma mensagem, considere-a exposta e troque-a.
- Use somente dados fictícios no banco local.

## Pendências de acesso

- [ ] Definir quem administra o cofre e revoga acessos.
- [ ] Informar as URLs de homologação e produção.
- [ ] Criar e guardar a conta administrativa de cada ambiente usando processo seguro.
- [ ] Confirmar o endereço numérico do IP público fixo e decidir se a trava ficará ativa.
- [ ] Se houver proxy reverso no ambiente publicado, cadastrar seus endereços reais como proxies confiáveis antes de ativar a restrição por IP.
- [ ] Definir processo de rotação de segredos e backup do banco.
