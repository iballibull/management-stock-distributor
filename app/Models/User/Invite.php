<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'email',
        'role_id',
        'token',
        'used'
    ];

}
