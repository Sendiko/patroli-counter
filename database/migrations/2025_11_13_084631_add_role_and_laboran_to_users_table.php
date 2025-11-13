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
        Schema::table('users', function (Blueprint $table) {
            // 'laboran' or 'assistant'
            $table->string('role')->default('assistant')->after('email');
            
            // Links an assistant to a laboran (User -> User)
            $table->foreignId('laboran_id')->nullable()->after('role')
                ->constrained('users') // Points to id on users table
                ->nullOnDelete();      // If Laboran is deleted, keep the assistant
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
