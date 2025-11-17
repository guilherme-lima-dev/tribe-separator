# PLANO DE AÇÃO - SEPARADOR DE TRIBOS MAANAIM

**Data de Criação:** 2024  
**Versão:** 1.0  
**Status:** Em Planejamento

---

## 📋 SUMÁRIO EXECUTIVO

Este plano de ação visa corrigir bugs críticos, implementar funcionalidades faltantes e melhorar o algoritmo de distribuição automática do sistema Separador de Tribos Maanaim.

**Objetivo Principal:** Ter um sistema funcional e confiável que distribua campistas em tribos respeitando todas as regras de negócio e balanceamento físico.

**Estimativa Total:** 5-7 dias de desenvolvimento  
**Prioridade:** Alta

---

## 🎯 FASE 1: CORREÇÃO DE BUGS CRÍTICOS
**Duração Estimada:** 1-2 dias  
**Prioridade:** 🔴 CRÍTICA

### Tarefa 1.1: Corrigir Loop Faltante na Distribuição
**Arquivo:** `app/Http/Controllers/CampistaController.php:48-59`  
**Problema:** Loop `foreach ($tribos as $tribo)` está faltando

**Ações:**
- [ ] Adicionar loop `foreach ($tribos as $tribo)` na linha 51
- [ ] Testar distribuição automática
- [ ] Verificar se campistas são atribuídos corretamente

**Estimativa:** 30 minutos

---

### Tarefa 1.2: Corrigir Bug em Adicionar Confidente Conhecido
**Arquivo:** `app/Http/Controllers/CampistaController.php:112`  
**Problema:** Busca `Campista::find()` em vez de `Confidente::find()`

**Ações:**
- [ ] Alterar `Campista::find()` para `Confidente::find()`
- [ ] Testar adição de confidente conhecido
- [ ] Verificar se relacionamento é criado corretamente

**Estimativa:** 15 minutos

---

### Tarefa 1.3: Corrigir Inconsistência de Nome de Coluna
**Arquivo:** `app/Models/Confidente.php:29`  
**Problema:** Usa `id_tribo` mas migration cria `tribo_id`

**Ações:**
- [ ] Verificar qual nome está no banco de dados
- [ ] Corrigir modelo para usar nome correto (`tribo_id`)
- [ ] Testar relacionamento `tribo()` do modelo Confidente

**Estimativa:** 20 minutos

---

### Tarefa 1.4: Corrigir Inconsistência de Gênero
**Arquivo:** `app/Http/Controllers/CampistaController.php:63`  
**Problema:** Usa 'M'/'F' maiúsculo, banco usa 'm'/'f'

**Ações:**
- [ ] Verificar valores reais no banco de dados
- [ ] Padronizar para minúsculo ('m'/'f') em todo o código
- [ ] Atualizar método `verificaRegrasTribo()` para usar 'm'/'f'
- [ ] Testar validação de gênero

**Estimativa:** 30 minutos

---

### Tarefa 1.5: Corrigir Select de Confidentes na View
**Arquivo:** `resources/views/welcome.blade.php:387-389`  
**Problema:** Loop lista campistas em vez de confidentes

**Ações:**
- [ ] Passar variável `$confidentes` para a view no método `index()`
- [ ] Alterar loop para `@foreach($confidentes as $confidente)`
- [ ] Testar modal de adicionar confidente conhecido

**Estimativa:** 20 minutos

---

### Tarefa 1.6: Melhorar Método `verificaRegrasTribo()`
**Arquivo:** `app/Http/Controllers/CampistaController.php:61-65`  
**Problema:** Não verifica conhecidos/confidentes

**Ações:**
- [ ] Integrar método `retornaInfracaoNessaTribo()` do modelo Campista
- [ ] Usar validação completa de regras
- [ ] Manter validação de gênero e limite
- [ ] Testar com campistas que se conhecem

**Estimativa:** 1 hora

---

### Tarefa 1.7: Corrigir Distribuição para Limpar Tribos Anteriores
**Arquivo:** `app/Http/Controllers/CampistaController.php:48-59`  
**Problema:** Não remove campistas de tribos anteriores

**Ações:**
- [ ] Adicionar limpeza de `tribo_id` antes de distribuir
- [ ] Garantir que cada campista fique em apenas uma tribo
- [ ] Testar redistribuição

**Estimativa:** 30 minutos

---

**Subtotal Fase 1:** ~3-4 horas

---

## 🚀 FASE 2: IMPLEMENTAÇÃO DE BALANCEAMENTO FÍSICO
**Duração Estimada:** 2-3 dias  
**Prioridade:** 🟡 ALTA

### Tarefa 2.1: Criar Métodos de Cálculo de Médias Globais
**Arquivo:** `app/Models/Campista.php` ou novo Service

**Ações:**
- [ ] Criar método estático `calcularMediasGlobais()`
- [ ] Calcular peso médio de todos os campistas
- [ ] Calcular altura média de todos os campistas
- [ ] Calcular proporção de gêneros
- [ ] Retornar array com todas as médias

**Estimativa:** 1 hora

---

### Tarefa 2.2: Criar Método de Cálculo de Score de Balanceamento
**Arquivo:** `app/Http/Controllers/CampistaController.php` ou Service

**Ações:**
- [ ] Criar método `calcularScoreBalanceamento($campista, $tribo, $mediasGlobais)`
- [ ] Calcular score de peso (quanto mais próximo da média, melhor)
- [ ] Calcular score de altura
- [ ] Calcular score de gênero
- [ ] Retornar score total (quanto maior, melhor)

**Estimativa:** 2 horas

---

### Tarefa 2.3: Adicionar Validação de Balanceamento no Modelo Tribo
**Arquivo:** `app/Models/Tribo.php`

**Ações:**
- [ ] Criar método `estaBalanceada($mediasGlobais, $tolerancia = 0.15)`
- [ ] Verificar se peso médio está dentro da tolerância (ex: ±15%)
- [ ] Verificar se altura média está dentro da tolerância (ex: ±10%)
- [ ] Verificar proporção de gênero (mínimo de mulheres)
- [ ] Retornar array com status de cada métrica

**Estimativa:** 2 horas

---

### Tarefa 2.4: Integrar Balanceamento na Distribuição Automática
**Arquivo:** `app/Http/Controllers/CampistaController.php`

**Ações:**
- [ ] Calcular médias globais antes de distribuir
- [ ] Modificar `distribuirCampistasNasTribos()` para usar score
- [ ] Para cada campista, escolher tribo com melhor score
- [ ] Priorizar regras obrigatórias sobre balanceamento
- [ ] Testar distribuição com dados reais

**Estimativa:** 3-4 horas

---

### Tarefa 2.5: Adicionar Indicadores Visuais de Balanceamento
**Arquivo:** `resources/views/welcome.blade.php`

**Ações:**
- [ ] Calcular médias globais na view
- [ ] Mostrar indicador visual de desequilíbrio (cores)
- [ ] Adicionar tooltip explicando desequilíbrio
- [ ] Mostrar percentual de desvio da média

**Estimativa:** 2 horas

---

**Subtotal Fase 2:** ~10-12 horas

---

## 🧠 FASE 3: MELHORIA DO ALGORITMO DE DISTRIBUIÇÃO
**Duração Estimada:** 1-2 dias  
**Prioridade:** 🟡 ALTA

### Tarefa 3.1: Implementar Algoritmo de Múltiplas Passadas
**Arquivo:** `app/Http/Controllers/CampistaController.php`

**Ações:**
- [ ] **Passada 1:** Distribuição inicial respeitando regras obrigatórias
- [ ] **Passada 2:** Otimização de balanceamento (tentar trocas)
- [ ] **Passada 3:** Ajustes finos para campistas sem tribo
- [ ] Implementar função de troca de campistas entre tribos
- [ ] Avaliar se troca melhora balanceamento global

**Estimativa:** 4-5 horas

---

### Tarefa 3.2: Ordenar Campistas por Prioridade
**Arquivo:** `app/Http/Controllers/CampistaController.php`

**Ações:**
- [ ] Ordenar campistas por número de conhecidos (mais restrições primeiro)
- [ ] Considerar peso/altura para balanceamento
- [ ] Distribuir campistas mais restritivos primeiro

**Estimativa:** 1 hora

---

### Tarefa 3.3: Implementar Função de Trocas Otimizadas
**Arquivo:** `app/Http/Controllers/CampistaController.php`

**Ações:**
- [ ] Criar método `tentarTrocarCampistas($tribo1, $tribo2)`
- [ ] Avaliar todas as trocas possíveis
- [ ] Escolher troca que mais melhora balanceamento
- [ ] Validar que troca não viola regras

**Estimativa:** 3 horas

---

### Tarefa 3.4: Adicionar Logging e Debug
**Arquivo:** `app/Http/Controllers/CampistaController.php`

**Ações:**
- [ ] Adicionar logs de cada passo da distribuição
- [ ] Mostrar estatísticas antes/depois
- [ ] Criar modo debug para desenvolvimento

**Estimativa:** 1 hora

---

**Subtotal Fase 3:** ~9-10 horas

---

## ✅ FASE 4: VALIDAÇÃO E TESTES
**Duração Estimada:** 1 dia  
**Prioridade:** 🟢 MÉDIA

### Tarefa 4.1: Testar Todas as Funcionalidades
**Ações:**
- [ ] Testar cadastro de campistas
- [ ] Testar adição/remoção de conhecidos
- [ ] Testar adição/remoção de confidentes conhecidos
- [ ] Testar adição manual à tribo
- [ ] Testar distribuição automática
- [ ] Testar validação de regras

**Estimativa:** 2 horas

---

### Tarefa 4.2: Testar com Dados Reais
**Ações:**
- [ ] Criar conjunto de dados de teste realista
- [ ] Testar distribuição com 50-100 campistas
- [ ] Verificar se todas as tribos ficam válidas
- [ ] Verificar balanceamento físico
- [ ] Identificar casos edge

**Estimativa:** 2-3 horas

---

### Tarefa 4.3: Corrigir Problemas Identificados
**Ações:**
- [ ] Documentar problemas encontrados
- [ ] Corrigir bugs adicionais
- [ ] Ajustar algoritmo se necessário

**Estimativa:** 2-3 horas

---

**Subtotal Fase 4:** ~6-8 horas

---

## 📊 FASE 5: MELHORIAS DE INTERFACE E UX
**Duração Estimada:** 0.5-1 dia  
**Prioridade:** 🟢 BAIXA

### Tarefa 5.1: Melhorar Feedback Visual de Balanceamento
**Arquivo:** `resources/views/welcome.blade.php`

**Ações:**
- [ ] Adicionar cores para indicar desequilíbrio:
  - 🟢 Verde: Bem balanceado
  - 🟡 Amarelo: Levemente desbalanceado
  - 🔴 Vermelho: Muito desbalanceado
- [ ] Mostrar percentual de desvio
- [ ] Adicionar ícones visuais

**Estimativa:** 1-2 horas

---

### Tarefa 5.2: Adicionar Estatísticas Globais
**Arquivo:** `resources/views/welcome.blade.php`

**Ações:**
- [ ] Mostrar painel com estatísticas gerais:
  - Total de campistas
  - Total de tribos
  - Médias globais
  - Distribuição de gênero
- [ ] Adicionar gráficos (Chart.js já está incluído)

**Estimativa:** 2 horas

---

### Tarefa 5.3: Melhorar Mensagens de Erro
**Ações:**
- [ ] Tornar mensagens mais claras e específicas
- [ ] Adicionar sugestões quando possível
- [ ] Melhorar formatação de mensagens

**Estimativa:** 1 hora

---

**Subtotal Fase 5:** ~4-5 horas

---

## 📝 FASE 6: DOCUMENTAÇÃO E LIMPEZA
**Duração Estimada:** 0.5 dia  
**Prioridade:** 🟢 BAIXA

### Tarefa 6.1: Documentar Código
**Ações:**
- [ ] Adicionar PHPDoc em métodos principais
- [ ] Documentar algoritmo de distribuição
- [ ] Adicionar comentários explicativos

**Estimativa:** 1-2 horas

---

### Tarefa 6.2: Limpar Código
**Ações:**
- [ ] Remover código comentado
- [ ] Remover imports não utilizados
- [ ] Padronizar formatação
- [ ] Executar Laravel Pint (já configurado)

**Estimativa:** 1 hora

---

### Tarefa 6.3: Atualizar README
**Ações:**
- [ ] Adicionar instruções de instalação
- [ ] Documentar funcionalidades
- [ ] Adicionar exemplos de uso
- [ ] Documentar regras de negócio

**Estimativa:** 1 hora

---

**Subtotal Fase 6:** ~3-4 horas

---

## 📅 CRONOGRAMA SUGERIDO

### Semana 1

| Dia | Fase | Tarefas | Duração |
|-----|------|---------|---------|
| **Dia 1** | Fase 1 | Correção de bugs críticos | 4-5h |
| **Dia 2** | Fase 2 | Implementação de balanceamento (parte 1) | 4-5h |
| **Dia 3** | Fase 2 | Implementação de balanceamento (parte 2) | 4-5h |
| **Dia 4** | Fase 3 | Melhoria do algoritmo | 4-5h |
| **Dia 5** | Fase 4 | Testes e validação | 4-5h |

### Semana 2 (Opcional)

| Dia | Fase | Tarefas | Duração |
|-----|------|---------|---------|
| **Dia 6** | Fase 5 | Melhorias de interface | 3-4h |
| **Dia 7** | Fase 6 | Documentação e limpeza | 3-4h |

---

## 🎯 MARCOS (MILESTONES)

### Marco 1: Sistema Funcional Básico ✅
**Data Alvo:** Final do Dia 1  
**Critérios:**
- [ ] Todos os bugs críticos corrigidos
- [ ] Distribuição automática funciona (mesmo que básica)
- [ ] Validação de regras funcionando

### Marco 2: Balanceamento Implementado ✅
**Data Alvo:** Final do Dia 3  
**Critérios:**
- [ ] Cálculo de médias globais funcionando
- [ ] Score de balanceamento implementado
- [ ] Distribuição considera balanceamento

### Marco 3: Algoritmo Otimizado ✅
**Data Alvo:** Final do Dia 4  
**Critérios:**
- [ ] Algoritmo de múltiplas passadas implementado
- [ ] Trocas otimizadas funcionando
- [ ] Distribuição produz resultados balanceados

### Marco 4: Sistema Testado e Validado ✅
**Data Alvo:** Final do Dia 5  
**Critérios:**
- [ ] Testes com dados reais realizados
- [ ] Todas as funcionalidades validadas
- [ ] Sistema pronto para uso

---

## ⚠️ RISCOS E MITIGAÇÕES

### Risco 1: Algoritmo Não Encontra Solução
**Probabilidade:** Média  
**Impacto:** Alto  
**Mitigação:**
- Implementar fallback para distribuição parcial
- Permitir ajustes manuais
- Mostrar campistas que não puderam ser alocados

### Risco 2: Performance com Muitos Campistas
**Probabilidade:** Baixa  
**Impacto:** Médio  
**Mitigação:**
- Otimizar queries do banco
- Usar eager loading
- Implementar cache se necessário

### Risco 3: Balanceamento Muito Restritivo
**Probabilidade:** Média  
**Impacto:** Médio  
**Mitigação:**
- Tornar tolerâncias configuráveis
- Permitir ajustes manuais
- Mostrar sugestões de balanceamento

---

## 📈 MÉTRICAS DE SUCESSO

### Funcionalidade
- ✅ 100% dos bugs críticos corrigidos
- ✅ Distribuição automática funciona em 90%+ dos casos
- ✅ Todas as regras obrigatórias validadas

### Performance
- ✅ Distribuição completa em < 5 segundos
- ✅ Interface responsiva (< 1 segundo para ações)

### Qualidade
- ✅ Código documentado e limpo
- ✅ Testes realizados com dados reais
- ✅ Sistema pronto para produção

---

## 🔄 PROCESSO DE DESENVOLVIMENTO

### Workflow Sugerido

1. **Criar Branch:** `git checkout -b feature/nome-da-fase`
2. **Desenvolver:** Implementar tarefas da fase
3. **Testar:** Validar funcionalidades
4. **Commit:** Commits pequenos e frequentes
5. **Merge:** Após validação completa da fase

### Checklist Antes de Cada Commit

- [ ] Código funciona localmente
- [ ] Sem erros de sintaxe
- [ ] Testado manualmente
- [ ] Mensagem de commit descritiva

---

## 📞 SUPORTE E DÚVIDAS

Durante o desenvolvimento, documentar:
- Decisões técnicas tomadas
- Problemas encontrados e soluções
- Melhorias futuras identificadas

---

## ✅ CHECKLIST FINAL

Antes de considerar o projeto completo:

### Funcionalidades
- [ ] Todos os bugs críticos corrigidos
- [ ] Distribuição automática funcionando
- [ ] Balanceamento físico implementado
- [ ] Validação completa de regras
- [ ] Interface funcional e intuitiva

### Qualidade
- [ ] Código limpo e documentado
- [ ] Testes realizados
- [ ] Sem erros de lint
- [ ] Performance aceitável

### Documentação
- [ ] README atualizado
- [ ] Código comentado
- [ ] Relatório do sistema atualizado

---

**Status do Plano:** ✅ Pronto para Execução  
**Próximo Passo:** Iniciar Fase 1 - Correção de Bugs Críticos

---

*Última atualização: 2024*

