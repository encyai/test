<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    use Searchable;

    protected $casts = [
        'deadline'              => 'datetime',
        'confirmation_deadline' => 'datetime',
        'received_time'         => 'datetime',
    ];

    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }
    public function fromUser() {
        return $this->belongsTo(User::class, 'from_user_id');
    }
    public function toUser() {
        return $this->hasOne(User::class, 'id', 'to_user_id');
    }
    public function chats() {
        return $this->hasMany(Chat::class);
    }
    public function investment() {
        return $this->belongsTo(Investment::class);
    }
    public function withdraw() {
        return $this->belongsTo(Withdrawal::class, 'withdrawal_id');
    }

    // scope and methods
    public function scopeAwaiting($query) {
        $query->where('status', Status::PAYMENT_CREATED)->orWhere('status', Status::PAYMENT_WAITING);
    }

    public function scopeReported($query) {
        $query->where('status', Status::PAYMENT_REPORT_SENDER)->orWhere('status', Status::PAYMENT_REPORT_RECEIVER);
    }
    public function scopeCompleted($query) {
        $query->where('status', Status::PAYMENT_COMPLETED);
    }
    public function scopeRejected($query) {
        $query->where('status', Status::PAYMENT_CANCELLED);
    }

    public function statusBadge(): Attribute {
        return new Attribute(function () {
            $className = 'badge badge--';

            if ($this->status == Status::PAYMENT_COMPLETED) {
                $className .= 'success';
                $text = 'Payment Completed';
            } elseif ($this->status == Status::PAYMENT_WAITING) {
                $className .= 'warning';
                $text = 'Waiting for Confirmation';
            } elseif ($this->status == Status::PAYMENT_REPORT_SENDER || $this->status == Status::PAYMENT_REPORT_RECEIVER) {
                $className .= 'danger';
                $text = 'Reported';
            } elseif ($this->status == Status::PAYMENT_CANCELLED) {
                $className .= 'dark';
                $text = 'Rejected';
            } else {
                $className .= 'warning';
                $text = 'Waiting For Payment';
            }
            return "<span class='badge badge--$className'>" . trans($text) . "</span>";
        });
    }
}
