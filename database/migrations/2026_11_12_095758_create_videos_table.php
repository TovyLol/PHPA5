<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Maakt de tabel 'videos' aan (TO 4.4 en 5.2).
     * Eén band kan meerdere video's hebben (one-to-many).
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('band_id')->constrained()->cascadeOnDelete();
            $table->string('youtube_url');      // de link zoals de gebruiker hem invult
            $table->string('youtube_id', 20);   // het video-ID, voor de embed-URL
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Draait de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
