<?php

namespace App\Jobs;

use App\Mail\MailTaskStatusMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailTaskStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $task;
    private $user;
    private $project;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    public function __construct($task, $user, $project)
    {
        $this->task = $task;
        $this->user = $user;
        $this->project = $project;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->first()->email)->send(New MailTaskStatusMail($this->task, $this->user, $this->project));

    }

}
