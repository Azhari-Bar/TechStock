<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_forbidden(): void
    {
        $user = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $response = $this->actingAs($user)->get(route('admin.categories.index'));
        $response->assertForbidden();
    }

    public function test_admin_can_access_index(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.categories.index'));

        $response->assertStatus(200);
    }

    public function test_create_category_success(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Laptop Gaming',
                'type' => 'laptop',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['name' => 'Laptop Gaming', 'type' => 'laptop']);
    }

    public function test_create_category_validation_fails_without_name(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [
                'type' => 'laptop',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_edit_category(): void
    {
        $category = \App\Models\Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'New Name',
                'type' => 'desktop',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Name']);
    }

    public function test_delete_category(): void
    {
        $category = \App\Models\Category::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($category);
    }

    public function test_show_category(): void
    {
        $category = \App\Models\Category::factory()->create(['name' => 'Test Category']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.categories.show', $category));

        $response->assertStatus(200);
    }
}
