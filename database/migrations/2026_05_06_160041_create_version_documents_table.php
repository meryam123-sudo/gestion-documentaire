<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('version_documents', function (Blueprint $table) {
            $table->id('idVersion');

            $table->unsignedBigInteger('idDocument');
            $table->string('numeroVersion');
            $table->string('fichier');
            $table->text('commentaire')->nullable();

            $table->foreign('idDocument')
                  ->references('idDocument')
                  ->on('documents')
                  ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['idDocument', 'numeroVersion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_documents');
    }
};