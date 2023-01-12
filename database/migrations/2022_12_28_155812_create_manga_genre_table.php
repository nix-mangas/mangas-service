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
        Schema::create('manga_genre', function (Blueprint $table) {
            $table->uuid('genre_id');
            $table
                ->foreign('genre_id')
                ->references('id')
                ->on('genres')
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
        Schema::dropIfExists('mangas_genres');
    }
};
