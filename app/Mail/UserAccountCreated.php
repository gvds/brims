<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class UserAccountCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public User $creator)
    {
        $this->loginUrl = URL::temporarySignedRoute(
            'newaccount',
            now()->addDay(),
            ['user' => $this->user->id]
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') . ' Account Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.user-account-created',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
