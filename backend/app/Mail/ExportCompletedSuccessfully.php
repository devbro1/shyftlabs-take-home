<?php

namespace App\Mail;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class ExportCompletedSuccessfully extends Mailable
{
    use Queueable;
    use SerializesModels;

    private File $file;

    /**
     * Create a new message instance.
     *
     * @param mixed $file_path
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Export Completed Successfully',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: '<p>Your export is ready</p><p>Please download it from this email</p>',
            // text: 'Your export is ready. Please download it from this email.',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(Storage::path($this->file->path))->as($this->file->filename),
        ];
    }
}
