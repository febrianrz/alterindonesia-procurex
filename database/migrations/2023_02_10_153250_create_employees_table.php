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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_no')->unique();
            $table->string('nama')->nullable();
            $table->string('gender')->nullable();
            $table->string('agama')->nullable();
            $table->string('status_kawin')->nullable();
            $table->tinyInteger('anak')->nullable();
            $table->string('mdg')->nullable();
            $table->string('emp_grade')->nullable();
            $table->string('emp_grade_title')->nullable();
            $table->string('area')->nullable();
            $table->string('area_title')->nullable();
            $table->string('sub_area')->nullable();
            $table->string('sub_area_title')->nullable();
            $table->string('contract')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('company')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('employee_status')->nullable();
            $table->string('email')->nullable();
            $table->string('hp')->nullable();
            $table->string('tgl_lahir')->nullable();
            $table->string('pos_id')->nullable();
            $table->string('pos_title')->nullable();
            $table->string('sup_pos_id')->nullable();
            $table->string('pos_grade')->nullable();
            $table->string('pos_kategori')->nullable();
            $table->string('org_id')->nullable();
            $table->string('org_title')->nullable();
            $table->string('dept_id')->nullable();
            $table->string('dept_title')->nullable();
            $table->string('komp_id')->nullable();
            $table->string('komp_title')->nullable();
            $table->string('dir_id')->nullable();
            $table->string('dir_title')->nullable();
            $table->string('pos_level')->nullable();
            $table->string('sup_emp_no')->nullable();
            $table->string('bag_id')->nullable();
            $table->string('bag_title')->nullable();
            $table->string('seksi_id')->nullable();
            $table->string('seksi_title')->nullable();
            $table->string('pre_name_title')->nullable();
            $table->string('post_name_title')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('mdg_date')->nullable();
            $table->string('PayScale')->nullable();
            $table->string('cc_code')->nullable();
            $table->boolean('status')->default(false);
            $table->string('message')->nullable();
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
        Schema::dropIfExists('employees');
    }
};
