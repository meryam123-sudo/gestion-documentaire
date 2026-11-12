<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\CategorieDocumentController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\VersionDocumentController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\ArchivageController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATEUR PRINCIPAL
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Administrateur Principal')->group(function () {

        Route::apiResource('roles', RoleController::class);

        Route::apiResource('utilisateurs', UtilisateurController::class);

    });

    /*
    |--------------------------------------------------------------------------
    | CATÉGORIES & DOSSIERS
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Administrateur Principal,Gestionnaire documentaire')->group(function () {

        Route::apiResource('categories', CategorieDocumentController::class);

        Route::apiResource('dossiers', DossierController::class);

    });

    /*
    |--------------------------------------------------------------------------
    | DOCUMENTS
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Administrateur Principal,Gestionnaire documentaire,Archiviste,Consultant autorisé')->group(function () {

        Route::apiResource('documents', DocumentController::class);

        Route::get('/documents/{id}/versions', [VersionDocumentController::class, 'index']);

        Route::post('/documents/{id}/versions', [VersionDocumentController::class, 'store']);

        Route::post('/documents/{id}/soumettre', [DocumentController::class, 'soumettre']);

        Route::get('/documents/{id}/telecharger', [DocumentController::class, 'telecharger']);

    });

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Administrateur Principal,Responsable de validation')->group(function () {

        Route::get('/validations/documents-soumis', [ValidationController::class, 'documentsSoumis']);

        Route::post('/validations/{id}/valider', [ValidationController::class, 'valider']);

        Route::post('/validations/{id}/rejeter', [ValidationController::class, 'rejeter']);

        Route::post('/validations/{id}/demander-correction', [ValidationController::class, 'demanderCorrection']);

    });

    /*
    |--------------------------------------------------------------------------
    | ARCHIVAGE
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Administrateur Principal,Archiviste')->group(function () {

        Route::get('/archives', [ArchivageController::class, 'index']);

        Route::get('/archives/documents-valides', [ArchivageController::class, 'documentsValides']);

        Route::post('/archives/{id}/archiver', [ArchivageController::class, 'archiver']);

        Route::get('/archives/{id}', [ArchivageController::class, 'show']);

    });

    /*
    |--------------------------------------------------------------------------
    | AUDIT & HISTORIQUE
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Administrateur Principal,Auditeur')->group(function () {

        Route::get('/historique/documents/{id}', [HistoriqueController::class, 'document']);

        Route::get('/audit', [AuditController::class, 'index']);

    });

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::get('/notifications/utilisateur/{idUtilisateur}', [NotificationController::class, 'notificationsUtilisateur']);

    Route::put('/notifications/{id}/lue', [NotificationController::class, 'marquerCommeLue']);

});