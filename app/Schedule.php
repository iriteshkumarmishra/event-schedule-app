<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['event_id', 'scheduled_at'];
    protected $casts = [
        'scheduled_at'   =>  'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
