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
        Schema::create('chapters', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->float('number', 8, 2);
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('cover')->nullable();
            $table->string('scans_supports')->nullable();

            $table->uuid('manga_id');
            $table->foreign('manga_id')
                  ->references('id')
                  ->on('mangas')
                  ->onDelete('cascade');

            $table->uuid('scan_id')->nullable();
            $table->foreign('scan_id')->references('id')->on('scans');

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
        Schema::dropIfExists('chapters');
    }
};
