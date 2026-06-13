<?php

namespace App\Observers;

use App\Models\Invoice;
use InvoiceNumberService;

class InvoiceObserver
{
    public function creating(Invoice $invoice): void
    {
        $invoice->invoice_number = app(InvoiceNumberService::class)->generate();
        $invoice->issued_at = now();
    }

    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
