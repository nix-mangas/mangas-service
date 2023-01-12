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
    public function up(): void
    {
        Schema::create('chapter_pages', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->integer('page_number');
            $table->string('page_url')->nullable();

            $table->uuid('chapter_id');
            $table
                ->foreign('chapter_id')
                ->references('id')
                ->on('chapters')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_pages');
    }
};
