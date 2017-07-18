<?php

namespace App\Http\Controllers;

use App\Contract;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = Contract::orderBy('signed_date', 'desc')->get();
        return view('contract.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $items = Contract::all();
        $page = single_page_paginator($items, $id);
        return view('contract.edit', $page);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $items = Contract::all();
        $page = single_page_paginator($items, $id);
        return view('contract.edit', $page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);
        $inputs = $request->only(['visible', 'pay_mode', 'advance_payment_amount', 'advance_payment_pct', 'progress_payment_pct']);
        $contract->visible                = $inputs['visible'] == 'on' ? 'on' : 'off';
        $contract->pay_mode               = $inputs['pay_mode'];
        $contract->advance_payment_amount = $inputs['advance_payment_amount'];
        $contract->progress_payment_pct   = $inputs['progress_payment_pct'];
        $contract->save();
        return redirect()->action('ContractController@edit', [$contract->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
