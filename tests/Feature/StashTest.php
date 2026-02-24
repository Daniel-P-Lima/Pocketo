<?php

namespace Tests\Feature;

use App\Models\Stash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class StashTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_index_returns_ok(): void
    {
        $response = $this->get(route('stash.index'));

        $response->assertInertia(fn(Assert $page) => $page
            ->component('Stash/Index')
            ->where('header', 'Caixinhas')
            ->has('stashes')
        );
    }

    public function test_create_returns_ok(): void
    {
        $response = $this->get(route('stash.create'));
        $response->assertInertia(fn(Assert $page) => $page
            ->component('Stash/Create')
            ->where('header', 'Nova Caixinha')
            ->where('backUrl', route('stash.index'))
        );
    }

    public function test_store_returns_ok(): void
    {
        $stash = Stash::factory()->create();

        $response = $this->post(route('stash.store'), [
            'name'          => $stash->name,
            'amount'        => $stash->amount,
            'goal_amount'   => $stash->goal_amount,
            'purpose'       => $stash->purpose,
        ]);

        $response->assertRedirect(route('stash.index'));
        $this->assertDatabaseHas('stashes', [
            'name'          => $stash->name,
            'amount'        => $stash->amount,
            'goal_amount'   => $stash->goal_amount,
            'purpose'       => $stash->purpose,
        ]);
    }

    public function test_show_returns_ok(): void
    {
        $stash = Stash::factory()->create();
        $response = $this->get(route('stash.show', $stash));

        $response->assertInertia(fn(Assert $page) => $page
            ->component('Stash/Show')
            ->where('header', 'Detalhes ' . $stash->name)
            ->where('backUrl', route('stash.index'))
        );
    }
}
