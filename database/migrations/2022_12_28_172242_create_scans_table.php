<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('description', 2000)->nullable();
            $table->string('website')->nullable();
            $table->string('discord')->nullable();
            $table->string('facebook')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scans');
    }
};
