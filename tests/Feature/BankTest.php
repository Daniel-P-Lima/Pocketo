<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BankTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_banks_with_transaction_count(): void
    {
        $bank = Bank::factory()->create();
        Transaction::factory()->count(2)->create(['bank_id' => $bank->id]);

        $this->get(route('banks.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Banks/Index')
                ->where('header', 'Bancos')
                ->has('banks', 1)
                ->where('banks.0.transactions_count', 2)
            );
    }

    public function test_store_creates_bank(): void
    {
        $response = $this->post(route('banks.store'), ['name' => 'Nubank', 'color' => '#820AD1']);

        $response->assertRedirect(route('banks.index'));
        $this->assertDatabaseHas('banks', ['name' => 'Nubank', 'color' => '#820AD1']);
    }

    public function test_store_rejects_duplicate_name_and_invalid_color(): void
    {
        Bank::factory()->create(['name' => 'Nubank']);

        $this->post(route('banks.store'), ['name' => 'Nubank', 'color' => 'roxo'])
            ->assertSessionHasErrors(['name', 'color']);
    }

    public function test_update_changes_bank(): void
    {
        $bank = Bank::factory()->create(['name' => 'Itau']);

        $this->put(route('banks.update', $bank), ['name' => 'Itaú', 'color' => '#EC7000'])
            ->assertRedirect(route('banks.index'));

        $this->assertDatabaseHas('banks', ['id' => $bank->id, 'name' => 'Itaú']);
    }

    public function test_update_allows_keeping_same_name(): void
    {
        $bank = Bank::factory()->create(['name' => 'Inter']);

        $this->put(route('banks.update', $bank), ['name' => 'Inter', 'color' => '#FF7A00'])
            ->assertSessionHasNoErrors();
    }

    public function test_destroy_deletes_bank_without_transactions(): void
    {
        $bank = Bank::factory()->create();

        $this->delete(route('banks.destroy', $bank))->assertRedirect(route('banks.index'));

        $this->assertDatabaseMissing('banks', ['id' => $bank->id]);
    }

    public function test_destroy_keeps_bank_with_transactions(): void
    {
        $bank = Bank::factory()->create();
        Transaction::factory()->create(['bank_id' => $bank->id]);

        $this->delete(route('banks.destroy', $bank))->assertSessionHas('error');

        $this->assertDatabaseHas('banks', ['id' => $bank->id]);
    }
}
