<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefLog extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function byWho()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

}
