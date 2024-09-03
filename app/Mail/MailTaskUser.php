<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailTaskUser extends Mailable
{
    use Queueable, SerializesModels;


    private $task;
    private $user;
    private $project;

    /**
     * Create a new message instance.
     *
     * @param $task
     * @param $user
     * @param $project
     * @return void
     */
    public function __construct($task, $user, $project)
    {
        $this->task = $task;
        $this->user = $user;
        $this->project = $project;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: ('noreplay@ip4y.com'),
            to: ($this->user->email),
            subject: 'Mail Task User'
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.NewTaskNotify',
            with: [
                'task' => $this->task,
                'user' => $this->user,
                'project' => $this->project,
                'end_date' => date('d/m/Y', strtotime($this->task->end_date))
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
