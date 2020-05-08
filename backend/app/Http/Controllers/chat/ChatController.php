<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class ChatController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function karyawan()
    {   
        $karyawan = Karyawan::where('user_id', $this->user()->id)->first();
        return $karyawan;
    }

    public function userList()
    {
        $users = User::select('id','name','avatar','isLogin')->get();
        return $users;
    }
}
