# Fluxograma do Ponto Conceito

As cinco telas principais formam dois caminhos: o funcionário registra e consulta a própria jornada; o administrador acompanha a equipe, cria funcionários e configura a rede autorizada.

```mermaid
flowchart TD
    INICIO[Abre o sistema] --> HOME[ / página inicial ]
    HOME --> LOGIN[ /login · CPF e senha ]
    LOGIN --> CONTA{Conta existe e está ativa?}
    CONTA -->|Não| ERRO[Mostrar erro de acesso]
    ERRO --> LOGIN
    CONTA -->|Sim, funcionário| DASH[ /dashboard · jornada pessoal ]
    CONTA -->|Sim, administrador| ADMIN[ /admin · equipe ]

    DASH --> ACAO{Próxima etapa}
    ACAO -->|Entrada| PONTO1[Registrar entrada]
    ACAO -->|Intervalo| PONTO2[Registrar início do intervalo]
    ACAO -->|Retorno| PONTO3[Registrar retorno]
    ACAO -->|Saída| PONTO4[Registrar saída]
    PONTO1 --> IP{Restrição por IP ativa?}
    PONTO2 --> IP
    PONTO3 --> IP
    PONTO4 --> IP
    IP -->|Não| SEQUENCIA[Validar etapa esperada]
    IP -->|Sim| REDE{IP observado pelo servidor é o autorizado?}
    REDE -->|Não| BLOQUEIA[Negar e não gravar]
    REDE -->|Sim| SEQUENCIA
    SEQUENCIA -->|Não| ERRO_ORDEM[Informar a próxima etapa sem gravar]
    SEQUENCIA -->|Sim| HORA[Salvar data e hora do servidor em Brasília]
    HORA --> ATUALIZA[Atualizar jornada e histórico mensal]
    ATUALIZA --> DASH
    DASH --> HISTORICO[Consultar somente o histórico da própria conta]

    ADMIN --> ADMIN_ACAO{O que fazer?}
    ADMIN_ACAO -->|Acompanhar| FILTRO[Filtrar por mês ou funcionário]
    FILTRO --> LISTA[Ver todas as marcações e status]
    ADMIN_ACAO -->|Consultar funcionário| FUNCIONARIOS[Lista paginada · 10 por página]
    FUNCIONARIOS --> PONTOS_FUNCIONARIO[ /admin/funcionarios/id/pontos · histórico individual]
    ADMIN_ACAO -->|Conferir mês da equipe| ESPELHO[ /admin/espelho · registros mensais consolidados]
    ESPELHO --> EXPORTA_CSV[Exportar CSV para planilha]
    ESPELHO --> IMPRIME[Imprimir ou salvar PDF · assinaturas]
    ADMIN_ACAO -->|Cadastrar| CADASTRO[ /cadastro · criar funcionário ativo ]
    CADASTRO --> ADMIN
    ADMIN_ACAO -->|Configurar rede| IP_CONFIG[Informar IP público fixo e ativar/desativar restrição]
    IP_CONFIG --> ADMIN

    CRIACAO_ADMIN[Primeiro administrador: comando Artisan interativo] --> LOGIN
```

## Observações

- Login é compartilhado; o papel armazenado na conta decide para qual painel o usuário é enviado.
- A autorização administrativa é checada no servidor. Uma conta de funcionário não pode abrir diretamente `/cadastro` ou `/admin`.
- A restrição por IP afeta cada gravação de ponto. Login e consulta do histórico continuam acessíveis de outra rede.
- O IP é o endereço público da rede da empresa, não o IP local do roteador.
- O sistema não confia em cabeçalhos de IP encaminhados por padrão. Hospedagem com proxy reverso exige configuração explícita de proxies confiáveis.
- A jornada é agrupada pela data local e não cobre corretamente uma jornada que atravesse a meia-noite.
- O espelho mensal apresenta as marcações armazenadas; datas sem registro não são automaticamente classificadas como falta ou folga e as horas trabalhadas não são calculadas.
- O espelho pode ser exportado em CSV ou impresso/salvo em PDF para conferência e assinatura.
