<?php

namespace App\Constants;

class Status{

    const ENABLE  = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO  = 0;

    const ACTIVE   = 1;
    const INACTIVE = 0;
    const PENDING  = 2;

    const VERIFIED   = 1;
    const UNVERIFIED = 0;

    CONST TICKET_OPEN   = 0;
    CONST TICKET_ANSWER = 1;
    CONST TICKET_REPLY  = 2;
    CONST TICKET_CLOSE  = 3;

    CONST PRIORITY_LOW    = 1;
    CONST PRIORITY_MEDIUM = 2;
    CONST PRIORITY_HIGH   = 3;

    const USER_ACTIVE     = 1;
    const USER_BAN        = 0;
    const USER_ACTIVATION = 1;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING    = 2;
    const KYC_VERIFIED   = 1;

    const INVESTMENT_COMPLETE   = 1;
    const INVESTMENT_PENDING    = 0;
    const INVESTMENT_ACTIVATION = 1;

    const RECOMMIT_COMPLETE = 1;
    const RECOMMIT_WAITING  = 0;

    const PAYMENT_CREATED         = 0;
    const PAYMENT_COMPLETED       = 1;
    const PAYMENT_WAITING         = 2;
    const PAYMENT_REPORT_SENDER   = 3;
    const PAYMENT_REPORT_RECEIVER = 4;
    const PAYMENT_CANCELLED       = 5;

    const WITHDRAW_DEFAULT  = 0;
    const WITHDRAW_ELIGIBLE = 1;
    const WITHDRAW_REFERRAL = 3;

    const WITHDRAW_PENDING   = 0;
    const WITHDRAW_COMPLETED = 1;
}
