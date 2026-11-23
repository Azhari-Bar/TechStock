<?php

namespace App\Models;

use App\Enums\DepositStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'amount',
        'status',
        'returned_amount',
        'deducted_amount',
        'returned_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => DepositStatus::class,
            'amount' => 'decimal:2',
            'returned_amount' => 'decimal:2',
            'deducted_amount' => 'decimal:2',
            'returned_at' => 'datetime',
        ];
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }
}
