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
        Schema::create('rule_conditions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rule_id');
            $table->text('value');
            $table->text('operator');
            $table->bigInteger('min');
            $table->bigInteger('max');
            $table->bigInteger('equal');
            $table->text('helper');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rule_conditions');
    }
};
