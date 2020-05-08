<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';

    protected $fillable = ['nama'];

    public function karyawan() {
        return $this->belongsToMany(Karyawan::class);
    }
}
