<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('class_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->json('grade_aspects'); // Store aspects and their notes as JSON
            $table->timestamps();

            // Ensure unique combination of semester_class and student
            $table->unique(['semester_class_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_notes');
    }
};
