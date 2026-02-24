# Pocketo

Um app web de finanças pessoais para acompanhar receitas, despesas, orçamentos e metas de economia.

> 🇺🇸 [Read in English](README.md)

## Funcionalidades

- **Dashboard** — Saldo mensal, gráfico de receitas vs. despesas (últimos 6 meses) e resumo por categoria
- **Transações** — Cadastre, edite e exclua lançamentos de receita ou despesa com categorias, notas e filtro por data
- **Orçamentos** — Limite de gastos mensal por categoria com alerta ao atingir 80%+
- **Categorias** — Categorias personalizadas com ícones e cores
- **Caixinhas** — Metas de economia com valor alvo e acompanhamento de progresso

## Stack

| Camada | Tecnologia |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Vue 3, Inertia.js 2, Tailwind CSS 4 |
| Banco de dados | MySQL 8.4 |
| Build | Vite 7 |
| Gráficos | Chart.js 4 |
| Ambiente dev | Docker / Laravel Sail |

## Como rodar

**1. Clone o repositório e instale as dependências:**

```bash
git clone <repo-url>
cd MoneyManagement
composer install
```

**2. Configure o arquivo de ambiente:**

```bash
cp .env.example .env
php artisan key:generate
```

**3. Suba os containers Docker:**

```bash
./vendor/bin/sail up -d
```

> Dica: adicione `alias sail="./vendor/bin/sail"` no seu arquivo de perfil do shell e use só `sail up -d`.

**4. Rode as migrations e os seeders:**

```bash
sail artisan migrate
sail artisan db:seed
```

**5. Instale as dependências do frontend e inicie o servidor de desenvolvimento:**

```bash
npm install
npm run dev
```

A aplicação estará disponível em [http://localhost](http://localhost).

## Comandos úteis

| Comando | Descrição |
|---|---|
| `composer setup` | Configuração completa inicial |
| `composer dev` | Inicia todos os processos de dev juntos (servidor + fila + Vite) |
| `composer test` | Executa a suíte de testes |
| `npm run dev` | Inicia o servidor Vite |
| `npm run build` | Gera os assets para produção |
