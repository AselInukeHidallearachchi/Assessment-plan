<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public const STATUS_OPEN = 'open';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    protected $fillable = [
        'title',
        'requester_name',
        'requester_email',
        'category',
        'priority',
        'status',
        'due_date',
        'description',
        'resolution_note',
        'attachment_path',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_IN_PROGRESS,
            self::STATUS_RESOLVED,
            self::STATUS_CLOSED,
        ];
    }

    public static function priorities(): array
    {
        return [
            self::PRIORITY_LOW,
            self::PRIORITY_MEDIUM,
            self::PRIORITY_HIGH,
            self::PRIORITY_URGENT,
        ];
    }

    public static function categories(): array
    {
        return [
            'bug',
            'access',
            'hardware',
            'software',
            'other',
        ];
    }

    public function statusLabel(): string
    {
        return str($this->status)->replace('_', ' ')->headline()->toString();
    }

    public function priorityLabel(): string
    {
        return str($this->priority)->headline()->toString();
    }

    public function categoryLabel(): string
    {
        return str($this->category)->headline()->toString();
    }

    public function statusBadgeVariant(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'info',
            self::STATUS_IN_PROGRESS => 'warning',
            self::STATUS_RESOLVED => 'success',
            self::STATUS_CLOSED => 'secondary',
            default => 'outline',
        };
    }

    public function priorityBadgeVariant(): string
    {
        return match ($this->priority) {
            self::PRIORITY_URGENT => 'danger',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_MEDIUM => 'info',
            self::PRIORITY_LOW => 'secondary',
            default => 'outline',
        };
    }
}
