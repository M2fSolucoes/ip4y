<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    use HasFactory;


    protected $table = "task_users";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'task_id',
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public function task()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }
}
