<?php

namespace Tests\Feature;

use App\Models\Icon;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_index_returns_ok(): void
    {
        $response = $this->get(route('categories.index'));
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Categories/Index')
            ->where('header', 'Categorias')
            ->has('expenseCategories')
            ->has('incomeCategories')
        );

    }

    public function test_render_create_returns_ok(): void
    {
        $response = $this->get(route('categories.create'));
        $response->assertOk();
    }

    public function test_store_category(): void
    {
        $icon = Icon::factory()->create();

        $response = $this->post(route('categories.store'), [
            'name'          => 'Livros',
            'icon_id'       => $icon->id,
            'color'         => 'green',
            'type'          => 'expense',
            'is_default'    => false
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Livros', 'color' => 'green', 'type' => 'expense']);
    }

    public function test_render_edit_category(): void
    {
        $category = Category::factory()->create();
        $response = $this->get(route('categories.edit', $category));
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Categories/Edit')
            ->where('header', 'Editar Categoria')
            ->where('category', $category)
            ->where('backUrl', route('categories.index'))
            ->has('icons')
        );
    }

    public function test_update_category_returns_ok(): void
    {
        $category = Category::factory()->create();
        $icon = Icon::factory()->create();
        $response = $this->put(route('categories.update', $category), [
            'name'          => 'Computação',
            'icon_id'       => $icon->id,
            'color'         => 'green',
            'type'          => 'expense',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Computação', 'color' => 'green', 'type' => 'expense']);

    }

    public function test_update_category_return_nok(): void
    {
        $category = Category::factory()->create();
        $response = $this->put(route('categories.update', $category), [
            'name'          => 123,
            'icon_id'       => 456,
            'color'         => 'green',
            'type'          => 'outro',
        ]);

        $response->assertSessionHas('errors');
    }

    public function test_destroy_category_returns_ok(): void
    {
        $category = Category::factory()->create();
        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id, 'name' => $category->name]);
    }
}
