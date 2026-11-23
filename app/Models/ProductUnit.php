<?php

namespace App\Models;

use App\Enums\UnitStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'serial_number',
        'room',
        'rack',
        'shelf',
        'bin',
        'physical_condition',
        'grade',
        'acquisition_price',
        'received_at',
        'warranty_until',
        'image_path',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => UnitStatus::class,
            'acquisition_price' => 'decimal:2',
            'received_at' => 'date',
            'warranty_until' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function rental(): HasOne
    {
        return $this->hasOne(Rental::class, 'product_unit_id');
    }
}