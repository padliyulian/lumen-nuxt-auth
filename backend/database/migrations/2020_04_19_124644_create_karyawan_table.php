<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama')->nullable();
            $table->timestamps();
        });

        Schema::create('jabatan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama')->nullable();
            $table->timestamps();
        });

        Schema::create('karyawan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('divisi_id')->index();
            $table->foreign('divisi_id')->references('id')->on('divisi')->onDelete('cascade');
            $table->unsignedBigInteger('jabatan_id')->index();
            $table->foreign('jabatan_id')->references('id')->on('jabatan')->onDelete('cascade');

            $table->string('nama')->nullable();
            $table->enum('jenis_kelamin', ['pria','wanita'])->nullable();
            $table->string('status')->nullable();
            $table->date('masuk_kerja')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->text('alamat')->nullable();
            $table->string('ttd')->nullable();
            $table->timestamps();
        });

        Schema::create('sppd', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('karyawan_id')->index();
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');

            $table->string('tujuan')->nullable();
            $table->text('pekerjaan')->nullable();
            $table->datetime('mulai')->nullable();
            $table->datetime('selesai')->nullable();
            $table->bigInteger('biaya_makan')->nullable()->default(0);
            $table->bigInteger('biaya_saku')->nullable()->default(0);
            $table->bigInteger('biaya_transport')->nullable()->default(0);
            $table->bigInteger('biaya_penginapan')->nullable()->default(0);
            $table->bigInteger('biaya_total')->nullable()->default(0);
            $table->bigInteger('biaya_sementara')->nullable()->default(0);

            // $table->string('pemberi_tugas')->nullable();
            // $table->string('diketahui')->nullable();
            $table->timestamps();
        });

        // Schema::create('ijin', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('tujuan')->nullable();
        //     $table->timestamps();
        // });

        // table pivot
        // Schema::create('divisi_karyawan', function (Blueprint $table) {
        //     $table->unsignedBigInteger('divisi_id')->index();
        //     $table->foreign('divisi_id')->references('id')->on('divisi')->onDelete('cascade');

        //     $table->unsignedBigInteger('karyawan_id')->index();
        //     $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
        // });

        // Schema::create('jabatan_karyawan', function (Blueprint $table) {
        //     $table->unsignedBigInteger('jabatan_id')->index();
        //     $table->foreign('jabatan_id')->references('id')->on('jabatan')->onDelete('cascade');

        //     $table->unsignedBigInteger('karyawan_id')->index();
        //     $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
        // });

        Schema::create('karyawan_sppd', function (Blueprint $table) {
            $table->unsignedBigInteger('karyawan_id')->index();
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');

            $table->unsignedBigInteger('sppd_id')->index();
            $table->foreign('sppd_id')->references('id')->on('sppd')->onDelete('cascade');

            // pekerja, pemberi tugas, diketahui
            $table->string('status')->nullable();
            $table->enum('progres', ['pemberi_tugas', 'diketahui', 'selesai'])->default('pemberi_tugas');
        });

        // dummy data
        DB::table('divisi')->insert([
            ['nama' => 'HRD'],
            ['nama' => 'IT'],
            ['nama' => 'TAX'],
            ['nama' => 'PURCHASING'],
            ['nama' => 'MARKETING'],
            ['nama' => 'LEGAL'],
            ['nama' => 'UMUM'],
            ['nama' => 'FINANCE'],
            ['nama' => 'MR'],
            ['nama' => 'KENDARAAN'],
        ]);

        DB::table('jabatan')->insert([
            ['nama' => 'Manager'],
            ['nama' => 'Kabag'],
            ['nama' => 'Kasie'],
            ['nama' => 'Staff'],
            ['nama' => 'Supir'],
        ]);

        DB::table('karyawan')->insert([
            ['user_id' => 3, 'divisi_id' => 3, 'jabatan_id' => 1, 'nama' => 'Andri', 'jenis_kelamin' => 'pria', 'no_telp' => '123', 'email' => 'andri@lambangjayagroup.com', 'photo' => 'karyawan1.jpg', 'ttd' => 'ttd1.jpg'],
            ['user_id' => 4, 'divisi_id' => 9, 'jabatan_id' => 2, 'nama' => 'Muhammad Hasan', 'jenis_kelamin' => 'pria', 'no_telp' => '124', 'email' => 'hasan@lambangjayagroup.com', 'photo' => 'karyawan2.jpg', 'ttd' => 'ttd2.jpg'],
            ['user_id' => 5, 'divisi_id' => 1, 'jabatan_id' => 2, 'nama' => 'Merika Hastuti', 'jenis_kelamin' => 'wanita', 'no_telp' => '125', 'email' => 'rika.hrd@lambangjayagroup.com', 'photo' => 'karyawan3.jpg', 'ttd' => 'ttd3.jpg'],
            ['user_id' => 6, 'divisi_id' => 2, 'jabatan_id' => 2, 'nama' => 'Padli Yulian', 'jenis_kelamin' => 'pria', 'no_telp' => '126', 'email' => 'padli.it@lambangjayagroup.com', 'photo' => 'karyawan4.jpg', 'ttd' => 'ttd4.jpg'],
            ['user_id' => 7, 'divisi_id' => 2, 'jabatan_id' => 4, 'nama' => 'Iwan Saputra', 'jenis_kelamin' => 'pria', 'no_telp' => '127', 'email' => 'iwan.it@lambangjayagroup.com', 'photo' => 'karyawan5.jpg', 'ttd' => 'ttd5.jpg'],
            ['user_id' => 8, 'divisi_id' => 6, 'jabatan_id' => 1, 'nama' => 'Tigor Silitonga', 'jenis_kelamin' => 'pria', 'no_telp' => '128', 'email' => 'tiggor@lambangjayagroup.com', 'photo' => 'karyawan6.jpg', 'ttd' => 'ttd6.jpg'],
            ['user_id' => 9, 'divisi_id' => 10, 'jabatan_id' => 5, 'nama' => 'Farid', 'jenis_kelamin' => 'pria', 'no_telp' => '129', 'email' => 'farid@lambangjayagroup.com', 'photo' => 'karyawan7.jpg', 'ttd' => 'ttd7.jpg'],
        ]);

        DB::table('sppd')->insert([
            ['karyawan_id' => 4, 'tujuan' => 'PT SPM1', 'pekerjaan' => 'Instalasi jaringan internet', 'mulai' => '2020-04-21 08:00:00', 'selesai' => '2020-04-26 17:00:00', 'biaya_makan' => 900000, 'biaya_saku' => 0, 'biaya_transport' => 0, 'biaya_penginapan' => 0, 'biaya_total' => 0, 'biaya_sementara' => 1500000, 'created_at' => new DateTime()],
            ['karyawan_id' => 2, 'tujuan' => 'PT SPM2', 'pekerjaan' => 'Uji laporan limbah', 'mulai' => '2020-05-02 08:00:00', 'selesai' => '2020-05-04 17:00:00', 'biaya_makan' => 0, 'biaya_saku' => 0, 'biaya_transport' => 0, 'biaya_penginapan' => 0, 'biaya_total' => 0, 'biaya_sementara' => 500000, 'created_at' => new DateTime()],
            ['karyawan_id' => 4, 'tujuan' => 'PT SBP', 'pekerjaan' => 'perbaikan inet', 'mulai' => '2020-05-02 08:00:00', 'selesai' => '2020-05-04 17:00:00', 'biaya_makan' => 0, 'biaya_saku' => 0, 'biaya_transport' => 0, 'biaya_penginapan' => 0, 'biaya_total' => 0, 'biaya_sementara' => 5000000, 'created_at' => new DateTime()],
        ]);

        // DB::table('divisi_karyawan')->insert([
        //     ['divisi_id' => 3, 'karyawan_id' => 1],
        //     ['divisi_id' => 9, 'karyawan_id' => 2],
        //     ['divisi_id' => 1, 'karyawan_id' => 3],
        //     ['divisi_id' => 2, 'karyawan_id' => 4],
        //     ['divisi_id' => 2, 'karyawan_id' => 5],
        //     ['divisi_id' => 6, 'karyawan_id' => 6],
        //     ['divisi_id' => 10, 'karyawan_id' => 7],
        // ]);

        // DB::table('jabatan_karyawan')->insert([
        //     ['jabatan_id' => 1, 'karyawan_id' => 1],
        //     ['jabatan_id' => 2, 'karyawan_id' => 2],
        //     ['jabatan_id' => 2, 'karyawan_id' => 3],
        //     ['jabatan_id' => 2, 'karyawan_id' => 4],
        //     ['jabatan_id' => 4, 'karyawan_id' => 5],
        //     ['jabatan_id' => 1, 'karyawan_id' => 6],
        //     ['jabatan_id' => 5, 'karyawan_id' => 7],
        // ]);

        DB::table('karyawan_sppd')->insert([
            ['karyawan_id' => 4, 'sppd_id' => 1, 'status' => 'pekerja', 'progres' => 'pemberi_tugas'],
            ['karyawan_id' => 5, 'sppd_id' => 1, 'status' => 'pekerja', 'progres' => 'pemberi_tugas'],
            ['karyawan_id' => 1, 'sppd_id' => 1, 'status' => 'pemberi_tugas', 'progres' => 'pemberi_tugas'],
            ['karyawan_id' => 3, 'sppd_id' => 1, 'status' => 'diketahui', 'progres' => 'pemberi_tugas'],

            ['karyawan_id' => 2, 'sppd_id' => 2, 'status' => 'pekerja', 'progres' => 'diketahui'],
            ['karyawan_id' => 7, 'sppd_id' => 2, 'status' => 'pekerja', 'progres' => 'diketahui'],
            ['karyawan_id' => 6, 'sppd_id' => 2, 'status' => 'pemberi_tugas', 'progres' => 'diketahui'],
            ['karyawan_id' => 3, 'sppd_id' => 2, 'status' => 'diketahui', 'progres' => 'diketahui'],

            ['karyawan_id' => 4, 'sppd_id' => 3, 'status' => 'pekerja', 'progres' => 'selesai'],
            ['karyawan_id' => 1, 'sppd_id' => 3, 'status' => 'pemberi_tugas', 'progres' => 'selesai'],
            ['karyawan_id' => 3, 'sppd_id' => 3, 'status' => 'diketahui', 'progres' => 'selesai'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan');
        Schema::dropIfExists('divisi');
        Schema::dropIfExists('jabatan');
        Schema::dropIfExists('sppd');

        // Schema::dropIfExists('divisi_karyawan');
        // Schema::dropIfExists('jabatan_karyawan');
        Schema::dropIfExists('karyawan_sppd');
    }
}
