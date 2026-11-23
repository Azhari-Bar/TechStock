<?php

namespace App\Models;

use App\Enums\ProductCondition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'model',
        'condition',
        'processor',
        'ram_gb',
        'storage_capacity',
        'storage_type',
        'component_type',
        'capacity_gb',
        'connectivity',
        'compatibility',
        'gpu',
        'screen_size',
        'operating_system',
        'description',
        'image_path',
        'purchase_price',
        'selling_price',
        'rental_daily_rate',
        'rental_weekly_rate',
        'rental_monthly_rate',
        'warranty_period',
        'minimum_stock',
        'is_active',
        'is_borrowable',
    ];

    protected function casts(): array
    {
        return [
            'condition' => ProductCondition::class,
            'ram_gb' => 'decimal:2',
            'storage_capacity' => 'decimal:2',
            'screen_size' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'rental_daily_rate' => 'decimal:2',
            'rental_weekly_rate' => 'decimal:2',
            'rental_monthly_rate' => 'decimal:2',
            'minimum_stock' => 'integer',
            'is_active' => 'boolean',
            'is_borrowable' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $base = Str::slug($product->name);
                $slug = $base;
                $i = 1;
                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $product->slug = $slug;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function availableUnitsCount(): int
    {
        return $this->units()->where('status', 'available')->count();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        $path = $this->image_path;

        if (! empty($path)) {
            if (preg_match('#^https?://#i', $path)) {
                return $path;
            }
            if (file_exists(public_path($path))) {
                return asset($path);
            }
            if (file_exists(public_path('storage/'.$path))) {
                return asset('storage/'.$path);
            }
            if (file_exists(storage_path('app/public/'.$path))) {
                return asset('storage/'.$path);
            }
        }

        return asset('images/placeholder-product.svg');
    }

    public function getStockStatusAttribute(): string
    {
        $count = $this->availableUnitsCount();
        if ($count <= 0) {
            return 'out_of_stock';
        }
        if ($count <= $this->minimum_stock) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function isComponent(): bool
    {
        return filled($this->component_type);
    }
}
