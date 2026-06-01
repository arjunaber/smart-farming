<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rag_documents', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename');
            $table->string('stored_filename')->unique();
            $table->string('file_path');
            $table->enum('file_type', ['pdf', 'txt', 'docx']);
            $table->unsignedBigInteger('file_size'); // bytes
            $table->string('mime_type');

            // Processing status
            $table->enum('status', [
                'uploaded',     // File uploaded, pending processing
                'processing',   // Being chunked / embedded
                'processed',    // Ready to be queried
                'failed',       // Error during processing
            ])->default('uploaded');

            $table->text('error_message')->nullable();

            // Metadata for LLM team
            $table->string('collection_name')->default('default'); // namespace / collection di vector DB
            $table->integer('chunk_count')->nullable();           // Jumlah chunk yang dihasilkan
            $table->integer('token_count')->nullable();           // Estimasi total token

            // Who uploaded
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('collection_name');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rag_documents');
    }
};
