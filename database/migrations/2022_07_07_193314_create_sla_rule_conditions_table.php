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
        Schema::create('sla_rule_conditions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rule_id');
            $table->text('condition');
            $table->text('value');
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
        Schema::dropIfExists('sla_rule_conditions');
    }
};
