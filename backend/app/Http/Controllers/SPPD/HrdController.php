<?php

namespace App\Http\Controllers\SPPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sppd;
use App\Models\User;
use App\Models\Karyawan;
use Validator;

class HrdController extends Controller
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

    public function sppdUnApprove(Request $request)
    {   
        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $search = $request->input('search');

        $query = Sppd::orderBy($column, $dir)->whereHas('diketahui_unapprove', function($query){
            $query->where('id', '=', $this->karyawan()->id);
        })->with('karyawan:id,id,nama');

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('tujuan', 'like', '%' . $search . '%')
                    ->orWhere('pekerjaan', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate($length);
        return $data;
    }
}
