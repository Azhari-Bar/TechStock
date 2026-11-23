<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExportReportTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    public function test_admin_can_access_report_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.reports.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_export_rental_report(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.reports.export.rentals'));

        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition');
    }

    public function test_admin_can_export_product_catalog(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.reports.export.products'));

        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition');
    }

    public function test_all_reports_support_year_month_week_filters(): void
    {
        foreach (['export.rentals', 'export.products', 'export.units', 'export.stock-movements'] as $name) {
            foreach (['year', 'month', 'week'] as $period) {
                $this->actingAs($this->user)
                    ->get(route("admin.reports.$name", ['period' => $period, 'date' => now()->toDateString()]))
                    ->assertOk()
                    ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            }
        }
    }
}
