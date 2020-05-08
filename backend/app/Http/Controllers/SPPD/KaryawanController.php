<?php

namespace App\Http\Controllers\SPPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sppd;
use App\Models\User;
use DB;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;
use Validator;

class KaryawanController extends Controller
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

    // sppd pemberi tugas ttd
    public function sppdPemberiApprove($sppd)
    {
        $sppd = Sppd::findOrFail($sppd);
        // $sppd->karyawan()->updateExistingPivot($sppd->id, ['progres' => 'diketahui']);
        DB::table('karyawan_sppd')->where('sppd_id', $sppd->id)->update(['progres' => 'diketahui']);
    }

    // sppd diketahui ttd
    public function sppdDiketahuiApprove($sppd)
    {
        $sppd = Sppd::findOrFail($sppd);
        // $sppd->karyawan()->updateExistingPivot($sppd->id, ['progres' => 'diketahui']);
        DB::table('karyawan_sppd')->where('sppd_id', $sppd->id)->update(['progres' => 'selesai']);
    }

    // sppd belum ttd pemberi tugas
    public function sppdPemberi(Request $request)
    {   
        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $search = $request->input('search');

        $query = Sppd::orderBy($column, $dir)->whereHas('pemberi_unapprove', function($query){
            $query->where('id', '=', $this->karyawan()->id);
        })->with('karyawan:id,id,nama,ttd,divisi_id,jabatan_id');

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('tujuan', 'like', '%' . $search . '%')
                    ->orWhere('pekerjaan', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate($length);
        return $data;
    }

    // sppd belum ttd diketahui
    public function sppdDiketahui(Request $request)
    {   
        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $search = $request->input('search');

        $query = Sppd::orderBy($column, $dir)->whereHas('diketahui_unapprove', function($query){
            $query->where('id', '=', $this->karyawan()->id);
        })->with('karyawan:id,id,nama,ttd,divisi_id,jabatan_id');

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('tujuan', 'like', '%' . $search . '%')
                    ->orWhere('pekerjaan', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate($length);
        return $data;
    }

    // sppd selesai ttd
    public function sppdSelesai(Request $request)
    {   
        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $search = $request->input('search');
 
        $query = Sppd::orderBy($column, $dir)->whereHas('all_approve', function($query){
            $query->where('id', '=', $this->karyawan()->id);
        })->with('karyawan:id,id,nama,ttd,divisi_id,jabatan_id');
 
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('tujuan', 'like', '%' . $search . '%')
                    ->orWhere('pekerjaan', 'like', '%' . $search . '%');
            });
        }
 
        $data = $query->paginate($length);
        return $data;
    }

    // user get profil
    public function userShow()
    {
        $user = User::where('id', $this->user()->id)->first();
        return $user;
    }    

    // user update profil
    public function userUpdate(Request $request, $user)
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
            return $user;
        }
    }
}
