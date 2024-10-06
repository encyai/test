<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use Searchable, GlobalStatus;

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function scopeActive($query)
    {
        $query->where('status', Status::ENABLE);
    }
}
