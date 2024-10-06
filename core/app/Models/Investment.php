<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model {
    protected $casts = [
        'created_at' => 'datetime',
        'paid_at' => 'datetime',
        'withdrawn_at' => 'datetime',
        'withdrawable_time' => 'datetime',
    ];

    public function currency() {
        return $this->belongsTo(Currency::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    public function payment() {
        return $this->hasMany(Payment::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class)->orderBy('id', 'desc')->with('currency', 'investment', 'fromUser', 'toUser');
    }


    // scope and investment
    public function scopeCompleted($query) {
        $query->where('status', Status::INVESTMENT_COMPLETE);
    }

    public function scopePending($query) {
        $query->where('status', Status::INVESTMENT_PENDING);
    }

    public function isWithdrawable() {
        if ($this->is_activation) {
            return 'Never withdrawable';
        } elseif ($this->withdrawn_at) {
            return 'Withdrawn';
        } elseif ($this->status == Status::INVESTMENT_COMPLETE && $this->recommit_status == Status::RECOMMIT_COMPLETE && $this->withdrawable_time < now() && $this->withdrawn_at == null) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function scopeWithdrawable($query) {
        $query->where('recommit_status', 1)->where('withdrawable_time', '<', now())->where('withdrawn_at', null);
    }

    public function pendingAmount() {
        return $this->amount - $this->remain_amount - $this->success_amount;
    }

    public function pendingRecommitAmount() {
        return $this->recommit_amount - $this->recommit_remain - $this->recommit_success;
    }

    public function statusBadge(): Attribute {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::INVESTMENT_COMPLETE) {
                $html = '<span class="badge badge--success">' . trans("Completed") . '</span>';
            } else {
                $html = '<span class="badge badge--warning">' . trans("Pending") . '</span>';
            }
            return $html;
        });
    }
}
