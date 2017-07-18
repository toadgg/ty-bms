<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')->get();
            return Datatables::of($users)
                ->removeColumn('password')
                ->make(true);
        }
        $roles = Role::all();
        return view('user.index', compact('roles'));
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
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            if ($data['roles-many-count'] > 0) {
                $user->roles()->sync(collect($data['roles'])->pluck('id')->all());
            }

            if ($data['avatar']) {
                $avatar = "users/$user->id/avatar";
                Storage::move(str_replace(Storage::url(''), '', $data['avatar']), $avatar);
                $user->avatar = $avatar;
                $user->save();
            }
            $user->roles;

            return Datatables::of([$user])->make(true);
        }
        if ($input['action'] == 'upload') {
            $file = $request->upload;
            $preview = $file->store('preview/temp');
            return response()->json([
                'upload' => ['id' => Storage::url($preview)]
            ]);
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
        $user = User::find($id);
        $input = $request->input();
        $data = $input['data'][$id];

        if (!$data['password']) {
            unset($data['password']);
        }
        $userAvatar = "users/$id/avatar";

        $data['avatar'] = str_replace(Storage::url(''), '', $data['avatar']);
        if ($user->getOriginal('avatar') != $data['avatar']) {
            if (Storage::exists($userAvatar)) {
                Storage::delete($userAvatar);
            }
            if ($data['avatar']) {
                Storage::move($data['avatar'], $userAvatar);
                $data['avatar'] = $userAvatar;
            }
        } else {
            unset($data['avatar']);
        }

        $user->update($data);

        if ($data['roles-many-count'] == 0) {
            $user->roles()->sync([]);
        } else {
            $user->roles()->sync(collect($data['roles'])->pluck('id')->all());
        }
        $user->roles;

        return Datatables::of([$user])->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return array('data' => []);
    }
}
