<?php

namespace App\Http\Controllers;

use App\CapitalPlan;
use App\CapitalPlanDetail;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class CapitalPlanDetailController extends Controller
{
    protected $rules = [
        'pay_to' => 'required|exists:nc56.BD_CUBASDOC,CUSTNAME',
        'info' => 'required',
        'contract_amount' => 'numeric|nullable',
        'completed_amount' => 'numeric|nullable',
        'payable_in_contract' => 'numeric|nullable',
        'paid_in_contract' => 'numeric|nullable',
        'paid_in_contract_amount' => 'numeric|nullable',
        'payable_in_plan' => 'required|numeric|nullable',
        'paid_in_plan' => 'numeric|nullable',
    ];
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @param  $cid
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id, $cid)
    {
        $plan = CapitalPlan::with(['project', 'plate', 'details'])->findOrFail($id);
        if ($request->ajax()) {
            $planDetails = $plan->details->where('category_id', $cid);
            return Datatables::of($planDetails)->make(true);
        }
        $category = Category::findOrFail($cid);
        return view('plans.capital.detail', compact('plan', 'category'));
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
        $input = $request->input();

        if ($input['action'] == 'create') {
            $data = array_filter($input['data'][0], 'strlen');
            $this->ajaxValidate($data);
            $detail = CapitalPlanDetail::create($data);
            $plan = CapitalPlan::find($detail->capital_plan_id);
            $plan->payment_amount = $plan->details()->sum('payable_in_plan');
            $plan->statistical_data = Carbon::now();
            $plan->save();
            return Datatables::of([$detail])->make(true);
        }
        $id = array_keys($input['data'])[0];
        if ($input['action'] == 'edit') {
            return $this->update($request, $id);
        }
        if ($input['action'] == 'remove') {
            return $this->destroy($id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CapitalPlanDetail  $capitalPlanDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CapitalPlanDetail $capitalPlanDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CapitalPlanDetail  $capitalPlanDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(CapitalPlanDetail $capitalPlanDetail)
    {
        //
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
        $detail = CapitalPlanDetail::find($id);
        $input = $request->input();
        $data = $input['data'][$id];
        foreach ($data as $key => &$value) {
            if (in_array($key, ['contract_amount', 'completed_amount', 'payable_in_contract', 'paid_in_contract', 'paid_in_contract_amount', 'payable_in_plan', 'paid_in_plan']) && empty($value)) {
                $value = 0;
            }
        }
        $detail->update($data);
        $plan = CapitalPlan::find($detail->capital_plan_id);
        $plan->payment_amount = $plan->details()->sum('payable_in_plan');
        $plan->statistical_data = Carbon::now();
        $plan->save();
        return Datatables::of([$detail])->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detail = CapitalPlanDetail::findOrFail($id);
        $plan = CapitalPlan::find($detail->capital_plan_id);
        $detail->delete();
        $plan->payment_amount = $plan->details()->sum('payable_in_plan');
        $plan->statistical_data = Carbon::now();
        $plan->save();
        return array('data' => []);
    }
}
