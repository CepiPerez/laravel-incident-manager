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
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('incident_id');
            $table->bigInteger('progress_type_id');
            $table->text('description')->nullable();
            $table->bigInteger('user_id');
            $table->bigInteger('assigned_to')->nullable();
            $table->bigInteger('assigned_group_to')->nullable();
            $table->bigInteger('prev_status')->nullable();
            $table->bigInteger('prev_assigned')->nullable();
            $table->bigInteger('prev_assigned_group')->nullable();
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
        Schema::dropIfExists('progress');
    }
};
