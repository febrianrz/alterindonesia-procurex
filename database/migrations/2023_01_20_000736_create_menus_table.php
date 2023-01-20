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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId("module_id")->constrained()->onUpdate("cascade")->onDelete("restrict");
            $table->string("name", 50);
            $table->string("icon", 50)->nullable();
            $table->enum('status', ['ACTIVE','INACTIVE'])->default('ACTIVE');
            $table->bigInteger("created_by");
            $table->bigInteger("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
