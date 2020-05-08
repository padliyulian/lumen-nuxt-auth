<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->enum('sex', ['M', 'F'])->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone')->unique()->nullable();
            $table->string('avatar')->nullable();

            $table->enum('isLogin', [0,1])->nullable()->default(0);
            $table->datetime('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('last_login_agent')->nullable();
            $table->timestamps();
        });

        // DB::table('users')->insert([
        //     ['name' => 'superadmin', 'email' => 'superadmin@mail.com', 'email_verified_at' => new DateTime(), 'username' => 'superadmin', 'password' => Hash::make('superadmin'), 'created_at' => new DateTime()],
        //     ['name' => 'admin', 'email' => 'admin@mail.com', 'email_verified_at' => new DateTime(), 'username' => 'admin', 'password' => Hash::make('admin'), 'created_at' => new DateTime()],
        //     ['name' => 'operator', 'email' => 'operator@mail.com', 'email_verified_at' => new DateTime(), 'username' => 'operator', 'password' => Hash::make('operator'), 'created_at' => new DateTime()],
        //     ['name' => 'karyawan', 'email' => 'karyawan@mail.com', 'email_verified_at' => new DateTime(), 'username' => 'karyawan', 'password' => Hash::make('karyawan'), 'created_at' => new DateTime()],
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
