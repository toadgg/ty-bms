<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::with('perms')->get();
            return Datatables::of($roles)->make(true);;
        }
        $perms = Permission::all();
        return view('role.index', compact('perms'));
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
            $role = Role::create($data);
            if ($data['perms-many-count'] > 0) {
                $role->perms()->sync(collect($data['perms'])->pluck('id')->all());
            }
            $role->perms;
            return Datatables::of([$role])->make(true);
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
        $role = Role::find($id);
        $input = $request->input();
        $data = $input['data'][$id];
        $role->update($data);

        if ($data['perms-many-count'] == 0) {
            $role->perms()->sync([]);
        } else {
            $role->perms()->sync(collect($data['perms'])->pluck('id')->all());
        }

        $role->perms;
        return Datatables::of([$role])->make(true);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return array('data' => []);
    }
}
