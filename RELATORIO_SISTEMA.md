# RELATÓRIO DO SISTEMA - SEPARADOR DE TRIBOS MAANAIM

**Versão:** 1.0  
**Data:** 2024  
**Tecnologia:** Laravel 11.9 (PHP 8.2+)  
**Banco de Dados:** SQLite (tribos_acampamento.db)

---

## 1. VISÃO GERAL DO SISTEMA

### 1.1. Objetivo
O **Separador de Tribos Maanaim** é uma aplicação web desenvolvida para automatizar e otimizar a distribuição de campistas em tribos durante o acampamento Maanaim. O sistema resolve o desafio complexo de criar grupos equilibrados que respeitam regras rigorosas de separação social e balanceamento físico.

### 1.2. Contexto do Problema
O acampamento Maanaim possui regras específicas que tornam a montagem manual de tribos extremamente trabalhosa:
- **Regra de Separação Social:** Campistas que se conhecem não podem estar na mesma tribo
- **Regra de Confidentes:** Campistas não podem estar na mesma tribo que confidentes que conhecem
- **Regra de Balanceamento:** As tribos precisam ser equilibradas em gênero, peso e altura para desafios físicos (escaladas, força física)
- **Regra de Tamanho:** Cada tribo deve ter entre 11 e 13 campistas

### 1.3. Solução Proposta
Sistema web que permite:
- Cadastro de campistas com informações físicas (peso, altura, gênero)
- Gerenciamento de relacionamentos (quem conhece quem)
- Distribuição automática de campistas em tribos respeitando todas as regras
- Visualização e ajuste manual quando necessário
- Validação em tempo real de violações de regras

---

## 2. ARQUITETURA E TECNOLOGIAS

### 2.1. Stack Tecnológico
- **Backend:** Laravel 11.9 (PHP 8.2+)
- **Frontend:** Blade Templates + Tailwind CSS + JavaScript (Vanilla)
- **Banco de Dados:** SQLite
- **Bibliotecas Externas:**
  - jQuery 3.7.1
  - Select2 4.1.0-beta.1 (para seleção de conhecidos)
  - Chart.js 2.9.4 (preparado para gráficos futuros)
  - Font Awesome 5.15.3

### 2.2. Estrutura de Diretórios
```
tribe-separator/
├── app/
│   ├── Http/Controllers/
│   │   ├── CampistaController.php    # Controlador principal
│   │   └── Controller.php
│   ├── Models/
│   │   ├── Campista.php              # Modelo de campista
│   │   ├── Tribo.php                 # Modelo de tribo
│   │   ├── Confidente.php            # Modelo de confidente
│   │   ├── CampistaConhece.php       # Tabela pivot conhecidos
│   │   └── CampistaConheceConfidente.php # Tabela pivot confidentes
│   └── Providers/
├── database/
│   ├── migrations/                    # Migrações do banco
│   └── seeders/
├── resources/
│   ├── views/
│   │   └── welcome.blade.php         # Interface principal
│   ├── css/
│   └── js/
├── routes/
│   └── web.php                        # Rotas da aplicação
└── public/                            # Arquivos públicos
```

---

## 3. MODELO DE DADOS

### 3.1. Entidades Principais

#### **Campista**
Representa um participante do acampamento.

**Campos:**
- `id` (PK)
- `nome` (string)
- `genero` (char: 'm' ou 'f')
- `peso` (numeric) - em kg
- `altura` (numeric) - em cm
- `tribo_id` (FK, nullable) - tribo atual

**Relacionamentos:**
- `conhecidos()` - Many-to-Many com outros Campistas
- `confidentesConhecidos()` - Many-to-Many com Confidentes
- `tribo()` - BelongsTo Tribo

#### **Tribo**
Representa um grupo de campistas.

**Campos:**
- `id` (PK)
- `nome_tribo` (string, unique)

**Relacionamentos:**
- `campistas()` - HasMany Campistas
- `confidentes()` - HasMany Confidentes

**Validação:**
- Deve ter entre 11 e 13 campistas para ser válida

#### **Confidente**
Representa um líder/confidente de uma tribo.

**Campos:**
- `id` (PK)
- `nome` (string)
- `tribo_id` (FK, nullable) - tribo que lidera

**Relacionamentos:**
- `campistas()` - Many-to-Many com Campistas (quem conhece)
- `tribo()` - BelongsTo Tribo

### 3.2. Tabelas de Relacionamento

#### **campistas_conhece**
Tabela pivot para relacionamento many-to-many entre campistas.

**Campos:**
- `id_campista` (FK)
- `id_conhecido` (FK)

**Comportamento:** Bidirecional (se A conhece B, ambos não podem estar juntos)

#### **campistas_conhece_confidentes**
Tabela pivot para relacionamento entre campistas e confidentes conhecidos.

**Campos:**
- `id_campista` (FK)
- `id_confidente` (FK)

**Regra:** Se um campista conhece um confidente, não pode estar na mesma tribo desse confidente.

### 3.3. Diagrama de Relacionamentos
```
Campista ──┬──< conhece >── Campista (bidirecional)
           │
           ├──< conhece >── Confidente
           │
           └──> pertence ── Tribo
                          
Tribo ──┬──< tem >── Campista (11-13)
        │
        └──< tem >── Confidente (1+)
```

---

## 4. REGRAS DE NEGÓCIO

### 4.1. Regras Obrigatórias (Hard Constraints)

#### **R1: Separação de Conhecidos**
- Um campista **NÃO PODE** estar na mesma tribo que outro campista que ele conhece
- Verificação **bidirecional**: Se A conhece B OU B conhece A, não podem estar juntos
- **Prioridade:** MÁXIMA (violação impede adição)

#### **R2: Separação de Confidentes Conhecidos**
- Um campista **NÃO PODE** estar na mesma tribo que um confidente que ele conhece
- **Prioridade:** MÁXIMA (violação impede adição)

#### **R3: Limite de Tamanho**
- Cada tribo deve ter entre **11 e 13 campistas**
- Máximo de **13 campistas** por tribo
- **Prioridade:** MÁXIMA (violação impede adição)

### 4.2. Regras de Balanceamento (Soft Constraints)

#### **R4: Balanceamento de Gênero**
- **Máximo de 7 homens** por tribo
- **Mínimo recomendado:** 4-5 mulheres por tribo
- **Objetivo:** Distribuição equilibrada entre tribos
- **Status Atual:** Parcialmente implementado (apenas máximo de homens)

#### **R5: Balanceamento de Peso**
- **Objetivo:** Média de peso similar entre todas as tribos
- **Necessário para:** Desafios de força física
- **Status Atual:** Apenas visualização (não usado na distribuição)

#### **R6: Balanceamento de Altura**
- **Objetivo:** Média de altura similar entre todas as tribos
- **Necessário para:** Desafios de escalada
- **Status Atual:** Apenas visualização (não usado na distribuição)

### 4.3. Validação de Tribo
Uma tribo é considerada **válida** quando:
- ✅ Tem entre 11 e 13 campistas
- ✅ Todos os campistas respeitam as regras R1 e R2
- ⚠️ Balanceamento físico (R4, R5, R6) - **não validado atualmente**

---

## 5. FUNCIONALIDADES IMPLEMENTADAS

### 5.1. Gerenciamento de Campistas

#### **Cadastro de Campista**
- Formulário modal para adicionar novo campista
- Campos: Nome, Gênero, Peso (kg), Altura (cm)
- Validação: Todos os campos obrigatórios, gênero 'm' ou 'f'

#### **Listagem de Campistas**
- Tabela com todos os campistas
- Exibição de: ID, Nome, Gênero, Peso, Altura
- Busca/filtro por qualquer campo
- Indicador visual de violação de regras (fundo vermelho claro)

#### **Remoção de Campista**
- Botão de exclusão com confirmação
- Remove campista e seus relacionamentos

### 5.2. Gerenciamento de Relacionamentos

#### **Conhecidos entre Campistas**
- Modal para visualizar conhecidos de um campista
- Adicionar conhecido via select
- Remover conhecido
- Verificação bidirecional automática

#### **Confidentes Conhecidos**
- Modal para visualizar confidentes conhecidos
- Adicionar confidente conhecido
- Remover confidente conhecido
- Validação automática ao adicionar à tribo

### 5.3. Gerenciamento de Tribos

#### **Visualização de Tribos**
- Cards para cada tribo mostrando:
  - Nome da tribo
  - Confidentes da tribo
  - Status de validação (válida/inválida)
  - Estatísticas:
    - Média de peso
    - Média de altura
    - Número de homens
    - Número de mulheres
  - Lista de campistas da tribo

#### **Adição Manual à Tribo**
- Botão "Adicionar a Tribo" em cada campista
- Lista de tribos disponíveis
- Validação em tempo real mostrando:
  - Motivos de bloqueio (se houver)
  - Botões desabilitados para tribos inválidas
- Mensagem explicativa de cada violação

#### **Remoção de Tribo**
- Botão para remover campista de uma tribo
- Disponível tanto na lista de campistas quanto na lista da tribo

### 5.4. Distribuição Automática

#### **Função "Montar Tribos"**
- Botão que executa algoritmo de distribuição automática
- **Status Atual:** Implementação básica com bugs
- **Funcionalidade Esperada:**
  - Limpar todas as tribos
  - Distribuir campistas respeitando regras
  - Balancear física e socialmente

---

## 6. INTERFACE DO USUÁRIO

### 6.1. Design
- **Framework CSS:** Tailwind CSS
- **Estilo:** Moderno, limpo, responsivo
- **Cores Principais:**
  - Verde (#2E8B57) - tema Maanaim
  - Vermelho claro - indicador de violação
  - Azul - ações principais

### 6.2. Componentes Principais

#### **Cabeçalho**
- Navegação com título "Maanaim - Separação de tribos"
- Barra verde com tema do acampamento

#### **Área de Tribos**
- Grid responsivo (3 colunas em telas grandes)
- Cards para cada tribo
- Indicadores visuais de status

#### **Área de Campistas**
- Tabela completa com busca
- Ações por campista:
  - Adicionar/Remover de tribo
  - Ver conhecidos
  - Ver confidentes conhecidos
  - Excluir campista

#### **Modais**
- Modal de conhecidos
- Modal de confidentes conhecidos
- Modal de adicionar campista
- Overlay escuro com fechamento por clique

### 6.3. Feedback Visual
- ✅ **Verde:** Sucesso, tribo válida
- ⚠️ **Amarelo:** Aviso, ação realizada
- ❌ **Vermelho:** Erro, violação de regra
- 🔴 **Fundo vermelho claro:** Campista com violação

---

## 7. ALGORITMO DE DISTRIBUIÇÃO (Estado Atual)

### 7.1. Implementação Atual
```php
private function distribuirCampistasNasTribos($campistas, $tribos)
{
    foreach ($campistas as $campista) {
        foreach ($tribos as $tribo) {
            if ($tribo->campistas()->count() < 13 && 
                $this->verificaRegrasTribo($tribo, $campista)) {
                $campista->tribo_id = $tribo->id;
                $campista->save();
                break;
            }
        }
    }
}
```

### 7.2. Problemas Identificados

#### **P1: Não Verifica Regras Principais**
- ❌ Não verifica conhecidos (R1)
- ❌ Não verifica confidentes conhecidos (R2)
- ✅ Apenas verifica limite de 13 e gênero

#### **P2: Não Remove de Tribos Anteriores**
- ❌ Não limpa `tribo_id` antes de redistribuir
- ❌ Pode manter campistas em múltiplas tribos

#### **P3: Não Considera Balanceamento**
- ❌ Não calcula médias globais
- ❌ Não tenta equilibrar peso/altura
- ❌ Não otimiza distribuição de gênero

#### **P4: Algoritmo Simples Demais**
- ❌ Distribuição sequencial sem otimização
- ❌ Não tenta múltiplas combinações
- ❌ Pode deixar campistas sem tribo

### 7.3. Método de Validação Atual
```php
private function verificaRegrasTribo(Tribo $tribo, Campista $campista): bool
{
    $numHomens = $tribo->campistas()->where('genero', 'M')->count();
    return $tribo->campistas()->count() < 13 && 
           ($campista->genero === 'F' || $numHomens < 7);
}
```

**Problemas:**
- Usa 'M'/'F' maiúsculo (inconsistência com banco 'm'/'f')
- Não verifica conhecidos/confidentes
- Não considera balanceamento

---

## 8. MÉTODOS DE VALIDAÇÃO

### 8.1. Modelo Campista

#### **`retornaInfracaoNessaTribo($idTribo)`**
Retorna mensagem explicativa de violações ao adicionar campista a uma tribo.

**Verifica:**
- ✅ Tribo existe
- ✅ Tribo não está cheia (13)
- ✅ Não conhece confidentes da tribo
- ✅ Não conhece campistas da tribo (bidirecional)

**Retorna:** String com mensagens ou `null` se válido

#### **`campistaAtendeARegra()`**
Verifica se campista atual respeita todas as regras na sua tribo.

**Verifica:**
- ✅ Tem tribo atribuída
- ✅ Não conhece confidentes da tribo
- ✅ Não conhece campistas da tribo (bidirecional)

**Retorna:** `true` ou `false`

### 8.2. Modelo Tribo

#### **`estaValida()`**
Verifica se tribo atende critérios básicos.

**Verifica:**
- ✅ Tem entre 11 e 13 campistas

**Não Verifica:**
- ❌ Balanceamento físico
- ❌ Regras de conhecidos (deveria verificar todos os campistas)

---

## 9. ROTAS DA APLICAÇÃO

### 9.1. Rotas Principais

| Método | Rota | Função | Controller |
|--------|------|--------|------------|
| GET | `/` | Página principal | `index()` |
| POST | `/adicionar-a-tribo/{campista}/{tribo}` | Adicionar manual | `adicionarATribo()` |
| POST | `/remover-da-tribo/{campista}` | Remover de tribo | `removerDatribo()` |
| GET | `/monta-tribos` | Distribuição automática | `montaTribos()` |

### 9.2. Rotas de API (JSON)

| Método | Rota | Função |
|--------|------|--------|
| GET | `/conhecidos/{campista}` | Listar conhecidos |
| POST | `/conhecidos/adicionar` | Adicionar conhecido |
| POST | `/conhecidos/remover` | Remover conhecido |
| GET | `/confidentes/{campista}` | Listar confidentes conhecidos |
| POST | `/confidentes/adicionar` | Adicionar confidente conhecido |
| POST | `/confidentes/remover` | Remover confidente conhecido |
| GET | `/confidentes` | Listar todos confidentes |
| POST | `/campistas/adicionar` | Criar campista |
| DELETE | `/campistas/remover/{id}` | Excluir campista |

---

## 10. PROBLEMAS E BUGS IDENTIFICADOS

### 10.1. Bugs Críticos

#### **B1: Bug na Distribuição Automática**
**Arquivo:** `app/Http/Controllers/CampistaController.php:48-59`
**Problema:** Loop `foreach ($tribos as $tribo)` está faltando na linha 51
**Impacto:** Código não compila/executa corretamente

#### **B2: Não Verifica Conhecidos na Distribuição**
**Arquivo:** `app/Http/Controllers/CampistaController.php:61-65`
**Problema:** `verificaRegrasTribo()` não verifica conhecidos/confidentes
**Impacto:** Distribuição automática pode violar regras principais

#### **B3: Bug em Adicionar Confidente Conhecido**
**Arquivo:** `app/Http/Controllers/CampistaController.php:112`
**Problema:** Busca `Campista::find()` em vez de `Confidente::find()`
**Impacto:** Não funciona corretamente

#### **B4: Inconsistência de Nome de Coluna**
**Arquivo:** `app/Models/Confidente.php:29`
**Problema:** Usa `id_tribo` mas migration cria `tribo_id`
**Impacto:** Relacionamento pode não funcionar

#### **B5: Inconsistência de Gênero**
**Arquivo:** `app/Http/Controllers/CampistaController.php:63`
**Problema:** Usa 'M'/'F' maiúsculo, banco usa 'm'/'f'
**Impacto:** Validação de gênero pode falhar

### 10.2. Bugs na Interface

#### **B6: Select de Confidentes Lista Campistas**
**Arquivo:** `resources/views/welcome.blade.php:387-389`
**Problema:** Loop `@foreach($campistas)` em vez de `@foreach($confidentes)`
**Impacto:** Não mostra confidentes corretos no select

### 10.3. Funcionalidades Faltantes

#### **F1: Balanceamento Físico**
- ❌ Não calcula médias globais
- ❌ Não valida balanceamento
- ❌ Não usa balanceamento na distribuição

#### **F2: Validação Completa de Tribo**
- ❌ Não verifica se todos os campistas respeitam regras
- ❌ Não valida balanceamento físico

#### **F3: Algoritmo de Distribuição Avançado**
- ❌ Algoritmo muito simples
- ❌ Não otimiza balanceamento
- ❌ Não tenta múltiplas combinações

---

## 11. ANÁLISE DE PERFORMANCE

### 11.1. Complexidade Atual

#### **Distribuição Automática**
- **Complexidade:** O(n × t) onde n = campistas, t = tribos
- **Estimativa:** ~50-130 campistas × 5-10 tribos = 250-1300 iterações
- **Performance:** Rápido (< 1 segundo)

#### **Validação de Conhecidos**
- **Complexidade:** O(k) onde k = número de conhecidos
- **Performance:** Rápido para casos normais

### 11.2. Otimizações Possíveis
- Cache de relacionamentos
- Índices no banco de dados
- Lazy loading de relacionamentos
- Algoritmo mais eficiente para distribuição

---

## 12. SEGURANÇA

### 12.1. Implementado
- ✅ CSRF protection (Laravel padrão)
- ✅ Validação de inputs
- ✅ Sanitização de dados

### 12.2. Recomendações
- ⚠️ Adicionar autenticação (atualmente sem login)
- ⚠️ Rate limiting para API
- ⚠️ Logs de auditoria
- ⚠️ Backup automático do banco

---

## 13. TESTES

### 13.1. Status Atual
- ❌ Sem testes unitários específicos
- ❌ Sem testes de integração
- ✅ Estrutura de testes Laravel presente

### 13.2. Testes Recomendados
- Testes de validação de regras
- Testes de algoritmo de distribuição
- Testes de relacionamentos
- Testes de balanceamento

---

## 14. DOCUMENTAÇÃO

### 14.1. Status
- ✅ Código comentado parcialmente
- ❌ Sem documentação de API
- ❌ Sem guia de usuário
- ✅ README básico (Laravel padrão)

### 14.2. Recomendações
- Documentar regras de negócio
- Criar guia de uso
- Documentar algoritmo de distribuição
- Adicionar exemplos de uso

---

## 15. MELHORIAS FUTURAS

### 15.1. Prioridade Alta
1. **Corrigir bugs críticos** (B1-B6)
2. **Implementar balanceamento físico** na distribuição
3. **Melhorar algoritmo de distribuição** (múltiplas passadas)
4. **Validação completa de tribos**

### 15.2. Prioridade Média
1. **Autenticação e autorização**
2. **Histórico de distribuições**
3. **Exportação de relatórios** (PDF/Excel)
4. **Gráficos de balanceamento**

### 15.3. Prioridade Baixa
1. **API REST completa**
2. **Notificações em tempo real**
3. **Sistema de backup automático**
4. **Interface mobile responsiva aprimorada**

---

## 16. CONCLUSÃO

### 16.1. Estado Atual
O sistema possui uma **base sólida** com:
- ✅ Estrutura bem organizada
- ✅ Interface funcional e intuitiva
- ✅ Regras de negócio parcialmente implementadas
- ✅ Validação em tempo real de violações

### 16.2. Principais Desafios
- ⚠️ Algoritmo de distribuição precisa ser melhorado
- ⚠️ Balanceamento físico não está implementado
- ⚠️ Alguns bugs críticos precisam ser corrigidos

### 16.3. Viabilidade
O sistema é **totalmente viável** e pode ser aprimorado com:
- Algoritmo de múltiplas passadas (recomendado)
- Função de score para balanceamento
- Validação completa de todas as regras

### 16.4. Próximos Passos Recomendados
1. Corrigir bugs críticos identificados
2. Implementar algoritmo de distribuição melhorado
3. Adicionar validação de balanceamento físico
4. Testar com dados reais
5. Refinar baseado em feedback

---

**Documento gerado automaticamente pela análise do código-fonte**  
**Última atualização:** 2024

