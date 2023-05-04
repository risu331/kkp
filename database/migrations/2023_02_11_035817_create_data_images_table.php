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
        Schema::create('data_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_collection_id')->nullable();
            $table->string('title')->nullable();
            $table->text('image')->nullable();
            $table->string('format')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('data_collection_id')->references('id')->on('data_collections')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_images');
    }
};
