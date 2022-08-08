<?php

namespace Teamprodev\LaravelPayment\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * @return Factory|View
     */
    public function dashboard(){
        return view('pay-uz::dashboard');
    }

    /**
     * @return Factory|View
     */
    public function editors(){

        $converters = [];
        $listeners = [];

        $listeners['before_pay']['content']    = file_get_contents(base_path('/app/Http/Payments/before_pay.php'));
        $listeners['before_pay']['title']      = 'Before pay: payListener($model, $transaction = null, $action_type = \'before-pay\')';

        $listeners['after_pay']['content']     = file_get_contents(base_path('/app/Http/Payments/after_pay.php'));
        $listeners['after_pay']['title']       = 'After pay: payListener($model = null, $transaction, $action_type = \'after-pay\')';

        $listeners['after_paid_invoice']['content']     = file_get_contents(base_path('/app/Http/Payments/after_paid_invoice.php'));
        $listeners['after_paid_invoice']['title']       = 'After paid invoice: payListener($model = null, $transaction, $action_type = \'after-paid-invoice\')';

        $listeners['after_cancelled_invoice']['content']     = file_get_contents(base_path('/app/Http/Payments/after_cancelled_invoice.php'));
        $listeners['after_cancelled_invoice']['title']       = 'After cancelled invoice: payListener($model = null, $transaction, $action_type = \'after-cancelled-invoice\')';

        $listeners['paying']['content']        = file_get_contents(base_path('/app/Http/Payments/paying.php'));
        $listeners['paying']['title']          = 'Paying: payListener($model, $transaction, $action_type = \'paying\')';

        $listeners['cancel_pay']['content']    = file_get_contents(base_path('/app/Http/Payments/cancel_pay.php'));
        $listeners['cancel_pay']['title']      = 'Cancel pay: payListener($model = null, $transaction, $action_type = \'cancel-pay\')';

        $converters['key_model']['content']     = file_get_contents(base_path('/app/Http/Payments/key_model.php'));
        $converters['key_model']['title']       = 'Key to Model: convertKeyToModel($key), returns Elequent model or null';

        $converters['model_key']['content']     = file_get_contents(base_path('/app/Http/Payments/model_key.php'));
        $converters['model_key']['title']       = 'Model to Key: convertModelToKey($model), returns string';

        $converters['is_proper']['content']     = file_get_contents(base_path('/app/Http/Payments/is_proper.php'));
        $converters['is_proper']['title']       = 'Is proper: isProperModelAndAmount($model, $amount), returns true or false';

        return view('pay-uz::editors',compact('listeners','converters'));
    }

    /**
     * @return Factory|View
     */
    public function blank(){
        return view('pay-uz::blank');
    }

    /**
     * @return Factory|View
     */
    public function settings(){
        return view('pay-uz::settings');
    }
}
