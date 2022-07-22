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
        Schema::create('progress_types', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->text('description');
            $table->integer('creator_visible');
            $table->integer('creator_email');
            $table->integer('internal_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progress_types');
    }
};
