<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sppd extends Model
{
    protected $table = 'sppd';

    protected $fillable = ['karyawan_id', 'tujuan', 'pekerjaan', 'mulai', 'selesai', 'biaya_makan', 'biaya_saku', 'biaya_transport', 'biaya_penginapan', 'biaya_total', 'biaya_sementara'];

    public function karyawan() {
        return $this->belongsToMany(Karyawan::class)->with('divisi:id,id,nama', 'jabatan:id,id,nama')->withPivot('status', 'progres');
    }

    public function pemberi_unapprove() {
        return $this->karyawan()->wherePivot('progres', 'pemberi_tugas');
    }

    public function diketahui_unapprove() {
        return $this->karyawan()->wherePivot('progres', 'diketahui');
    }

    public function all_approve() {
        return $this->karyawan()->wherePivot('progres', 'selesai');
    }

    public function all_unapprove() {
        return $this->karyawan()->wherePivot('progres', '!=', 'selesai');
    }
}
