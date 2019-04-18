<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMydomain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('domain');
            $table->timestamp('expire_at')->nullable();
            $table->timestamp('create_at')->nullable();
            $table->integer('dayleft')->nullable();
            $table->string('owner');
            $table->string('register');
            $table->integer('send_noti_before');
            $table->integer('send_noti_after');
            $table->timestamp('last_send_noti')->nullable();
            $table->tinyInteger('notification')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domain');
    }
}
