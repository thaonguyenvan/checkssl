<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLimitDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('limit', function($table) {
            $table->integer('limit_domain')->nullable();
        });
        DB::table('limit')->update(['limit_domain' => 10]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('limit', function($table) {
            $table->dropColumn('limit_domain');
        });
    }
}
