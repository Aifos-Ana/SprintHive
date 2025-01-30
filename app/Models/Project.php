<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Disable automatic timestamp handling
    public $timestamps = false;

    protected $table = 'project'; // Specify the table name explicitly

    protected $primaryKey = 'projectid'; // Primary key is 'projectId'

    protected $fillable = [
        'name',
        'description',
        'startdate',
        'enddate',
        'id', // Foreign key referencing Users table
    ];

    /**
     * Get the user (project leader) associated with the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'userproject', 'projectid', 'userid');
    }
    
    /**
     * Get the tasks associated with the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'projectid');
    }

    /**
     * Get the favorite associations of this project.
     */
    public function favoriteAssociations(){
        return $this->hasMany(FavouriteProject::class, 'projectid');
    }

    public function favoritedBy(){
        return $this->belongsToMany(User::class, 'favourite_projects', 'projectid', 'id')
                ->withPivot('addeddate');
    }
}
