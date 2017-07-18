<?php

namespace App\Http\Controllers;

use App\Category;
use Carbon\Carbon;
use DateTime;
use App\CapitalPlan;
use App\Plate;
use App\Project;
use Illuminate\Http\Request;

class CapitalPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = CapitalPlan::with(['plate', 'project'])->get();
        $projects = Project::visible()->get();
        $plates = Plate::all();
        $categories = Category::all();
        return view('plans.capital.index', compact('plans', 'projects', 'plates', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->only(['project_id', 'plate_id', 'calendar']);

        $inputs['calendar'] = Carbon::createFromFormat('mY', $inputs['calendar'])->lastOfMonth();

        $exists = CapitalPlan::where($inputs)->exists();
        if (!$exists) {
            CapitalPlan::create($inputs);
        }

        return redirect()->action('CapitalPlanController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CapitalPlan  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plan = CapitalPlan::with('details')->findOrFail($id);
        dd($plan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CapitalPlan  $capitalPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(CapitalPlan $capitalPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CapitalPlan  $capitalPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CapitalPlan $capitalPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plan = CapitalPlan::findOrFail($id);
        $plan->details()->delete();
        $plan->delete();
    }
}
