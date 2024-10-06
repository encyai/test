<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class)->latest();
    }

    // scope and method
    public function scopeCompleted($query)
    {
        $query->where('status', Status::WITHDRAW_COMPLETED);
    }

    public function scopePending($query)
    {
        $query->where('status', Status::WITHDRAW_DEFAULT);
    }

    public function scopeComplete($query)
    {
        $query->where('status', Status::WITHDRAW_COMPLETED);
    }
}
