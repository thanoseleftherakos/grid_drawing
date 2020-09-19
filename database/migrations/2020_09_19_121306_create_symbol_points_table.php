<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbol_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('symbol_id')->unsigned();
            $table->foreign('symbol_id')->references('id')->on('symbols')->onDelete("cascade");
            $table->integer('x');
            $table->integer('y');
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
        Schema::dropIfExists('symbol_points');
    }
}
