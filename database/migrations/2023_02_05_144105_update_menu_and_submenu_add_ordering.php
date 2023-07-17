<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string("path")->nullable()->after("icon");
            $table->tinyInteger("order_no")->default(0)->after("path");
        });

        Schema::table('sub_menus', function (Blueprint $table) {
            $table->string("path")->nullable()->after("icon");
            $table->tinyInteger("order_no")->default(0)->after("path");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
