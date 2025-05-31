<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('semester_class_teacher', function (Blueprint $table) {
            $table->foreignId('semester_class_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();

            // Primary key
            $table->primary(['semester_class_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_class_teachers');
    }
};
