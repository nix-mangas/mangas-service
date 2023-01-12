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
        Schema::table('chapters', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable();
            $table->timestamp('published_plus_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn('published_at');
            $table->dropColumn('published_plus_at');
        });
    }
};
