<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->bigInteger('from')->nullable();
            // $table->bigInteger('to')->nullable();
            $table->unsignedBigInteger('from')->index();
            $table->foreign('from')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('to')->index();
            $table->foreign('to')->references('id')->on('users')->onDelete('cascade');

            $table->text('message')->nullable();
            $table->enum('read', [0,1])->default(0);
            $table->timestamps();
        });

        DB::table('messages')->insert([
            ['from' => 6, 'to' => 3, 'message' => 'siang pak, sppd ke sbp sudah dibuat', 'created_at' => new DateTime()],
            ['from' => 3, 'to' => 6, 'message' => 'ok, nanti saya approve', 'created_at' => new DateTime()],
            ['from' => 6, 'to' => 3, 'message' => 'baik.', 'created_at' => new DateTime()],
            ['from' => 6, 'to' => 7, 'message' => 'wan senin beragkat nya', 'created_at' => new DateTime()],
            ['from' => 7, 'to' => 6, 'message' => 'ok kak', 'created_at' => new DateTime()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
