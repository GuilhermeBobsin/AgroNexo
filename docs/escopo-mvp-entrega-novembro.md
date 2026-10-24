# Escopo MVP para entrega em novembro

**Data de referência:** 24/10/2026  
**Data-alvo:** 30/11/2026

Este documento congela o escopo funcional mínimo para priorizar estabilização e homologação. Ele não declara o produto pronto: cada item ainda precisa de verificação em ambiente de homologação e aceite dos responsáveis.

## Dentro do MVP

- Acesso por criação administrativa, login, recuperação de senha e bloqueio de usuários inativos.
- Perfis admin, agrônomo e operador, com autorização por papel e propriedade vinculada.
- Cadastros de propriedades, talhões, culturas, produtos e recursos.
- Estoque de produto por propriedade, consulta de saldo e baixa consistente ao registrar aplicação.
- Criação e acompanhamento de tarefas pelo admin, acompanhamento técnico pelo agrônomo e execução pelo operador.
- Recomendações do agrônomo, revisão administrativa, conversão em tarefa e notificações correspondentes.
- Registro e consulta de aplicações concluídas.
- Inteligência climática informativa para pulverização, aração e calagem, com origem/horário da previsão, indisponibilidade explícita e licença da fonte resolvida.
- Dashboards existentes, correções de navegação e experiência responsiva dos fluxos críticos.
- Preparação de produção documentada: configuração, banco, scheduler, filas, logs, backup e restauração.

## Fora do MVP, salvo repriorização explícita

- Gestão financeira, contas a pagar/receber, fluxo de caixa e custos completos.
- Planejamento de safra/ciclo, rotação de culturas e caderno agronômico avançado.
- Integração com ERP, telemetria de máquinas, sensores, satélite ou aplicativo móvel offline.
- BI avançado, relatórios customizados e exportações complexas.
- Rastreabilidade regulatória completa por lote e cadeia de custódia, além do histórico operacional básico.

## Condições para considerar o MVP entregável

- Nenhum cadastro público pode conceder ou permitir escolher perfil administrativo.
- Os papéis não podem ler ou alterar dados fora das propriedades autorizadas.
- Fluxo de tarefa/aplicação não pode duplicar aplicação nem produzir saldo de estoque negativo.
- CRUD de talhões mantém geometria/área consistentes e suas páginas não geram erro.
- A inteligência comunica atualização e ausência de dados; as regras têm validação agronômica documentada.
- Fluxos críticos passam em testes automatizados e homologação com usuários de cada perfil.
- Existe procedimento repetível de implantação e recuperação do banco.

## Controle de mudança

Novas funcionalidades após 01/11 devem substituir outra entrega planejada ou ser adiadas. Mudanças de segurança, integridade de dados e defeitos bloqueadores permanecem prioritárias.
