<?php

namespace App\Enums;

enum MessageStatus: string
{
    case PENDING = 'pending';
    case QUEUED = 'queued';
    case SENDING = 'sending';
    case SENT = 'sent';
    case DELIVERED = 'delivered';
    case READ = 'read';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::QUEUED => 'Queued',
            self::SENDING => 'Sending',
            self::SENT => 'Sent',
            self::DELIVERED => 'Delivered',
            self::READ => 'Read',
            self::FAILED => 'Failed',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::PENDING => 'bg-amber-100 text-amber-700',
            self::QUEUED => 'bg-yellow-100 text-yellow-700',
            self::SENDING => 'bg-blue-100 text-blue-700',
            self::SENT => 'bg-green-100 text-green-700',
            self::DELIVERED => 'bg-emerald-100 text-emerald-700',
            self::READ => 'bg-purple-100 text-purple-700',
            self::FAILED => 'bg-red-100 text-red-700',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [
            self::SENT,
            self::DELIVERED,
            self::READ,
            self::FAILED,
        ], true);
    }

    public function isSuccessful(): bool
    {
        return in_array($this, [
            self::SENT,
            self::DELIVERED,
            self::READ,
        ], true);
    }

    public function isPending(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::QUEUED,
            self::SENDING,
        ], true);
    }
}