<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table =  "tasks";

    protected $primaryKey =  "id";
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'end_date',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users', 'task_id', 'user_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
