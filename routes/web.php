<?php

use App\Http\Controllers\CampagneController;
use App\Http\Controllers\DestinataireController;
use Illuminate\Support\Facades\Route;

// Page d'accueil : redirige vers les campagnes
Route::redirect('/', '/campagnes');

// Campagnes
Route::prefix('campagnes')->name('campagnes.')->group(function () {

    Route::get('/',              [CampagneController::class, 'index'])        ->name('index');
    Route::get('/creer',         [CampagneController::class, 'create'])       ->name('create');
    Route::post('/',             [CampagneController::class, 'store'])        ->name('store');
    Route::get('/{campagne}',    [CampagneController::class, 'show'])         ->name('show');
    Route::get('/{campagne}/modifier', [CampagneController::class, 'edit'])   ->name('edit');
    Route::put('/{campagne}',    [CampagneController::class, 'update'])       ->name('update');
    Route::delete('/{campagne}', [CampagneController::class, 'destroy'])      ->name('destroy');

     // Changement de statut (AJAX ou form POST)
    Route::patch('/{campagne}/statut', [CampagneController::class, 'changerStatut'])->name('statut');

    //  Destinataires d'une campagne
    Route::prefix('/{campagne}/destinataires')->name('destinataires.')->group(function () {

        Route::post('/',                    [DestinataireController::class, 'store'])  ->name('store');
        Route::get('/{destinataire}/modifier', [DestinataireController::class, 'edit'])  ->name('edit');
        Route::put('/{destinataire}',       [DestinataireController::class, 'update'])->name('update');
        Route::delete('/{destinataire}',    [DestinataireController::class, 'destroy'])->name('destroy');

        // Import CSV
        Route::post('/import-csv',          [DestinataireController::class, 'importCsv'])->name('import');

    });

});
