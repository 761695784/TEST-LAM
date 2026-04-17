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
        Schema::create('destinataires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')
                  ->constrained('campagnes')
                  ->onDelete('cascade');
            $table->string('numero_telephone');
            $table->enum('statut_appel', [
                'PENDING',
                'SENT',
                'ANSWERED',
                'FAILED',
                'NO_ANSWER'
            ])->default('PENDING');
            $table->unsignedInteger('duree_appel')->nullable()->comment('Durée en secondes');
            $table->string('motif_echec')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinataires');
    }
};
