<?php

namespace App\Http\Controllers\SPPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sppd;
use App\Models\User;
use App\Models\Karyawan;
use Validator;

class ListController extends Controller
{
    public function karyawan()
    {
        $karyawan = Karyawan::select('id', 'nama')->get();
        return $karyawan;
    }

    // public function index(Request $request)
    // {   
        
    //     $data['user'] = $this->user();
    //     $data['karyawan'] = Karyawan::where('user_id', $this->user()->id)->with('divisi:id,nama','jabatan:id,nama', 'sppd')->first();
    //     // $data['sppd'] = Sppd::with('karyawan')->where('karyawan_id', $data['karyawan']->id)->get();
    //     return $data;
    // }
}
