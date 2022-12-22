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
        Schema::create('admin_settings_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settings_id')->constrained('admin_settings')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name')->nullable();
            $table->char('country', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('street')->nullable();
            $table->string('cin')->nullable();
            $table->string('tin')->nullable();
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
        Schema::dropIfExists('admin_settings_billings');
    }
};
