<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = ['nama', 'jenis_kelamin', 'status', 'masuk_kerja', 'no_telp', 'email', 'alamat', 'ttd'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi() {
        return $this->belongsTo(Divisi::class);
    }

    public function jabatan() {
        return $this->belongsTo(Jabatan::class);
    }

    public function sppd() {
        return $this->belongsToMany(Sppd::class)->withPivot('status', 'progres');
    }
}
