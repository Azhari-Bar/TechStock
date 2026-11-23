<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardVisualTest extends TestCase
{
    use DatabaseTransactions;

    public function test_dashboard_renders_with_charts(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('unitStatusChart', false);
        $response->assertSee('rentalTrendChart', false);
        $response->assertSee('categoryChart', false);
    }

    public function test_reports_page_renders_export_buttons(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Unduh Laporan Inventaris');
        $response->assertSee('Unduh Laporan Peminjaman');
        $response->assertSee('Unduh Laporan Stok');
        $response->assertSee('Unduh Laporan Unit');
    }
}