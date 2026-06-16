<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('idNotification');
            $table->unsignedBigInteger('idUtilisateur');
            $table->unsignedBigInteger('idDocument')->nullable();
            $table->string('typeNotification');
            $table->text('message');
            $table->boolean('lue')->default(false);
            $table->timestamp('dateEnvoi')->useCurrent();

            $table->foreign('idUtilisateur')
                ->references('idUtilisateur')
                ->on('utilisateurs')
                ->onDelete('cascade');

            $table->foreign('idDocument')
                ->references('idDocument')
                ->on('documents')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};