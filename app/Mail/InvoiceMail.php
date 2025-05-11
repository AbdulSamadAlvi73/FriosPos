<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceMail extends Mailable
{
use Queueable, SerializesModels;

    public $invoice;
    public $invoiceItems;
    public $franchisee;

    public function __construct($invoice, $invoiceItems, $franchisee)
    {
        $this->invoice = $invoice;
        $this->invoiceItems = $invoiceItems;
        $this->franchisee = $franchisee;
    }


    public function build()
    {
        $pdf = PDF::loadView('franchise_admin.payment.pdf.invoice-pos', [
            'invoice' => $this->invoice,
            'invoiceItems' => $this->invoiceItems,
            'franchisee' => $this->franchisee,
        ]);

        return $this->markdown('emails.invoice')
            ->subject('Your Invoice OR-00' . $this->invoice->id)
            ->attachData($pdf->output(), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
