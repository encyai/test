<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });
    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['check.status'])->group(function () {

        Route::get('user-data', 'User\UserController@userData')->name('data');
        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

        Route::middleware('registration.complete')->namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                Route::get('account/activate', 'activateAccount')->name('activate');
                Route::post('account/activation-request', 'sendActivationRequest')->name('activate.request');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                Route::get('transactions', 'transactions')->name('transactions.history');

                Route::middleware('referral')->group(function () {
                    Route::get('referral/users', 'referral')->name('referral.log');
                    Route::get('referral/users/investment/{id}', 'referralUserInvest')->name('referral.investment');
                    Route::get('referral/commissions', 'referralCommission')->name('referral.commission');
                    Route::get('referral/withdraw', 'referralWithdraw')->name('referral.withdraw');
                    Route::post('referral/withdraw/commissions', 'withdrawCommission')->name('referral.withdraw.commissions');
                });

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');
                Route::post('send/message/{id}', 'sendMessage')->name('send.message');
                Route::get('chat/messages', 'chatMessage')->name('chat.messages');
            });

            Route::get('withdraw/information', 'WithdrawalInfoController@index')->name('withdraw.information');
            Route::post('withdraw/information/store/{id}', 'WithdrawalInfoController@store')->name('withdraw.information.store');

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            // Withdraw
            Route::middleware('kyc')->controller('WithdrawController')->prefix('withdraw')->name('withdraw.')->group(function () {
                Route::get('now', 'index')->name('now');
                Route::get('history', 'withdrawHistory')->name('history');
                Route::get('store/{id}', 'withdrawStore')->name('store');
                Route::get('details/{id}', 'withdrawDetails')->name('details');
            });

            Route::middleware(['accountActive'])->controller('InvestmentController')->name('invest.')->prefix('investment')->group(function () {
                Route::get('invest-now', 'investNow')->name('now');
                Route::post('store', 'store')->name('store');
                Route::get('history', 'history')->name('history');
                Route::get('detail/{id}', 'detail')->name('detail');
            });

            Route::controller('PaymentController')->name('payment.')->prefix('payment')->group(function () {
                Route::get('detail/{id}', 'detail')->name('detail');
                Route::post('proved/{id}', 'paymentProved')->name('proved');
                Route::post('report/{id}', 'paymentReport')->name('report');
                Route::post('confirm/{id}', 'paymentConfirm')->name('confirm');
                Route::post('not/paid/{id}', 'paymentNotPaid')->name('not.paid');
                Route::get('information/download/{id}', 'informationDownload')->name('information.download');
            });
        });
    });
});
