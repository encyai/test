<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalInfo extends Model
{
    protected $casts = [
        'info' => 'object',
    ];
}
