<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pekerjaan extends Model
{
    use HasFactory;

    protected $table = 'pekerjaan';


    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    protected $fillable = [
        'id_user', 'bulan', 'start', 'end', 'jam_toleransi', 'total_jam'
    ];
}
