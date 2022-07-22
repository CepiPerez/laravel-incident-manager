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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('area_id');
            $table->bigInteger('module_id');
            $table->bigInteger('problem_id');
            $table->text('title');
            $table->longText('description');
            $table->bigInteger('creator');
            $table->bigInteger('assigned')->nullable();
            $table->bigInteger('group_id')->nullable();
            $table->bigInteger('status_id');
            $table->integer('priority');
            $table->integer('sla');
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
        Schema::dropIfExists('incidents');
    }
};
