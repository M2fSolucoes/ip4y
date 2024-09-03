<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $table = "projects";
    protected $primaryKey = "id";
    protected $fillable = [
        'project_code',
        'title',
        'description',
        'end_date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }
}
