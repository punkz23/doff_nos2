<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Mail\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;

class WaybillMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public array $content;
    public function __construct( array $content )
    {
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'DOFF Online Booking',
            
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'waybills.send_printable_link',
            with: [
                    'reference_no'=>$this->content['reference_no'],
                    'name'=>$this->content['to_name']
                ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = PDF::loadView('waybills.reference_pdf',$this->content['pdf_data']);
        return [

            Attachment::fromData(fn () => $pdf->output(), $this->content['reference_no'].'.pdf')
                ->withMime('application/pdf'),

        ];
        
    }
}
