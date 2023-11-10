<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestAccess extends Model
{
    use HasFactory;
    
    protected $table = 'guest_access';
    protected $fillable = [];
}
