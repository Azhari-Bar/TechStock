<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_unit_id',
        'complaint',
        'damage_description',
        'technician',
        'service_cost',
        'service_date',
        'completed_at',
        'repair_action',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => ServiceStatus::class,
            'service_cost' => 'decimal:2',
            'service_date' => 'date',
            'completed_at' => 'date',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }
}
