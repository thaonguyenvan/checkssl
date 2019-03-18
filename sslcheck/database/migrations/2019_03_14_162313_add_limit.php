<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->integer('limit_ssl')->nullable();
            $table->integer('limit_email')->nullable();
            $table->integer('limit_tele')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('limit_ssl');
            $table->dropColumn('limit_email');
            $table->dropColumn('limit_tele');
        });
    }
}
