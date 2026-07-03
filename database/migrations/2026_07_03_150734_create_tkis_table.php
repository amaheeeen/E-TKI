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
        Schema::create('tkis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_id')->constrained('users')->onDelete('cascade');
            $table->date('registration_date')->nullable();
            $table->string('full_name');
            $table->enum('gender', ['L', 'P']);
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('education')->nullable();
            $table->string('destination_country')->nullable();
            $table->enum('experience_type', ['NON', 'EX'])->nullable();
            $table->string('height_weight')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('medical_date')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('visa_status')->nullable();
            $table->date('departure_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tkis');
    }
};
