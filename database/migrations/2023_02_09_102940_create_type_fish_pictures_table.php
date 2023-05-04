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
        Schema::create('type_fish_pictures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_fish_id')->nullable();
            $table->integer('index')->nullable();
            $table->string('title')->nullable();
            $table->boolean('is_required')->default(0);
            $table->text('sample_image')->nullable();
            $table->text('sample_description')->nullable();
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
        Schema::dropIfExists('type_fish_pictures');
    }
};
