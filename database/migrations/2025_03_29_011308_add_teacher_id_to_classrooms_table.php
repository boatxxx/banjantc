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
        Schema::table('classrooms', function (Blueprint $table) {
            // เพิ่มคอลัมน์ teacher_id ให้เป็น unsignedBigInteger และสามารถเป็น null ได้
            $table->unsignedBigInteger('teacher_id')->nullable()->after('grade');
            
            // ตั้ง foreign key อ้างอิงไปยังตาราง teachers (สมมติว่ามี primary key เป็น id)
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            // ลบ foreign key
            $table->dropForeign(['teacher_id']);
            // ลบคอลัมน์ teacher_id
            $table->dropColumn('teacher_id');
        });
    
    }
};
