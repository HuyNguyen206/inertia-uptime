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
        Schema::create('endpoint_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endpoint_id')->constrained('end_points')->cascadeOnDelete();
            $table->string('status_code');
            $table->timestamp('checked_at');
            $table->text('response_body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endpoint_logs');
    }
};
