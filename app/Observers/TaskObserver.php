<?php

namespace App\Observers;

use App\Jobs\MailTaskStatusJob;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TaskObserver
{

    /**
     * Handle the Task "updated" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        $updated = $task->getChanges();

        if (count($updated)>0) {
            if (isset($updated['status'])){
                $user = $task->users;
                $project = $task->project;
                MailTaskStatusJob::dispatch($task,$user,$project);
            }
        }
    }


}
