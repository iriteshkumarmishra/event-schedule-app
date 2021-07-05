<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'description', 'start_time', 'end_time', 'day_of_the_week', 'created_by'];
    protected $casts = [
        'day_of_the_week'   =>  'array',
    ];

    public function author()
    {
        return User::find($this->created_by);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
