<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    // ---------------------------------------------------------------
    // index
    // ---------------------------------------------------------------

    public function test_index_returns_ok(): void
    {
        $response = $this->get(route('transactions.index'));

        $response->assertStatus(200);
    }

    public function test_index_renders_correct_inertia_component(): void
    {
        $response = $this->get(route('transactions.index'));

        $response->assertInertia(fn ($page) =>
            $page->component('Transactions/Index')
                 ->has('transactions')
                 ->has('totalIncome')
                 ->has('totalExpense')
        );
    }

    public function test_index_lists_transactions_of_current_month(): void
    {
        $current  = Transaction::factory()->create(['date' => now()]);
        $old      = Transaction::factory()->create(['date' => now()->subYear()]);

        $response = $this->get(route('transactions.index'));

        $response->assertInertia(fn ($page) =>
            $page->component('Transactions/Index')
                 ->has('transactions', 1)
                 ->where('transactions.0.id', $current->id)
        );
    }

    public function test_reference_month_defaults_to_date_month(): void
    {
        $transaction = Transaction::factory()->create(['date' => '2026-09-03']);

        $this->assertSame('2026-09', $transaction->fresh()->toArray()['reference_month']);
        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'reference_month' => '2026-09-01']);
    }

    public function test_index_uses_reference_month_instead_of_date(): void
    {
        $cardPurchase = Transaction::factory()->create([
            'date' => '2026-09-03', 'reference_month' => '2026-10', 'type' => 'expense', 'amount' => 80,
        ]);

        $this->get(route('transactions.index', ['month' => 9, 'year' => 2026]))
            ->assertInertia(fn ($page) => $page->has('transactions', 0)->where('totalExpense', 0));

        $this->get(route('transactions.index', ['month' => 10, 'year' => 2026]))
            ->assertInertia(fn ($page) => $page
                ->has('transactions', 1)
                ->where('transactions.0.id', $cardPurchase->id)
                ->where('totalExpense', '80.00')
            );
    }

    public function test_budget_spent_uses_reference_month(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);
        Transaction::factory()->create([
            'category_id' => $category->id, 'type' => 'expense', 'amount' => 120,
            'date' => '2026-09-20', 'reference_month' => '2026-10',
        ]);

        $september = Budget::create(['category_id' => $category->id, 'amount' => 500, 'month' => 9, 'year' => 2026]);
        $october = Budget::create(['category_id' => $category->id, 'amount' => 500, 'month' => 10, 'year' => 2026]);

        $this->assertEquals(0, $september->spent);
        $this->assertEquals(120, $october->spent);
    }

    public function test_budget_keeps_cents(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);
        Transaction::factory()->create([
            'category_id' => $category->id, 'type' => 'expense', 'amount' => 120.90, 'date' => '2026-10-02',
        ]);

        $budget = Budget::create(['category_id' => $category->id, 'amount' => 200.50, 'month' => 10, 'year' => 2026]);

        $this->assertSame(120.90, $budget->spent);
        $this->assertSame(79.60, $budget->remaining);
        $this->assertSame(60.3, $budget->spent_percentage);
        $this->assertSame('R$ 120,90', $budget->category->transactions()->first()->formatted_amount);
    }

    public function test_budget_with_zero_amount_has_zero_percentage(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);

        $budget = Budget::create(['category_id' => $category->id, 'amount' => 0, 'month' => 10, 'year' => 2026]);

        $this->assertEquals(0, $budget->spent_percentage);
    }

    public function test_update_without_reference_month_follows_new_date(): void
    {
        $transaction = Transaction::factory()->create(['date' => '2026-09-03']);

        $this->put(route('transactions.update', $transaction), [
            'category_id' => $transaction->category_id,
            'type'        => $transaction->type,
            'amount'      => 10,
            'description' => 'Movida',
            'date'        => '2026-11-15',
        ]);

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'reference_month' => '2026-11-01']);
    }

    public function test_store_rejects_invalid_reference_month(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);

        $this->post(route('transactions.store'), [
            'category_id'     => $category->id,
            'type'            => 'expense',
            'amount'          => 30,
            'description'     => 'Padaria',
            'date'            => now()->toDateString(),
            'reference_month' => '10/2026',
        ])->assertSessionHasErrors('reference_month');
    }

    public function test_index_filters_transactions_and_totals_by_bank(): void
    {
        $nubank = Bank::factory()->create();
        $itau = Bank::factory()->create();
        $fromNubank = Transaction::factory()->create(['date' => now(), 'bank_id' => $nubank->id, 'type' => 'expense', 'amount' => 100]);
        Transaction::factory()->create(['date' => now(), 'bank_id' => $itau->id, 'type' => 'expense', 'amount' => 50]);

        $response = $this->get(route('transactions.index', ['bank' => $nubank->id]));

        $response->assertInertia(fn ($page) =>
            $page->has('transactions', 1)
                 ->where('transactions.0.id', $fromNubank->id)
                 ->where('transactions.0.bank.id', $nubank->id)
                 ->where('bank', $nubank->id)
                 ->where('totalExpense', '100.00')
        );
    }

    // ---------------------------------------------------------------
    // create
    // ---------------------------------------------------------------

    public function test_create_returns_ok(): void
    {
        $response = $this->get(route('transactions.create'));

        $response->assertStatus(200);
    }

    // ---------------------------------------------------------------
    // store
    // ---------------------------------------------------------------

    public function test_store_creates_transaction_and_redirects(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);

        $response = $this->post(route('transactions.store'), [
            'category_id' => $category->id,
            'type'        => 'expense',
            'amount'      => 5000,
            'description' => 'Conta de luz',
            'notes'       => null,
            'date'        => now()->toDateString(),
        ]);

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseHas('transactions', ['amount' => 5000, 'category_id' => $category->id, 'description' => 'Conta de luz']);
    }

    public function test_store_saves_bank(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);
        $bank = Bank::factory()->create();

        $this->post(route('transactions.store'), [
            'category_id' => $category->id,
            'bank_id'     => $bank->id,
            'type'        => 'expense',
            'amount'      => 30,
            'description' => 'Padaria',
            'date'        => now()->toDateString(),
        ])->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', ['description' => 'Padaria', 'bank_id' => $bank->id]);
    }

    public function test_store_rejects_unknown_bank(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);

        $this->post(route('transactions.store'), [
            'category_id' => $category->id,
            'bank_id'     => 999,
            'type'        => 'expense',
            'amount'      => 30,
            'description' => 'Padaria',
            'date'        => now()->toDateString(),
        ])->assertSessionHasErrors('bank_id');
    }

    public function test_store_requires_description(): void
    {
        $category = Category::factory()->create();

        $response = $this->post(route('transactions.store'), [
            'category_id' => $category->id,
            'type'        => 'expense',
            'amount'      => 5000,
            'date'        => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('description');
    }

    public function test_store_requires_valid_type(): void
    {
        $category = Category::factory()->create();

        $response = $this->post(route('transactions.store'), [
            'category_id' => $category->id,
            'type'        => 'invalid',
            'amount'      => 5000,
            'description' => 'Teste',
            'date'        => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('type');
    }

    // ---------------------------------------------------------------
    // show
    // ---------------------------------------------------------------

    public function test_show_returns_ok(): void
    {
        $transaction = Transaction::factory()->create();

        $response = $this->get(route('transactions.show', $transaction));

        $response->assertStatus(200);
    }

    // ---------------------------------------------------------------
    // update
    // ---------------------------------------------------------------

    public function test_update_changes_transaction_and_redirects(): void
    {
        $transaction = Transaction::factory()->create(['description' => 'Original']);
        $category    = Category::factory()->create();

        $response = $this->put(route('transactions.update', $transaction), [
            'category_id' => $category->id,
            'type'        => 'income',
            'amount'      => 1000,
            'description' => 'Atualizado',
            'notes'       => null,
            'date'        => now()->toDateString(),
        ]);

        $response->assertRedirect(route('transactions.show', $transaction));
        $this->assertDatabaseHas('transactions', ['description' => 'Atualizado']);
        $this->assertDatabaseMissing('transactions', ['description' => 'Original']);
    }

    // ---------------------------------------------------------------
    // destroy
    // ---------------------------------------------------------------

    public function test_destroy_deletes_transaction_and_redirects(): void
    {
        $transaction = Transaction::factory()->create();

        $response = $this->delete(route('transactions.destroy', $transaction));

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }
}
