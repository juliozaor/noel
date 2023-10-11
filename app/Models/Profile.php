<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'document',
        'cell',
        'address',
        'neighborhood',
        'birth',
        'eps',
        'reference',
        'experience2022',
        'user_id',
        'is_collaborator'
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }
}
