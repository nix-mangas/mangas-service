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
        Schema::create('scan_manga', function (Blueprint $table) {
            $table->uuid('scan_id');
            $table
                ->foreign('scan_id')
                ->references('id')
                ->on('scans')
                ->onDelete('cascade');

            $table->uuid('manga_id');
            $table
                ->foreign('manga_id')
                ->references('id')
                ->on('mangas')
                ->onDelete('cascade');

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
        Schema::dropIfExists('scan_manga');
    }
};
