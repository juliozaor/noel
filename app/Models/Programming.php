<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Programming extends Model
{
    use HasFactory;
    protected $fillable = [
        'quota',
        'cell',
        'quota_available',
        'initial_date',
        'initial_time',
        'final_date',
        'final_time',
        'event_id',
        'state',
    ];

    public function event() {
        return $this->belongsTo('App\Models\Event');
    }

    public function reservation() {
        return $this->hasMany('App\Models\Reservation');
    }
    public function qrCodes(): HasManyThrough
    {
        return $this->hasManyThrough(QrCodes::class, Reservation::class);
    }
}
