<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_trend_ends_at_selected_month(): void
    {
        Transaction::factory()->create(['type' => 'income', 'amount' => 300, 'date' => '2025-10-05']);
        Transaction::factory()->create(['type' => 'income', 'amount' => 700, 'date' => '2026-03-05']);
        Transaction::factory()->create(['type' => 'income', 'amount' => 999, 'date' => '2026-04-05']);

        $this->get(route('dashboard', ['month' => 3, 'year' => 2026]))
            ->assertInertia(fn (Assert $page) => $page
                ->has('trendData.labels', 6)
                ->where('trendData.income.0', '300.00') // out/2025
                ->where('trendData.income.5', '700.00') // mar/2026, abril fica de fora
            );
    }

    public function test_spending_trend_endpoint_ends_at_selected_month(): void
    {
        Transaction::factory()->create(['type' => 'expense', 'amount' => 45.5, 'date' => '2026-03-10']);

        $response = $this->getJson(route('api.dashboard.trend', ['month' => 3, 'year' => 2026]));

        $response->assertJsonCount(6)->assertJsonPath('5.expense', '45.50');
    }
}
