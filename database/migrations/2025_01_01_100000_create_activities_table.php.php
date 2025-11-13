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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The assistant
            $table->foreignId('room_id')->constrained()->onDelete('cascade');

            // We store the enum as a string
            $table->string('type'); // maintenance, administration, inspection

            $table->text('note')->nullable();
            $table->string('proof_image_path')->nullable(); // Optional upload

            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable(); // Null means currently "Checked In"

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
