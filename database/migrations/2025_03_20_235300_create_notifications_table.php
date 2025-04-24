<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_id'); // อ้างอิงไปยังห้องเรียน
            $table->string('title'); // หัวข้อแจ้งเตือน
            $table->text('message'); // เนื้อหาการแจ้งเตือน
            $table->boolean('is_read')->default(false); // สถานะอ่านแล้วหรือยัง
            $table->timestamps();
    
            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
