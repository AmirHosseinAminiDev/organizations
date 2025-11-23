<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int otp_code
 * @property string phone
 * @property string $last_name
 * @property string expires_at
 */
class Otp extends Model
{

    protected $fillable = [
        'phone',
        'otp',
        'expires_at',
    ];
}
