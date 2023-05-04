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
        Schema::create('fishing_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('ship_id')->nullable();
            $table->foreignId('landing_site_id')->nullable();
            $table->foreignId('fishing_gear_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('operational_day')->default(0);
            $table->string('travel_day')->default(0);
            $table->string('setting')->nullable();
            $table->string('area')->nullable();
            $table->double('lat')->default(0);
            $table->double('lng')->default(0);
            $table->double('flat')->default(0);
            $table->double('flng')->default(0);
            $table->string('miles')->nullable();
            $table->date('enumeration_time')->nullable();
            $table->string('status')->nullable();
            $table->string('gt')->nullable();
            $table->string('total_other_fish')->nullable();
            $table->boolean('is_htu')->default(0);
            $table->string('verification_by')->nullable();
            $table->text('dtkn')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ship_id')->references('id')->on('ships')->onDelete('set null');
            $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('set null');
            $table->foreign('fishing_gear_id')->references('id')->on('fishing_gears')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fishing_data');
    }
};
