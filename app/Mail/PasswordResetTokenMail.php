<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;

class PasswordResetTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $expiresAt;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $token, Carbon $expiresAt)
    {
        $this->user = $user;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
        $this->resetUrl = route('password.reset.token');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Token Reset Password - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-token',
            with: [
                'user' => $this->user,
                'token' => $this->token,
                'expiresAt' => $this->expiresAt,
                'resetUrl' => $this->resetUrl,
                'appName' => config('app.name'),
            ],
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