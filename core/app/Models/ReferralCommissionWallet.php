<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCommissionWallet extends Model
{
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
