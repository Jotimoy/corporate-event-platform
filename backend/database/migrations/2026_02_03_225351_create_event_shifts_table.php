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
    Schema::create('event_shifts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('event_id')->constrained()->onDelete('cascade');
        $table->string('shift_name'); // setup, registration, main, cleanup
        $table->date('shift_date');
        $table->time('start_time');
        $table->time('end_time');
        $table->integer('required_employee');
        $table->enum('status', ['open', 'full', 'completed', 'cancelled'])->default('open');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_shifts');
    }
};
