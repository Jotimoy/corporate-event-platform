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
    Schema::create('shift_applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('event_shift_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->enum('status', ['applied', 'approved', 'rejected'])->default('applied');
        $table->timestamps();

        $table->unique(['event_shift_id', 'user_id']); // duplicate apply prevent
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_applications');
    }
};
