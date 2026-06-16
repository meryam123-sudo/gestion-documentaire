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
        Schema::create('journal_audits', function (Blueprint $table) {

            $table->id('idAudit');

            $table->unsignedBigInteger('idUtilisateur')->nullable();

            $table->string('action');

            $table->text('details')->nullable();

            $table->string('adresseIp')->nullable();

            $table->timestamp('dateAction')->useCurrent();

            $table->timestamps();

            $table->foreign('idUtilisateur')
                ->references('idUtilisateur')
                ->on('utilisateurs')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_audits');
    }
};