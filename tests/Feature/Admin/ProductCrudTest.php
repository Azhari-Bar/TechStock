<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $category;
    protected $brand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
        $this->category = \App\Models\Category::factory()->create();
        $this->brand = \App\Models\Brand::factory()->create();
    }

    public function test_admin_can_access_index(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index'));

        $response->assertStatus(200);
    }

    public function test_create_product_success(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'category_id' => $this->category->id,
                'brand_id' => $this->brand->id,
                'name' => 'Test Laptop',
                'model' => 'Model X',
                'condition' => 'new',
                'processor' => 'Intel i7',
                'ram_gb' => 16,
                'storage_capacity' => 512,
                'storage_type' => 'SSD',
                'gpu' => 'NVIDIA',
                'screen_size' => 15.6,
                'operating_system' => 'Windows 11',
                'purchase_price' => 10000000,
                'selling_price' => 15000000,
                'rental_daily_rate' => 100000,
                'minimum_stock' => 5,
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', ['name' => 'Test Laptop', 'model' => 'Model X']);
    }

    public function test_create_product_validation_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'category_id' => $this->category->id,
            ]);

        $response->assertSessionHasErrors(['name', 'condition']);
    }

    public function test_edit_product(): void
    {
        $product = \App\Models\Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Old Product',
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.products.update', $product), [
                'category_id' => $this->category->id,
                'brand_id' => $this->brand->id,
                'name' => 'Updated Product',
                'model' => 'Model Y',
                'condition' => 'new',
                'selling_price' => 20000000,
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Product']);
    }

    public function test_delete_product(): void
    {
        $product = \App\Models\Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($product);
    }
}
