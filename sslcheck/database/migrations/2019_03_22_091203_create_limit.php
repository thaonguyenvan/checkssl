<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('limit_ssl');
            $table->integer('limit_email');
            $table->integer('limit_tele');
            $table->integer('send_noti_before');
            $table->integer('send_noti_after');
            $table->timestamps();
        });

        DB::table('limit')->insert([
            ['limit_ssl' => 10, 'limit_email' => 2, 'limit_tele'=>1,'send_noti_before'=> 60, 'send_noti_after' => 30],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('limit');
    }
}
