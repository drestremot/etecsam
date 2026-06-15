<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPhoto extends Model
{
    protected $fillable = ['event_id', 'path', 'caption', 'order'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
