<?php

namespace App\Domain\Attendance\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $keyType = 'string'; // because UUID
    public $incrementing = false; // UUID not auto-increment

    protected $fillable = [
        'id',
        'user_id',
        'latitude',
        'longitude',
        'check_in_at',
        'check_out_at',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
