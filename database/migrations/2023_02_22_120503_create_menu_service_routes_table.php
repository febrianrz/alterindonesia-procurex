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
        Schema::create('menu_service_routes', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('service_id');
            $table->string('route');
        });
        Schema::create('submenu_service_routes', function (Blueprint $table) {
            $table->unsignedBigInteger('submenu_id');
            $table->unsignedBigInteger('service_id');
            $table->string('route');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_service_routes');
        Schema::dropIfExists('submenu_service_routes');
    }
};
