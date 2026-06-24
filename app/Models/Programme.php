<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function pi()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->withTimestamps();
    }
}
