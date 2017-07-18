<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectReceiptStatement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;

class ProjectReceiptController extends Controller
{
    public function __construct() {
        for ($i = 1; $i<=12; $i++) {
            $this->rules["m_$i"] = 'numeric|nullable';
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $year = Input::get('y') ? Input::get('y') : Carbon::now()->year;
        if ($request->ajax()) {
            $statements = ProjectReceiptStatement::with('project')->where('year', $year)->get();
            return Datatables::of($statements)->make(true);
        }
        $projects = Project::visible()->with(['contract'])->get();
        return view('receipt.index', compact('projects', 'year'));
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
            $this->ajaxValidate($data, [
                'project_id' => ['required', Rule::unique('project_receipt_statements')
                    ->where(function ($query) use ($data) {
                        $query->where([
                            'project_id' => $data['project_id'],
                            'year' => $data['year']
                        ]);
                    })]
            ]);
            $receipt = ProjectReceiptStatement::create($data);
            return Datatables::of([$receipt])->make(true);
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
     * @param  \App\ProjectReceiptStatement  $projectReceiptStatement
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectReceiptStatement $projectReceiptStatement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProjectReceiptStatement  $projectReceiptStatement
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectReceiptStatement $projectReceiptStatement)
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
        $receipt = ProjectReceiptStatement::find($id);
        $input = $request->input();
        $data = $input['data'][$id];
        foreach ($data as &$value) {
            if (empty($value)) {
                $value = 0;
            }
        }
        $appendRules = [];
        if (array_key_exists('project_id', $data) && array_key_exists('year', $data)) {
            $appendRules['project_id'] = ['required', Rule::unique('project_receipt_statements')
                ->where(function ($query) use ($data) {
                    $query->where([
                        'project_id' => $data['project_id'],
                        'year' => $data['year']]);
                })
            ];
        }
        $this->ajaxValidate($data, $appendRules);
        $receipt->update($data);
        return Datatables::of([$receipt])->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $outputValue = ProjectReceiptStatement::findOrFail($id);
        $outputValue->delete();
        return array('data' => []);
    }
}
