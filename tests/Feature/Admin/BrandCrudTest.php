<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BrandCrudTest extends TestCase
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

    public function test_admin_can_access_index(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.brands.index'));

        $response->assertStatus(200);
    }

    public function test_create_brand_success(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.brands.store'), [
                'name' => 'Test Brand',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.brands.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('brands', ['name' => 'Test Brand']);
    }

    public function test_create_brand_validation_fails_without_name(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.brands.store'), []);

        $response->assertSessionHasErrors('name');
    }

    public function test_edit_brand(): void
    {
        $brand = \App\Models\Brand::factory()->create(['name' => 'Old Brand']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.brands.update', $brand), [
                'name' => 'New Brand',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.brands.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('brands', ['id' => $brand->id, 'name' => 'New Brand']);
    }

    public function test_delete_brand(): void
    {
        $brand = \App\Models\Brand::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.brands.destroy', $brand));

        $response->assertRedirect(route('admin.brands.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($brand);
    }
}
