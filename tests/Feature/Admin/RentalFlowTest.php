<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RentalFlowTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;
    private Product $product;
    private ProductUnit $unit;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->product = Product::factory()->create([
            'is_active' => true,
            'rental_daily_rate' => 50000,
        ]);
        $this->unit = ProductUnit::factory()->create([
            'product_id' => $this->product->id,
            'status' => 'available',
        ]);
    }

    public function test_guest_can_create_rental_request_with_private_identity_file(): void
    {
        $response = $this->post(route('rentals.store'), [
            'product_id' => $this->product->id,
            'customer_name' => 'Rental Customer',
            'customer_phone' => '081234567890',
            'customer_address' => 'Jl. Test No. 1',
            'purpose' => 'Kebutuhan pekerjaan',
            'start_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(3)->format('Y-m-d'),
            'rental_period_type' => 'daily',
            'identity_document' => UploadedFile::fake()->create('identity.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect();
        $rental = Rental::latest('id')->firstOrFail();

        $this->assertSame('menunggu', $rental->status->value);
        $this->assertSame(150000.0, (float) $rental->estimated_amount);
        Storage::disk('local')->assertExists($rental->identity_document_path);
    }

    public function test_rental_approval_handover_and_return_update_inventory(): void
    {
        $rental = Rental::factory()->create([
            'product_id' => $this->product->id,
            'product_unit_id' => null,
            'status' => 'menunggu',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.rentals.approve', $rental), [
                'product_unit_id' => $this->unit->id,
                'deposit_amount' => 100000,
            ])
            ->assertSessionHas('success');

        $this->assertSame('disetujui', $rental->fresh()->status->value);
        $this->assertSame('reserved', $this->unit->fresh()->status->value);

        $this->actingAs($this->admin)
            ->post(route('admin.rentals.handover', $rental), [
                'condition_notes' => 'Unit baik',
                'accessories' => ['charger'],
            ])
            ->assertSessionHas('success');

        $this->assertSame('dipinjam', $rental->fresh()->status->value);
        $this->assertSame('rented', $this->unit->fresh()->status->value);

        $this->actingAs($this->admin)
            ->post(route('admin.rentals.return', $rental), [
                'condition_notes' => 'Unit kembali baik',
                'fine_amount' => 0,
            ])
            ->assertSessionHas('success');

        $this->assertSame('selesai', $rental->fresh()->status->value);
        $this->assertSame('available', $this->unit->fresh()->status->value);
    }

    public function test_rental_payment_proof_requires_valid_public_token(): void
    {
        $token = 'rental-public-token';
        $rental = Rental::factory()->create([
            'product_id' => $this->product->id,
            'public_token_hash' => hash('sha256', $token),
        ]);

        $this->post(route('rentals.payment.submit', ['rental' => $rental, 'token' => 'wrong-token']), [
            'amount' => 50000,
            'payment_method' => 'transfer',
            'proof' => UploadedFile::fake()->create('proof.pdf', 100, 'application/pdf'),
        ])->assertNotFound();

        $this->post(route('rentals.payment.submit', ['rental' => $rental, 'token' => $token]), [
            'amount' => 50000,
            'payment_method' => 'transfer',
            'proof' => UploadedFile::fake()->create('proof.pdf', 100, 'application/pdf'),
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'payable_type' => Rental::class,
            'payable_id' => $rental->id,
            'payment_type' => 'rental',
            'status' => 'pending',
        ]);
    }
}
