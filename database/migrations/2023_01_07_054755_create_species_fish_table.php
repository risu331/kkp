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
        Schema::create('species_fish', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_fish_id')->nullable();
            $table->string('species')->nullable();
            $table->string('local')->nullable();
            $table->string('general')->nullable();
            $table->string('group')->nullable();
            $table->string('code')->nullable();
            $table->integer('born_start')->nullable();
            $table->integer('born_end')->nullable();
            $table->integer('mature_male_start')->nullable();
            $table->integer('mature_male_end')->nullable();
            $table->integer('mature_female_start')->nullable();
            $table->integer('mature_female_end')->nullable();
            $table->integer('mega_spawner')->nullable();
            $table->text('dtkn')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('type_fish_id')->references('id')->on('type_fish')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('species_fish');
    }
};
