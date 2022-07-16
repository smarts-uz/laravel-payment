<?php

use Illuminate\Support\Facades\Route;
use Teamprodev\LaravelPayment\Http\Controllers\ClientController;
use Teamprodev\LaravelPayment\PayUz;

Route::middleware('web')->name('payment.')->prefix('payment')->namespace('Teamprodev\LaravelPayment\Http\Controllers')->group(function() {

    Route::any('dashboard','PageController@dashboard')->name('dashboard');
    Route::any('editors','PageController@editors')->name('editors');
    Route::any('blank','PageController@blank')->name('blank');
    Route::any('settings','PageController@settings')->name('settings');
    Route::get('payment_params/delete/{param_id}','PaymentSystemController@deleteParam')->name('payment_systems.delete_param');
    Route::get('payment_systems/edit/status/{payment_system}','PaymentSystemController@editStatus')->name('payment_systems.edit_status');

    // --editable functions
    Route::any('/api/editable/update','ApiController@file_put')->name('api.file_put');
    // end --editable functions

    Route::resource('transactions','TransactionController');
    Route::resource('projects','ProjectController');
    Route::resource('payment_systems','PaymentSystemController');
    Route::resource('transactions','TransactionController');



    Route::get('/client/checkout', [ClientController::class, 'checkout'])->name('client.checkout');

    Route::any('/{payment}', function ($payment) {
        if (in_array($payment, ['paynet', 'payme', 'click'])) {
            (new PayUz)->driver($payment)->handle();
        }
    });
    Route::any('/click/user-balance', function () {
        return (new PayUz())->click_additional();
    });
});
