<?php

namespace App\Http\Controllers\SPPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Models\Sppd;
use App\Models\User;
use App\Models\Karyawan;
use Carbon\Carbon;
use Validator;

class SppdController extends Controller
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

    public function sppd(Request $request)
    {   
        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $search = $request->input('search');

        // $query = Sppd::orderBy($column, $dir)->whereHas('karyawan', function($query){
        //     $query->where('id', '=', $this->karyawan()->id);
        // })->with('karyawan:id,id,nama');
        $query = Sppd::orderBy($column, $dir)->where('karyawan_id', '=', $this->karyawan()->id)->whereHas('all_unapprove')->with('karyawan:id,id,nama');

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('tujuan', 'like', '%' . $search . '%')
                    ->orWhere('pekerjaan', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate($length);
        return $data;
    }

    public function sppdStore(Request $request)
    {
        $this->validate($request, [
            'tujuan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:600',
            'mulai' => 'required',
            'selesai' => 'required',
            'biaya_makan' => 'required|numeric|digits_between:1,10',
            'biaya_saku' => 'required|numeric|digits_between:1,10',
            'biaya_transport' => 'required|numeric|digits_between:1,10',
            'biaya_penginapan' => 'required|numeric|digits_between:1,10',
            'biaya_total' => 'required|numeric|digits_between:1,10',
            'biaya_sementara' => 'required|numeric|digits_between:1,10',

            'pekerja' => 'required',
            'pemberi_tugas_id' => 'required',
            'diketahui_id' => 'required',
        ]);

        $sppd = new Sppd;
        $sppd->karyawan_id = $this->karyawan()->id;
        $sppd->tujuan = $request->tujuan;
        $sppd->pekerjaan = $request->pekerjaan;
        $sppd->mulai = $request->mulai;
        $sppd->selesai = $request->selesai;
        $sppd->biaya_makan = $request->biaya_makan;
        $sppd->biaya_saku = $request->biaya_saku;
        $sppd->biaya_transport = $request->biaya_transport;
        $sppd->biaya_penginapan = $request->biaya_penginapan;
        $sppd->biaya_total = $request->biaya_total;
        $sppd->biaya_sementara = $request->biaya_sementara;

        if ($sppd->save()) {
            $sppd->karyawan()->attach($request->pekerja_id, ['status' => 'pekerja']);
            $sppd->karyawan()->attach($request->pemberi_tugas_id, ['status' => 'pemberi_tugas']);
            $sppd->karyawan()->attach($request->diketahui_id, ['status' => 'diketahui']);
            return $sppd;
        }
    }

    public function sppdUpdate(Request $request, $sppd)
    {
        $this->validate($request, [
            'tujuan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:600',
            'mulai' => 'required',
            'selesai' => 'required',
            'biaya_makan' => 'required|numeric|digits_between:1,10',
            'biaya_saku' => 'required|numeric|digits_between:1,10',
            'biaya_transport' => 'required|numeric|digits_between:1,10',
            'biaya_penginapan' => 'required|numeric|digits_between:1,10',
            'biaya_total' => 'required|numeric|digits_between:1,10',
            'biaya_sementara' => 'required|numeric|digits_between:1,10',

            'pekerja' => 'required',
            'pemberi_tugas_id' => 'required',
            'diketahui_id' => 'required',
        ]);

        $sppd = Sppd::findOrFail($sppd);
        $sppd->karyawan_id = $this->karyawan()->id;
        $sppd->tujuan = $request->tujuan;
        $sppd->pekerjaan = $request->pekerjaan;
        $sppd->mulai = $request->mulai;
        $sppd->selesai = $request->selesai;
        $sppd->biaya_makan = $request->biaya_makan;
        $sppd->biaya_saku = $request->biaya_saku;
        $sppd->biaya_transport = $request->biaya_transport;
        $sppd->biaya_penginapan = $request->biaya_penginapan;
        $sppd->biaya_total = $request->biaya_total;
        $sppd->biaya_sementara = $request->biaya_sementara;

        if ($sppd->update()) {
            $sppd->karyawan()->detach();
            $sppd->karyawan()->attach($request->pekerja_id, ['status' => 'pekerja']);
            $sppd->karyawan()->attach($request->pemberi_tugas_id, ['status' => 'pemberi_tugas']);
            $sppd->karyawan()->attach($request->diketahui_id, ['status' => 'diketahui']);
            return $sppd;
        }
    }

    public function sppdDestroy($sppd)
    {
        $sppd = Sppd::findOrFail($sppd);
        if ($sppd->delete()) {
            $sppd->karyawan()->detach();
            return $sppd;
        }
    }

    public function sppdPrint($sppd)
    {
        $sppd = Sppd::findOrFail($sppd);

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($this->htmlToPdf($sppd))->setPaper('a4');
        return $pdf->inline();
    }

    public function htmlToPdf($sppd)
    {
        $html = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>SPPD</title>
                <style>
                    .table_pekerja {
                        width: 100%;
                        margin-bottom: 20px;
                    }
                    
                    .table_pekerja, .table_pekerja tr, .table_pekerja td, .table_pekerja th {
                        border-collapse: collapse;
                        border: 1px solid black;
                    }   
                    
                    .table_pekerja th, .table_pekerja td {
                        padding: 10px;
                    }
                    
                    .table_rincian {
                        width: 100%;
                        margin-bottom: 20px;
                    }

                    .tb-flex {
                        display: flex;
                        flex-direction: row;
                        justify-content: space-between;
                    }

                    .table_head, .table_head tr, .table_head td {
                        border-collapse: collapse;
                        border: 1px solid black;
                    } 

                    .table_head {
                        width: 100%;
                    }

                    .table_efek {
                        width: 100%;
                    }

                    .table_efek, .table_efek tr, .table_efek td {
                        border: none;
                    }   
                </style>
            </head>
            <body>
                <div class="container" style="border:1px solid black; padding:5px;">
        ';


        $html.= '
                <div class="row">
                <div class="col-lg-12">

                <table class="table_head">
                    <tr>
                        <td style="text-align:center;"><img src="'.public_path("/images/karyawan/logo.png").'" alt="logo" width="100px"></td>
                        <td style="text-align:center;"><h2 style="text-transform:uppercase;">Surat Perintah Perjalanan Dinas <br> (SPPD)</h2></td>
                        <td>
                            <table class="table_efek">
                                <tr>
                                    <td>Kode Form</td>
                                    <td>:</td>
                                    <td>LJ-FM-HRD-14</td>
                                </tr>
                                <tr>
                                    <td>Edisi</td>
                                    <td>:</td>
                                    <td>01</td>
                                </tr>   
                                <tr>
                                    <td>Revisi</td>
                                    <td>:</td>
                                    <td>06</td>
                                </tr> 
                                <tr>
                                    <td>Tgl. Efektif</td>
                                    <td>:</td>
                                    <td>01 Feb 2016</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <h4>Ditugaskan Kepada :</h4>
                    <table class="table_pekerja">
                        <thead>
                            <tr>
                                <th class="text-center" style="text-align:center;">No.</th>
                                <th>Nama</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                            </tr>
                        </thead>
                        <tbody>
                ';

                            $i = 1;
                            foreach ($sppd->karyawan as $karyawan) {
                                if ($karyawan->pivot->status === 'pekerja') {
                                    $html .= '
                                        <tr>
                                            <td style="text-align:center;">'.$i.'</td>
                                            <td>'.$karyawan->nama.'</td>
                                            <td>'.$karyawan->divisi->nama.'</td>
                                            <td>'.$karyawan->jabatan->nama.'</td>
                                        </tr>
                                        ';
                                }
                                $i++;
                            }

                            $html .= '
                                <table class="table_rincian">
                                    <tbody>
                                        <tr>
                                            <td>Tujuan</td>
                                            <td>:</td>
                                            <td>'.$sppd->tujuan.'</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Pekerjaan</td>
                                            <td>:</td>
                                            <td>'.$sppd->pekerjaan.'</td>
                                        </tr>
                                        <tr>
                                            <td>Hari Kerja</td>
                                            <td>:</td>
                                            <td>
                                                '.(new Carbon($sppd->mulai))->format('d/m/Y H:m').' s.d '.(new Carbon($sppd->selesai))->format('d/m/Y H:m').'
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Biaya - biaya</td>
                                            <td>:</td>
                                            <td>
                                                <table style="width:100%">
                                                    <tr>
                                                        <td style="text-align:center;">1.</td>
                                                        <td>Uang Makan</td>
                                                        <td>:</td>
                                                        <td>Rp. '.number_format($sppd->biaya_makan,0,',','.').'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center;">2.</td>
                                                        <td>Uang Saku</td>
                                                        <td>:</td>
                                                        <td>Rp. '.number_format($sppd->biaya_saku,0,',','.').'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center;">3.</td>
                                                        <td>Uang Transport</td>
                                                        <td>:</td>
                                                        <td>Rp. '.number_format($sppd->biaya_transport,0,',','.').'</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center;">4.</td>
                                                        <td>Uang Penginapan</td>
                                                        <td>:</td>
                                                        <td>Rp. '.number_format($sppd->biaya_penginapan,0,',','.').'</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Biaya total yang dikeluarkan</td>
                                            <td>:</td>
                                            <td>Rp. '.number_format($sppd->biaya_total,0,',','.').'</td>
                                        </tr>
                                        <tr>
                                            <td>Biaya sementara</td>
                                            <td>:</td>
                                            <td>Rp. '.number_format($sppd->biaya_sementara,0,',','.').'</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div>Demikian surat ini dibuat agar dapat dilaksanakan dengan baik.</div>     
                                <div style="margin-bottom:30px;">Hajimena, '.(new Carbon($sppd->created_at))->format('d M Y').'</div> 
                                <table style="width:100%; margin-top:50px;">
                                    <tr>
                                    <td style="text-align:center; padding-right:100px;">
                                        <div class="d-block">Pemberi Tugas</div>
                                        ';
                                        foreach ($sppd->karyawan as $karyawan) {
                                            if ($karyawan->pivot->status === 'pemberi_tugas') {
                                                if ($karyawan->pivot->progres === 'diketahui' || $karyawan->pivot->progres === 'selesai') {
                                                    $html .= '
                                                    <img src="'.public_path("/images/karyawan/".$karyawan->ttd).'" alt="ttd" width="150px">
                                                    <div>'.$karyawan->nama.'</div>
                                                    ';
                                                } else {
                                                    $html .= '<div style="margin-top:50px;">'.$karyawan->nama.'</div>';
                                                }
                                            }
                                        }
                                        $html .='
                                        <div>Dir/Mgr/Kabag</div>
                                    </td>
                                    <td style="text-align:center; padding-left:100px;">
                                        <div>Diketahui</div>
                                        ';
                                        foreach ($sppd->karyawan as $karyawan) {
                                            if ($karyawan->pivot->status === 'diketahui') {
                                                if ($karyawan->pivot->progres === 'selesai') {
                                                    $html .= '
                                                    <img src="'.public_path("/images/karyawan/".$karyawan->ttd).'" alt="ttd" width="150px">
                                                    <div>'.$karyawan->nama.'</div>
                                                    ';
                                                } else {
                                                    $html .= '<div style="margin-top:50px;">'.$karyawan->nama.'</div>';
                                                }
                                            }
                                        }                                        
                                        $html .='
                                        <div>Mgr / Kabag HRD</div>
                                    </td>
                                </tr>
                                <table>    
                            ';
                            
        $html .= '      </tbody>
                    </table>
                    </div> 
                    </div> 
                </div>   
            </body>
        </html>
        ';

        return $html;
    }
}
