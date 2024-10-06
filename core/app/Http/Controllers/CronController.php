<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Investment;
use App\Models\Payment;
use App\Models\Withdrawal;

class CronController extends Controller
{
    public function cron()
    {
        $general                = gs();
        $general->last_cron_run = now();
        $general->save();

        $investments = Investment::pending()->where('remain_amount', '>', 0)
            ->whereHas('user', function ($user) {
                $user->where('status', Status::USER_ACTIVE);
            })->oldest('id')->orderBy('is_activation', 'DESC')->take(20)->get();
        foreach ($investments as $investment) {
            $remainAmount     = $investment->remain_amount;
            $withdrawalsQuery = Withdrawal::pending()
                ->where('remain_amount', '>', 0)
                ->whereHas('user', function ($user) {
                    $user->where('status', Status::USER_ACTIVE);
                })
                ->where('currency_id', $investment->currency_id)
                ->where('user_id', '!=', $investment->user_id);

            if ($withdrawalsQuery->count() == 0) {
                continue;
            }

            while ($remainAmount > 0) {
                $withdrawal = (clone $withdrawalsQuery)
                    ->orderBy('date_priority')
                    ->first();
                if (!$withdrawal) {
                    break;
                }
                $weak                    = $withdrawal->remain_amount < $remainAmount ? $withdrawal->remain_amount : $remainAmount;
                $remainAmount           -= $weak;
                $payment                 = new Payment();
                $payment->plan_id        = $investment->plan_id;
                $payment->investment_id  = $investment->id;
                $payment->withdrawal_id  = $withdrawal->id;
                $payment->to_user_id     = $withdrawal->user_id;
                $payment->from_user_id   = $investment->user_id;
                $payment->amount         = $weak;
                $payment->deadline       = now()->addHours($general->payment_duration);
                $payment->trx            = getTrx();
                $payment->save();

                $investment->remain_amount -= $weak;
                $investment->save();

                $withdrawal->remain_amount -= $weak;
                $withdrawal->save();

                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $investment->user_id;
                $adminNotification->title     = $investment->user->username . ' has merged to pay ' . getAmount($weak) . ' ' . $investment->currency->code;
                $adminNotification->click_url = urlPath('admin.payment.detail', @$payment->id);
                $adminNotification->save();

                  // ////// NOTIFICATION TO BOTH USER
                echo $adminNotification->title . '<br><br>';

                if ($investment->is_activation == 1) {
                    notify($investment->user, 'ACTIVATION_REQUEST_MERGE', [
                        'amount'   => showAmount($investment->amount),
                        'currency' => $investment->currency->code,
                        'deadline' => $investment->deadline,
                    ]);
                } else {
                    notify($investment->user, 'INVESTMENT_REQUEST_MERGE', [
                        'amount'   => showAmount($investment->amount),
                        'currency' => $investment->currency->code,
                        'deadline' => $investment->deadline,
                    ]);
                }

                notify($withdrawal->user, 'WITHDRAWAL_REQUEST_MERGE', [
                    'amount'   => showAmount($investment->amount),
                    'currency' => $investment->currency->code,
                ]);
            }
        }

        return 'Success!';
    }
}
