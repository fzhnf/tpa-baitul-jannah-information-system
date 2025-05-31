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
        Schema::create('student_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('makruj')->nullable();
            $table->string('mad')->nullable();
            $table->string('tajwid')->nullable();
            $table->string('kelancaran')->nullable();
            $table->string('fashohah')->nullable();
            $table->timestamps();

            // Ensure a student can only achieve a specific achievement once per session
            $table->unique(['student_id', 'class_session_id', 'achievement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_achievements');
    }
};
