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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->float('amount' , 16 , 8 );
            $table->unsignedBigInteger('platform_id');
            $table->foreign('platform_id')->on('platforms')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->string('transaction')->nullable()->default(null);
            $table->string('coin_id');
            $table->string('coin_symbol');
            $table->string('coin_name');
            $table->string('coin_price');
            $table->string('price');
            $table->enum('status' , ['wait','paid','cancel'])->default('wait');
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
        Schema::dropIfExists('orders');
    }
};
