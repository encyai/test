<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model {
    use Searchable, GlobalStatus;

    public function form() {
        return $this->belongsTo(Form::class);
    }

    // scopes
    public function scopeActive($query) {
        $query->where('status', Status::ACTIVE);
    }

    public function withdrawInfo() {
        return $this->hasOne(WithdrawalInfo::class, 'currency_id')->where('user_id', auth()->user()->id);
    }
}
