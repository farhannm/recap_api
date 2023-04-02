<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalToken extends Model
{
    use HasFactory;

    protected $table = 'personal_token';

    protected $fillable = [
        'id_user', 
        'token'
    ];


    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

}
