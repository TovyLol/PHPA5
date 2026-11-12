<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Maakt de koppeltabel 'band_user' aan (TO 4.1 en 5.3).
     * Hiermee kunnen meerdere gebruikers één band beheren
     * en kan één gebruiker meerdere bands beheren (many-to-many).
     */
    public function up(): void
    {
        Schema::create('band_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('band_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Een gebruiker kan maar één keer aan dezelfde band gekoppeld zijn.
            $table->unique(['band_id', 'user_id']);
        });
    }

    /**
     * Draait de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('band_user');
    }
};
