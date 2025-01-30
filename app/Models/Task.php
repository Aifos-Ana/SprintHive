<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Disable automatic timestamp handling
    public $timestamps = false;
        
    protected $table = 'task'; // Specify the table name explicitly

    protected $primaryKey = 'taskid'; // Primary key is 'taskId'

    protected $dates = ['duedate'];

    protected $casts = [
        'duedate' => 'date',
    ];
    

    protected $fillable = [
        'title',
        'description',
        'priority',
        'duedate',
        'projectid', // Foreign key referencing Project table
    ];

    /**
     * Get the project that this task belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'projectid');
    }

    /**
     * Get comments associated with this task.
     */
    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'taskid');
    }

    /**
     * Get notifications associated with this task.
     */
    public function notifications()
    {
        return $this->hasMany(TaskNotification::class, 'taskid');
    }
    
}
