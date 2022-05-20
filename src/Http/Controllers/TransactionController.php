<?php

namespace Teamprodev\LaravelPayment\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Teamprodev\LaravelPayment\Models\Transaction;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::latest()->get();
        return view('pay-uz::transactions.index',compact('transactions'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
