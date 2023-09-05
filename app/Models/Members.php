<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    use HasFactory;

    protected $fillable = [
        'document',
        'name',
        'is_minor',
    ];

    //muchos a muchos
    public function reservation() {
        return $this->belongsToMany(Reservation::class);
    }
}
