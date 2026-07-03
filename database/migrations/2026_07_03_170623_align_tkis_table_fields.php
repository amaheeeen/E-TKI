<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tkis', function (Blueprint $table) {
            $table->dropColumn('height_weight');
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('verification_status')->default('Pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tkis', function (Blueprint $table) {
            $table->string('height_weight')->nullable();
            $table->dropColumn(['height', 'weight', 'verification_status']);
        });
    }
};
