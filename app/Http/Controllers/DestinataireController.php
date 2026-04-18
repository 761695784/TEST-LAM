<?php

namespace App\Http\Controllers;

use App\Models\Destinataire;
use App\Models\Campagne;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDestinataireRequest;
use App\Http\Requests\UpdateDestinataireRequest;

class DestinataireController extends Controller
{
    //  Ajout d'un ou plusieurs destinataires

    public function store(StoreDestinataireRequest $request, Campagne $campagne)
    {
        $numeros = array_unique(array_filter($request->input('numeros', [])));

        foreach ($numeros as $numero) {
            $campagne->destinataires()->create([
                'numero_telephone' => trim($numero),
                'statut_appel'     => Destinataire::STATUT_PENDING,
            ]);
        }

        $nb = count($numeros);

        if ($request->expectsJson()) {
            return response()->json([
                'succes'  => true,
                'message' => "{$nb} destinataire(s) ajouté(s).",
            ]);
        }

        return redirect()
            ->route('campagnes.show', $campagne)
            ->with('succes', "{$nb} destinataire(s) ajouté(s) avec succès.");
    }

    //  Formulaire modification destinataire

    public function edit(Campagne $campagne, Destinataire $destinataire)
    {
        return view('destinataires.edit', [
            'campagne'     => $campagne,
            'destinataire' => $destinataire,
            'statuts'      => Destinataire::STATUTS,
        ]);
    }

    //  Mise à jour destinataire

    public function update(UpdateDestinataireRequest $request, Campagne $campagne, Destinataire $destinataire)
    {
        $destinataire->update($request->validated());

        return redirect()
            ->route('campagnes.show', $campagne)
            ->with('succes', 'Destinataire mis à jour.');
    }

    //  Suppression destinataire

    public function destroy(Campagne $campagne, Destinataire $destinataire)
    {
        $destinataire->delete();

        if (request()->expectsJson()) {
            return response()->json(['succes' => true]);
        }

        return redirect()
            ->route('campagnes.show', $campagne)
            ->with('succes', 'Destinataire supprimé.');
    }

        //  Import CSV

    public function importCsv(Request $request, Campagne $campagne)
    {
        $request->validate([
            'fichier_csv' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ], [
            'fichier_csv.required' => 'Veuillez sélectionner un fichier CSV.',
            'fichier_csv.mimes'    => 'Le fichier doit être au format CSV.',
            'fichier_csv.max'      => 'Le fichier ne doit pas dépasser 2 Mo.',
        ]);

        $fichier   = $request->file('fichier_csv');
        $contenu   = file($fichier->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $ajoutes   = 0;
        $ignores   = 0;
        $ligneData = [];

        foreach ($contenu as $index => $ligne) {
            // Ignore la première ligne si c'est un header
            if ($index === 0 && !preg_match('/^\+?[0-9\s\-]+$/', trim($ligne))) {
                continue;
            }

            // Supporte CSV avec virgule ou point-virgule
            $colonnes = str_getcsv($ligne, ',');
            if (count($colonnes) < 1) {
                $colonnes = str_getcsv($ligne, ';');
            }

            $numero = trim($colonnes[0]);

            if (!preg_match('/^\+?[0-9\s\-]{7,20}$/', $numero)) {
                $ignores++;
                continue;
            }

            $ligneData[] = [
                'campagne_id'      => $campagne->id,
                'numero_telephone' => $numero,
                'statut_appel'     => Destinataire::STATUT_PENDING,
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
            $ajoutes++;
        }

        // Insertion en masse pour les performances
        if (!empty($ligneData)) {
            Destinataire::insert($ligneData);
        }

        return redirect()
            ->route('campagnes.show', $campagne)
            ->with('succes', "{$ajoutes} destinataire(s) importé(s). {$ignores} ligne(s) ignorée(s).");
    }

}
