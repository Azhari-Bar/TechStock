<?php

namespace Tests\Feature\Catalog;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PublicCatalogTest extends TestCase
{
    use DatabaseTransactions;

    public function test_catalog_index_returns_200(): void
    {
        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
    }

    public function test_product_show_returns_200(): void
    {
        $product = Product::factory()->create(['is_active' => true]);
        $response = $this->get(route('catalog.show', $product));
        $response->assertStatus(200);
    }

    public function test_product_show_shows_whatsapp_button(): void
    {
        $product = Product::factory()->create(['is_active' => true]);
        $response = $this->get(route('catalog.show', $product));
        $response->assertStatus(200);
        $response->assertSee('Hubungi Petugas tentang Ketersediaan');
    }
}