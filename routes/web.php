<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampagneController;

// Page d'accueil : redirige vers les campagnes
Route::redirect('/', '/campagnes');

// Campagnes
Route::prefix('campagnes')->name('campagnes.')->group(function () {

    Route::get('/',              [CampagneController::class, 'index'])        ->name('index');
    Route::get('/creer',         [CampagneController::class, 'create'])       ->name('create');
    Route::post('/',             [CampagneController::class, 'store'])        ->name('store');
    Route::get('/{campagne}',    [CampagneController::class, 'show'])         ->name('show');
});
