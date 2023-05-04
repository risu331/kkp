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
        Schema::create('data_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fishing_data_id')->nullable();
            $table->foreignId('species_fish_id')->nullable();
            $table->string('amount_fish')->nullable();
            $table->string('pt')->nullable();
            $table->string('ps')->nullable();
            $table->string('lt')->nullable();
            $table->string('weight')->nullable();
            $table->string('gender')->nullable();
            $table->string('clasp_length')->nullable();
            $table->string('gonad')->nullable();
            $table->string('amount_child')->nullable();
            $table->string('length_min_child')->nullable();
            $table->string('length_max_child')->nullable();
            $table->string('weight_child')->nullable();
            $table->string('sl')->nullable();
            $table->string('fl')->nullable();
            $table->string('tl')->nullable();
            $table->string('dw')->nullable();
            $table->string('m')->nullable();
            $table->string('p')->nullable();
            $table->string('t')->nullable();
            $table->string('mp')->nullable();
            $table->string('economy_price')->nullable();
            $table->string('total_economy_price')->nullable();
            $table->string('type_product')->nullable();
            $table->string('description')->nullable();
            $table->double('flat')->default(0);
            $table->double('flng')->default(0);
            $table->double('lat')->default(0);
            $table->double('lng')->default(0);
            $table->string('status')->nullable();
            $table->string('verification_by')->nullable();
            $table->text('dtkn')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fishing_data_id')->references('id')->on('fishing_data')->onDelete('set null');
            $table->foreign('species_fish_id')->references('id')->on('species_fish')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_collections');
    }
};
