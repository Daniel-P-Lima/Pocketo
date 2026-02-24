# Pocketo

A personal finance web app to track income, expenses, budgets, and savings goals.

> 🇧🇷 [Leia em Português](README.pt-BR.md)

## Features

- **Dashboard** — Monthly balance, income vs. expense chart (last 6 months), and category breakdown
- **Transactions** — Add, edit, and delete income/expense entries with categories, notes, and date filters
- **Budgets** — Monthly budget per category with alerts when spending reaches 80%+
- **Categories** — Custom categories with icons and colors
- **Stash** — Savings goals with target amounts and progress tracking

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Vue 3, Inertia.js 2, Tailwind CSS 4 |
| Database | MySQL 8.4 |
| Build | Vite 7 |
| Charts | Chart.js 4 |
| Dev Environment | Docker / Laravel Sail |

## Getting Started

**1. Clone the repository and install dependencies:**

```bash
git clone <repo-url>
cd MoneyManagement
composer install
```

**2. Set up your environment file:**

```bash
cp .env.example .env
php artisan key:generate
```

**3. Start Docker:**

```bash
./vendor/bin/sail up -d
```

> Tip: Add `alias sail="./vendor/bin/sail"` to your shell profile so you can just type `sail up -d`.

**4. Run migrations and seed the database:**

```bash
sail artisan migrate
sail artisan db:seed
```

**5. Install frontend dependencies and start the dev server:**

```bash
npm install
npm run dev
```

The app will be available at [http://localhost](http://localhost).

## Useful Commands

| Command | Description |
|---|---|
| `composer setup` | First-time full setup |
| `composer dev` | Start all dev processes at once (server + queue + Vite) |
| `composer test` | Run the test suite |
| `npm run dev` | Start Vite dev server |
| `npm run build` | Build for production |
