<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user()
    {
        // return $user = Auth::user();
        $user = User::with('karyawan','roles')->where('id', Auth::user()->id)->first();
        return $user;
    }

    public function info()
    {
        return $this->user();
    }

    public function roleName()
    {
        return $this->user()->getRoleNames();
    }

    public function index(Request $request)
    {   
        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $search = $request->input('search');

        $query = User::orderBy($column, $dir)->with('roles:id,name');

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate($length);
        return $data;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|max:255|confirmed',

            'sex' => 'required',
            'phone' => 'required|numeric|digits_between:1,13|unique:users',
            'avatar' => 'required|max:512|mimes:jpeg,jpg,png',

            'role' => 'required|string|max:255',
        ]);

        if ($request->hasFile('avatar')){
            $filenameWithExt = $request->file('avatar')->getClientOriginalName();
            $filename = uniqid();
            $fileExt = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$fileExt;
            $path = $request->file('avatar')->move(public_path('/images/user/'), $fileNameToStore);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        $user->sex = $request->sex;
        $user->phone = $request->phone;
        $user->avatar = $fileNameToStore;

        if ($user->save()) {
            $user->assignRole($request->role);
            return $user;
        }
    }

    public function update(Request $request, $user)
    {
        $user = User::findOrFail($user);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email,'.$user->id,
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'password' => 'required|string|min:8|max:255|confirmed',

            'sex' => 'required',
            'phone' => 'required|numeric|digits_between:1,13|unique:users,phone,'.$user->id,
            'avatar' => 'max:512|mimes:jpeg,jpg,png',

            'role' => 'required|string|max:255',
        ]);

        if ($request->hasFile('avatar')){
            if ($user->avatar != null) {
                unlink(public_path('/images/user/'.$user->avatar));
            }

            $filenameWithExt = $request->file('avatar')->getClientOriginalName();
            $filename = uniqid();
            $fileExt = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$fileExt;
            $path = $request->file('avatar')->move(public_path('/images/user/'), $fileNameToStore);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        $user->sex = $request->sex;
        $user->phone = $request->phone;
        if ($request->hasFile('avatar')){
            $user->avatar = $fileNameToStore;
        }

        if ($user->update()) {
            $user->syncRoles($request->role);
            return $user;
        }
    }

    public function destroy($user)
    {
        $user = User::findOrFail($user);
        if ($user->avatar != null) {
            unlink(public_path('/images/user/'.$user->avatar));
        }
        if ($user->delete()) {
            return $user;
        }
    }
}
