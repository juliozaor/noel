<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembersReservation extends Model
{
    protected $table = 'members_reservation';

    use HasFactory;
    protected $fillable = [
        'members_id',
        'reservation_id'
    ];
}
