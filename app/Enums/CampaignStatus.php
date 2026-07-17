<?php

namespace App\Enums;

enum CampaignStatus: string
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case PAUSED = 'paused';
    case CANCELLED = 'cancelled';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SCHEDULED => 'Scheduled',
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::PAUSED => 'Paused',
            self::CANCELLED => 'Cancelled',
            self::FAILED => 'Failed',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {

            self::DRAFT => 'bg-gray-100 text-gray-700',

            self::SCHEDULED => 'bg-blue-100 text-blue-700',

            self::PROCESSING => 'bg-yellow-100 text-yellow-700',

            self::COMPLETED => 'bg-green-100 text-green-700',

            self::FAILED => 'bg-red-100 text-red-700',

            self::PAUSED => 'bg-orange-100 text-orange-700',

            self::CANCELLED => 'bg-slate-200 text-slate-700',
        };
    }

    public function canDispatch(): bool
    {
        return $this === self::DRAFT;
    }

    public function canGenerateMessages(): bool
    {
        return $this === self::DRAFT;
    }

    public function canPause(): bool
    {
        return $this === self::PROCESSING;
    }

    public function canResume(): bool
    {
        return in_array($this, [
            self::PAUSED,
            self::CANCELLED,
        ]);
    }

    public function canCancel(): bool
    {
        return in_array($this, [
            self::DRAFT,
            self::SCHEDULED,
            self::PROCESSING,
            self::PAUSED,
        ]);
    }

    public function isFinished(): bool
    {
        return in_array($this, [
            self::COMPLETED,
            self::FAILED,
            self::CANCELLED,
        ]);
    }

    public function isPaused(): bool
    {
        return $this === self::PAUSED;
    }

    public function isProcessing(): bool
    {
        return $this === self::PROCESSING;
    }

    public function nextStatuses(): array
    {
        return match ($this) {

            self::DRAFT => [
                self::PROCESSING,
                self::CANCELLED,
            ],

            self::PROCESSING => [
                self::COMPLETED,
                self::FAILED,
                self::PAUSED,
            ],

            self::PAUSED => [
                self::PROCESSING,
                self::CANCELLED,
            ],

            default => [],
        };
    }
}
