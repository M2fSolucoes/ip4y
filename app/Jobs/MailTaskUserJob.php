<?php

namespace App\Jobs;

use App\Mail\MailTaskUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailTaskUserJob implements ShouldQueue
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
        Mail::to($this->user->email)->send(New MailTaskUser($this->task, $this->user, $this->project));
    }
}
