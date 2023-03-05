\<?php

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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('scheme');
            $table->string('domain');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
        \App\Models\Site::create([
            'user_id' => \App\Models\User::first()->id,
            'domain' => 'https://codecourse.com'
        ]);
        \App\Models\Site::create([
            'user_id' => \App\Models\User::first()->id,
            'domain' => 'https://gamek.com',
            'is_default' => true
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
