<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->default('input'); // input, checkbox, radio, image, textarea, etc
            $table->string('group')->default('general');
            $table->string('label');
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->json('options')->nullable(); // options for select, checkbox, radio etc
            $table->json('info')->nullable(); // for storing data e.g. image information
            $table->string('help')->nullable(); // help text will show below text
            $table->string('help_label')->nullable(); // help text show beside label
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
