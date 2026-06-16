<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validations', function (Blueprint $table) {
            $table->id('idValidation');

            $table->unsignedBigInteger('idDocument');
            $table->unsignedBigInteger('idUtilisateur');

            $table->string('decision');
            $table->text('commentaire')->nullable();
            $table->timestamp('dateValidation')->useCurrent();

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
        Schema::dropIfExists('validations');
    }
};