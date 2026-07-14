<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case PENDING = 'pending';

    case PAID = 'paid';

    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {

            self::PENDING => 'Pending',

            self::PAID => 'Paid',

            self::CANCELLED => 'Cancelled',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {

            self::PENDING => 'bg-amber-100 text-amber-700',

            self::PAID => 'bg-green-100 text-green-700',

            self::CANCELLED => 'bg-slate-100 text-slate-700',
        };
    }
}
