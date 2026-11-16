<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Verwijdert de onderdelen voor e-mailfuncties die de applicatie niet gebruikt:
     * - users.email_verified_at (e-mailverificatie)
     * - password_reset_tokens (wachtwoord-reset via e-mail)
     *
     * Het e-mailadres zelf blijft bestaan: daarmee log je in (TO 5.2).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });

        Schema::dropIfExists('password_reset_tokens');
    }

    /**
     * Zet de onderdelen terug zoals Laravel ze standaard aanmaakt.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
};
