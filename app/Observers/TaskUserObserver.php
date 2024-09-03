<?php

namespace App\Observers;

use App\Jobs\MailTaskUserJob;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;

class TaskUserObserver
{
    /**
     * Handle the TaskUser "created" event.
     *
     * @param  \App\Models\TaskUser  $taskUser
     * @return void
     */
    public function created(TaskUser $taskUser)
    {
        $user =  $taskUser->user;
        $task = $taskUser->task;
        $project = $task->project;

        MailTaskUserJob::dispatch($task,$user,$project);
    }

}
