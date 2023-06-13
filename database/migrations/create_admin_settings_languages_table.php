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
        Schema::create('admin_settings_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settings_id')->constrained('admin_settings')->cascadeOnDelete()->cascadeOnUpdate();
            $table->char('code', 6);
            $table->boolean('is_default')->default(false)->nullable();
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
        Schema::dropIfExists('admin_settings_languages');
    }
};
