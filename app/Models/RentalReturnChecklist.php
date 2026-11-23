<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalReturnChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'product_unit_id',
        'condition_notes',
        'damage_notes',
        'photo_paths',
        'checked_by',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'photo_paths' => 'array',
            'checked_at' => 'datetime',
        ];
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
