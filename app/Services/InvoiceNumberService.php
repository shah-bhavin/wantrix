<?php

use App\Models\Invoice;

class InvoiceNumberService
{
    public function generate(): string
    {
        $year = now()->year;

        $next = Invoice::count() + 1;

        return sprintf(
            'WAN-%s-%06d',
            $year,
            $next
        );
    }
}