<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Maakt de tabel 'bands' aan (TO 5.2).
     */
    public function up(): void
    {
        Schema::create('bands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();                    // voor nette URL's, bv. /epk/the-beatles
            $table->string('short_description', 255)->nullable(); // getoond in de zoekresultaten
            $table->text('biography')->nullable();
            $table->string('photo_path')->nullable();            // pad naar de foto in storage/app/public
            $table->string('bg_color', 7)->default('#ffffff');   // achtergrondkleur EPK-pagina (hex)
            $table->string('text_color', 7)->default('#000000'); // tekstkleur EPK-pagina (hex)
            $table->timestamps();

            $table->index('name'); // sneller zoeken op bandnaam
        });
    }

    /**
     * Draait de migration terug.
     */
    public function down(): void
    {
        Schema::dropIfExists('bands');
    }
};
