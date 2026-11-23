<?php

namespace App\Models;

use App\Enums\RentalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'public_token_hash',
        'product_id',
        'product_unit_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'purpose',
        'identity_document_path',
        'start_date',
        'due_date',
        'returned_at',
        'rental_period_type',
        'rental_rate_snapshot',
        'estimated_amount',
        'deposit_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => RentalStatus::class,
            'start_date' => 'date',
            'due_date' => 'date',
            'returned_at' => 'datetime',
            'rental_rate_snapshot' => 'decimal:2',
            'estimated_amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    public function handoverChecklist(): HasOne
    {
        return $this->hasOne(RentalHandoverChecklist::class);
    }

    public function returnChecklist(): HasOne
    {
        return $this->hasOne(RentalReturnChecklist::class);
    }

    public function extensions(): HasMany
    {
        return $this->hasMany(RentalExtension::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function isOverdue(): bool
    {
        return ! $this->returned_at
            && $this->due_date
            && $this->due_date->isPast()
            && ! $this->status->isFinal();
    }

    public function daysLate(): int
    {
        if (! $this->due_date || ! $this->due_date->isPast()) {
            return 0;
        }

        $end = $this->returned_at ?? now();

        return max(0, $this->due_date->diffInDays($end));
    }
}
