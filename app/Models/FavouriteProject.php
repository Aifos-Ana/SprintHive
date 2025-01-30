<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteProject extends Model
{
    use HasFactory;

    protected $table = 'favouriteproject'; // Specify the table name explicitly

    protected $primaryKey = 'favouriteid'; // Match the database column name

    protected $keyType = 'int'; // Primary key type
    public $incrementing = true;  // Ensure Laravel handles it as auto-incrementing

    public $timestamps  = false;

    protected $fillable = [
        'id', // Foreign key referencing Users table
        'projectid', // Foreign key referencing Project table
        'addeddate',
    ];

    /**
     * Get the user who favorited the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Get the project that was favorited.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'projectid');
    }
}
