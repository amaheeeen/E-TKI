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
        Schema::create('physical_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tki_id')->constrained('tkis')->onDelete('cascade');
            $table->enum('document_type', ['passport', 'visa', 'medical', 'id_card']);
            $table->string('current_location')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_documents');
    }
};
