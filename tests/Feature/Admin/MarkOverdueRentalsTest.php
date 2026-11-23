<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Rental;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MarkOverdueRentalsTest extends TestCase
{
    use DatabaseTransactions;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create(['is_active' => true]);
    }

    private function makeRental(string $status, string $dueDate): Rental
    {
        $unit = ProductUnit::factory()->create([
            'product_id' => $this->product->id,
            'status' => 'rented',
        ]);

        return Rental::factory()->create([
            'product_id' => $this->product->id,
            'product_unit_id' => $unit->id,
            'status' => $status,
            'due_date' => $dueDate,
            'returned_at' => null,
        ]);
    }

    public function test_command_marks_active_overdue_rentals_as_terlambat(): void
    {
        $overdue = $this->makeRental('dipinjam', now()->subDays(3)->format('Y-m-d'));
        $notYet = $this->makeRental('dipinjam', now()->addDays(3)->format('Y-m-d'));
        $finished = $this->makeRental('selesai', now()->subDays(10)->format('Y-m-d'));

        $this->artisan('rentals:mark-overdue')->assertSuccessful();

        $this->assertSame('terlambat', $overdue->fresh()->status->value);
        $this->assertSame('dipinjam', $notYet->fresh()->status->value);
        $this->assertSame('selesai', $finished->fresh()->status->value);
    }

    public function test_dry_run_does_not_change_status(): void
    {
        $overdue = $this->makeRental('dipinjam', now()->subDays(3)->format('Y-m-d'));

        $this->artisan('rentals:mark-overdue', ['--dry-run' => true])->assertSuccessful();

        $this->assertSame('dipinjam', $overdue->fresh()->status->value);
    }
}
