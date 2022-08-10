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
            $table->text('title');
            $table->longText('description');
            $table->foreignId('client_id')->constrained();
            $table->foreignId('area_id')->constrained();
            $table->foreignId('module_id')->constrained();
            $table->foreignId('problem_id')->constrained();
            $table->unsignedBigInteger('creator')->nullable();
            $table->foreign('creator')->references('id')->on('users');
            $table->unsignedBigInteger('assigned')->nullable();
            $table->foreign('assigned')->references('id')->on('users');
            $table->foreignId('group_id')->nullable()->constrained();
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('incident_states');
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
