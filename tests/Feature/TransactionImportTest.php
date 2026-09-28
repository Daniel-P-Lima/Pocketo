<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TransactionImportTest extends TestCase
{
    use RefreshDatabase;

    private const CSV = <<<CSV
        date,title,amount
        2026-09-28,Auto Posto Curitibano,"140,41"
        2026-09-12,Blacksnookerbar,"22,00"
        2026-09-12,Blacksnookerbar,"22,00"
        2026-09-04,Pagamento recebido,"- 1.196,52"
        CSV;

    private function upload(string $content = self::CSV, ?int $bankId = null, ?string $referenceMonth = null, string $filename = 'Nubank_2026-10-14.csv')
    {
        return $this->post(route('transactions.import.upload'), [
            'file' => UploadedFile::fake()->createWithContent($filename, $content),
            'bank_id' => $bankId,
            'reference_month' => $referenceMonth,
        ]);
    }

    public function test_upload_takes_reference_month_from_filename(): void
    {
        $this->upload();

        $this->get(route('transactions.import.preview'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('referenceMonth', '2026-10')
                ->where('rows.0.reference_month', '2026-10')
                ->where('rows.3.reference_month', '2026-10')
            );
    }

    public function test_upload_prefers_informed_reference_month(): void
    {
        $this->upload(referenceMonth: '2026-11');

        $this->get(route('transactions.import.preview'))
            ->assertInertia(fn (Assert $page) => $page->where('rows.0.reference_month', '2026-11'));
    }

    public function test_upload_requires_reference_month_when_filename_has_no_date(): void
    {
        $this->upload(filename: 'fatura.csv')->assertSessionHasErrors('reference_month');
    }

    public function test_create_preselects_nubank(): void
    {
        $nubank = Bank::factory()->create(['name' => 'Nubank']);

        $this->get(route('transactions.import.create'))
            ->assertInertia(fn (Assert $page) => $page
                ->has('banks', 1)
                ->where('defaultBankId', $nubank->id)
            );
    }

    public function test_upload_applies_chosen_bank_to_every_row(): void
    {
        $bank = Bank::factory()->create();

        $this->upload(bankId: $bank->id);

        $this->get(route('transactions.import.preview'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('bank.id', $bank->id)
                ->where('rows.0.bank_id', $bank->id)
                ->where('rows.3.bank_id', $bank->id)
            );
    }

    // ---------------------------------------------------------------
    // create
    // ---------------------------------------------------------------

    public function test_create_returns_ok(): void
    {
        $response = $this->get(route('transactions.import.create'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Transactions/Import')
            ->where('backUrl', route('transactions.index'))
        );
    }

    // ---------------------------------------------------------------
    // upload / preview
    // ---------------------------------------------------------------

    public function test_upload_parses_csv_and_redirects_to_preview(): void
    {
        $this->upload()->assertRedirect(route('transactions.import.preview'));

        $this->get(route('transactions.import.preview'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Transactions/ImportPreview')
                ->has('rows', 4)
                ->where('rows.0.date', '2026-09-28')
                ->where('rows.0.description', 'Auto Posto Curitibano')
                ->where('rows.0.amount', 140.41)
                ->where('rows.0.type', 'expense')
                ->where('rows.0.selected', true)
                ->where('rows.3.amount', 1196.52)
                ->where('rows.3.type', 'income')
                ->where('rows.3.selected', false)
            );
    }

    public function test_upload_rejects_invalid_header(): void
    {
        $this->upload("data,descricao,valor\n2026-09-28,Teste,\"10,00\"")
            ->assertSessionHasErrors('file');
    }

    public function test_upload_rejects_invalid_date(): void
    {
        $this->upload("date,title,amount\n28/09/2026,Teste,\"10,00\"")
            ->assertSessionHasErrors('file');
    }

    public function test_upload_requires_file(): void
    {
        $this->post(route('transactions.import.upload'))->assertSessionHasErrors('file');
    }

    public function test_preview_without_upload_redirects_to_create(): void
    {
        $this->get(route('transactions.import.preview'))
            ->assertRedirect(route('transactions.import.create'));
    }

    public function test_preview_suggests_category_from_previous_transaction(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);
        Transaction::factory()->create([
            'category_id' => $category->id,
            'type' => 'expense',
            'description' => 'Auto Posto Curitibano',
            'amount' => 50,
            'date' => '2026-08-10',
        ]);

        $this->upload();

        $this->get(route('transactions.import.preview'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('rows.0.category_id', $category->id)
            );
    }

    public function test_preview_marks_only_already_imported_rows_as_duplicate(): void
    {
        Transaction::factory()->create([
            'type' => 'expense',
            'description' => 'Blacksnookerbar',
            'amount' => 22,
            'date' => '2026-09-12',
        ]);

        $this->upload();

        // Duas compras iguais no CSV e uma já no banco: só a primeira é duplicada
        $this->get(route('transactions.import.preview'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('rows.1.duplicate', true)
                ->where('rows.1.selected', false)
                ->where('rows.2.duplicate', false)
                ->where('rows.2.selected', true)
            );
    }

    // ---------------------------------------------------------------
    // store
    // ---------------------------------------------------------------

    public function test_store_creates_transactions_and_redirects(): void
    {
        $category = Category::factory()->create(['type' => 'both']);
        $bank = Bank::factory()->create();
        $this->upload();

        $response = $this->post(route('transactions.import.store'), [
            'rows' => [
                ['date' => '2026-09-28', 'description' => 'Auto Posto Curitibano', 'amount' => 140.41, 'type' => 'expense', 'category_id' => $category->id, 'bank_id' => $bank->id, 'reference_month' => '2026-10'],
                ['date' => '2026-09-12', 'description' => 'Blacksnookerbar', 'amount' => 22, 'type' => 'expense', 'category_id' => $category->id, 'reference_month' => '2026-10'],
            ],
        ]);

        // Vai para o mês da fatura, não para o mês das compras
        $response->assertRedirect(route('transactions.index', ['month' => 10, 'year' => 2026]));
        $this->assertDatabaseHas('transactions', ['description' => 'Blacksnookerbar', 'date' => '2026-09-12', 'reference_month' => '2026-10-01']);
        $response->assertSessionMissing('transaction_import');
        $this->assertDatabaseCount('transactions', 2);
        $this->assertDatabaseHas('transactions', ['description' => 'Auto Posto Curitibano', 'amount' => 140.41, 'bank_id' => $bank->id]);
        $this->assertDatabaseHas('transactions', ['description' => 'Blacksnookerbar', 'bank_id' => null]);
    }

    public function test_store_requires_category_for_every_row(): void
    {
        $response = $this->post(route('transactions.import.store'), [
            'rows' => [
                ['date' => '2026-09-28', 'description' => 'Auto Posto Curitibano', 'amount' => 140.41, 'type' => 'expense', 'category_id' => null, 'reference_month' => '2026-10'],
            ],
        ]);

        $response->assertSessionHasErrors('rows.0.category_id');
        $this->assertDatabaseCount('transactions', 0);
    }
}
