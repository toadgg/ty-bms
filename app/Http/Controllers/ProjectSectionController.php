<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectSection;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ProjectSectionController extends Controller
{

    protected $rules = [
        'project_id' => 'required',
        'name' => 'required',
        'receivable' => 'numeric|nullable',
        'advance_deducted' => 'numeric|nullable',
        'completion_date' => 'date_format:Y-m-d|nullable',
    ];
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sections = ProjectSection::with('project')->get();
            return Datatables::of($sections)->make(true);
        }
        $projects = Project::visible()->whereHas('contract', function($q){
            $q->where('pay_mode', '1');
        })->get();
        return view('section.index', compact('projects'));
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
            $data = $input['data'][0];
            $this->ajaxValidate($data);
            $section = ProjectSection::create($data);
            return Datatables::of([$section])->make(true);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $section = ProjectSection::find($id);
        $input = $request->input();
        $data = $input['data'][$id];
        $this->ajaxValidate($data);
        $section->update($data);
        return Datatables::of([$section])->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $section = ProjectSection::findOrFail($id);
        $section->delete();
        return array('data' => []);
    }
}
