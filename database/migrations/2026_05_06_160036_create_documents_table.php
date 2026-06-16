<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id('idDocument');

            $table->string('titre');
            $table->string('reference')->unique();
            $table->text('description')->nullable();
            $table->string('typeDocument');
            $table->string('niveauConfidentialite')->default('interne');

            $table->string('fichier');
            $table->string('statut')->default('brouillon');

            $table->unsignedBigInteger('idCategorie');
            $table->unsignedBigInteger('idDossier')->nullable();
            $table->unsignedBigInteger('idUtilisateur');

            $table->foreign('idCategorie')->references('idCategorie')->on('categorie_documents')->onDelete('cascade');
            $table->foreign('idDossier')->references('idDossier')->on('dossiers')->onDelete('set null');
            $table->foreign('idUtilisateur')->references('idUtilisateur')->on('utilisateurs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};