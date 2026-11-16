<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Photos and receipts a runner uploads for an errand (F13). Composition
 * with Errand: deleting the errand deletes its proofs.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('errand_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('file_path');
            $table->text('note')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proofs');
    }
};
