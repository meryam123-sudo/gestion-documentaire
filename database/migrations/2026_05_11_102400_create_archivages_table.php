<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archivages', function (Blueprint $table) {
            $table->id('idArchivage');

            $table->unsignedBigInteger('idDocument');
            $table->unsignedBigInteger('idUtilisateur');

            $table->string('emplacementArchive');
            $table->text('motifArchivage')->nullable();
            $table->timestamp('dateArchivage')->useCurrent();

            $table->foreign('idDocument')
                ->references('idDocument')
                ->on('documents')
                ->onDelete('cascade');

            $table->foreign('idUtilisateur')
                ->references('idUtilisateur')
                ->on('utilisateurs')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivages');
    }
};