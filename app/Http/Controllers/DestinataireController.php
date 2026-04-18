<?php

namespace App\Http\Controllers;

use App\Models\Destinataire;
use App\Models\Campagne;
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

}
