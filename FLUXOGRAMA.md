# Fluxograma do MVP

O MVP tem dois caminhos: o funcionário marca os quatro horários e consulta os próprios registros; o administrador mantém as contas e a opção de IP.

```mermaid
flowchart TD
    INICIO[Pessoa abre o sistema] --> AREA{Qual área?}
    AREA -->|Funcionário| LOGIN_FUNC[Entrar com CPF e senha]
    AREA -->|Administrador| LOGIN_ADMIN[Entrar no painel]

    LOGIN_FUNC --> AUT_FUNC{Conta ativa e senha válida?}
    AUT_FUNC -->|Não| ERRO_FUNC[Mostrar erro]
    ERRO_FUNC --> LOGIN_FUNC
    AUT_FUNC -->|Sim| PAINEL_FUNC[Abrir painel pessoal]
    PAINEL_FUNC --> ACAO_FUNC{O que fazer?}
    ACAO_FUNC -->|Consultar| HISTORICO[Mostrar somente o próprio histórico]
    ACAO_FUNC -->|Marcar ponto| IP_ATIVO{Restrição por IP ligada?}
    IP_ATIVO -->|Não| PROXIMA[Mostrar próxima marcação]
    IP_ATIVO -->|Sim| IP_OK{IP público corresponde ao IP da empresa?}
    IP_OK -->|Não| BLOQUEAR[Não gravar e orientar a usar o Wi-Fi da empresa]
    IP_OK -->|Sim| PROXIMA
    PROXIMA --> ORDEM{Qual marcação vem agora?}
    ORDEM -->|1. Entrada| ENTRADA[Registrar entrada]
    ORDEM -->|2. Início do intervalo| INTERVALO[Registrar início do intervalo]
    ORDEM -->|3. Retorno| RETORNO[Registrar retorno]
    ORDEM -->|4. Saída| SAIDA[Registrar saída]
    ENTRADA --> HORA_SERVIDOR[Salvar data e hora do servidor]
    INTERVALO --> HORA_SERVIDOR
    RETORNO --> HORA_SERVIDOR
    SAIDA --> HORA_SERVIDOR
    HORA_SERVIDOR --> CONFIRMACAO[Mostrar confirmação e atualizar histórico]

    LOGIN_ADMIN --> AUT_ADMIN{Conta ativa e é administradora?}
    AUT_ADMIN -->|Não| NEGAR[Negar acesso]
    NEGAR --> LOGIN_ADMIN
    AUT_ADMIN -->|Sim| PAINEL_ADMIN[Abrir painel administrativo]
    PAINEL_ADMIN --> ACAO_ADMIN{O que fazer?}
    ACAO_ADMIN -->|Contas| USUARIOS[Cadastrar ou desativar funcionário]
    ACAO_ADMIN -->|Restrição de rede| CONFIG_IP[Ligar/desligar e configurar o IP fixo]
```

## Regras que o desenho representa

- A sequência é: entrada → início do intervalo → retorno → saída.
- Se a restrição estiver ligada, cada uma das quatro marcações exige que o IP de origem corresponda ao IP público autorizado.
- Login e consulta de histórico não dependem do IP autorizado.
- O funcionário precisa continuar entrando com sua própria conta. O IP identifica a rede, não a pessoa.
- O protótipo atual só tem campos para entrada e saída; início e retorno ainda precisam ser acrescentados.
- Justificativas, anexos, aprovações e relatórios existentes no protótipo ficam desativados no MVP.

O IP validado é o endereço público de saída do Wi-Fi, não o nome da rede nem o IP privado do roteador. Se a hospedagem usa proxy, ele precisa estar configurado corretamente para o sistema receber o endereço real do visitante.
