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
        Schema::create('manga_staff', function (Blueprint $table) {
            $table->string('office')->nullable();

            $table->uuid('people_id');
            $table
                ->foreign('people_id')
                ->references('id')
                ->on('peoples')
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
        Schema::dropIfExists('mangas_staff');
    }
};
