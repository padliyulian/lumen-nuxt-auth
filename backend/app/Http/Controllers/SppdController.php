<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sppd;
use Validator;

class SppdController extends Controller
{
    public function index(Request $request)
    {   
        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $search = $request->input('search');

        $query = Sppd::orderBy($column, $dir)->with('karyawan:id,nama');

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
}
