<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('feedback_replies', function (Blueprint $table) {
            $table->id();
            $table->string('feedback_replies');
            $table->foreignId('feedback_id')->constrained('feedback')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('feedback_replies');
    }
};
