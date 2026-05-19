<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'name',
        'price',
        'stock_qty',
        'status',
        'description',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_qty' => 'integer',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_ACTIVE,
            self::STATUS_ARCHIVED,
        ];
    }

    public function statusLabel(): string
    {
        return str($this->status)->headline()->toString();
    }

    public function statusBadgeVariant(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_ARCHIVED => 'secondary',
            self::STATUS_DRAFT => 'warning',
            default => 'outline',
        };
    }

    public function formattedPrice(): string
    {
        return number_format((float) $this->price, 2);
    }
}
