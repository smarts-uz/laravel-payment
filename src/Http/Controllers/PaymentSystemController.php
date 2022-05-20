<?php

namespace Teamprodev\LaravelPayment\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Teamprodev\LaravelPayment\Models\PaymentSystem;
use Teamprodev\LaravelPayment\Models\PaymentSystemParam;
use Teamprodev\LaravelPayment\Services\PaymentSystemService;

class PaymentSystemController extends Controller
{

    public function index()
    {
        $payment_systems = PaymentSystem::latest()->get();
        return view('pay-uz::payment_systems.index',compact('payment_systems'));
    }


    public function create()
    {
        return view('pay-uz::payment_systems.create');
    }


    public function store(Request $request)
    {
        $rules = [
            'system'         => 'required|unique:payment_systems|max:255',
        ];

        $messages = [
            'system.required'          => "Payment system cannot be exist!",
            'system.unique'          => "The payment system has already been taken!",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('payment.payment_systems.create')
                ->withErrors($validator)
                ->withInput();
        }

        PaymentSystemService::createPaymentSystem($request);

        return redirect()->route('payment.payment_systems.index')->with(['success'  => "Payment system successfully saved."]);

    }


    public function show($id)
    {
        //
    }


    public function edit(PaymentSystem $payment_system)
    {
        return view('pay-uz::payment_systems.edit',compact('payment_system'));
    }



    public function update(Request $request, PaymentSystem $payment_system)
    {
        $rules = [
            'system'         => 'required|max:255|unique:payment_systems'. ',system,' . $payment_system->id,
        ];

        $messages = [
            'system.required'          => "Payment system cannot be exist!",
            'system.unique'          => "The payment system has already been taken!",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('payment.payment_systems.edit',['payment_system' => $payment_system->id])
                ->withErrors($validator)
                ->withInput();
        }

        PaymentSystemService::updatePaymentSystem($request,$payment_system);

        return redirect()->route('payment.payment_systems.edit',['payment_system' => $payment_system->id])->with(['success'  => "Payment system successfully saved."]);
    }


    public function destroy(PaymentSystem $payment_system)
    {
        $payment_system->delete();
        return redirect()->back()->with(['success'  => "To'lov tizimi o'chirildi"]);
    }

    public function editStatus(PaymentSystem $paymentSystem){
        $paymentSystem->status = ($paymentSystem->status == PaymentSystem::NOT_ACTIVE) ? PaymentSystem::ACTIVE : PaymentSystem::NOT_ACTIVE;
        $paymentSystem->update();
        return  redirect()->back()->with(['success' => "Status o'xgartirildi"]);
    }

    public function deleteParam($param_id){
        $param = PaymentSystemParam::find($param_id);
        if ($param){
            $param->delete();
            return redirect()->back()->with(['success'  => 'Param deleted!']);
        }
        return redirect()->back()->with(['warning'  => 'Param not found!']);
    }
}
