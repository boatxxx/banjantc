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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('level');
            $table->string('courseType');
            $table->string('major');
            $table->date('registerDate');
            $table->string('receipt'); // Store path to receipt file
            $table->timestamps();
        });

        // Create the 'subjects' table
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade'); // Foreign key to registrations table
            $table->string('semester');
            $table->string('subject');
            $table->string('subject_code'); // Store subject code
            $table->string('grade');
            $table->string('teacher');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('registrations');    }
};
