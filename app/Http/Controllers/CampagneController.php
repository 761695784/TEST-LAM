<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampagneRequest;
use App\Http\Requests\UpdateCampagneRequest;
use App\Models\Campagne;
use App\Models\Destinataire;
use Illuminate\Http\Request;

class CampagneController extends Controller
{
    // Liste des campagnes avec filtres

    public function index(Request $request)
    {
        $campagnes = Campagne::query()
            ->parStatut($request->input('statut'))
            ->recherche($request->input('recherche'))
            ->withCount('destinataires')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('campagnes.index', [
            'campagnes' => $campagnes,
            'statuts'   => Campagne::STATUTS,
            'filtres'   => $request->only(['statut', 'recherche']),
        ]);
    }

    // Formulaire création campagne

    public function create()
    {
        return view('campagnes.create', [
            'statuts' => Campagne::STATUTS,
        ]);
    }

    // Enregistrement nouvelle campagne

    public function store(StoreCampagneRequest $request)
    {
        $campagne = Campagne::create($request->validated());

        return redirect()
            ->route('campagnes.show', $campagne)
            ->with('succes', 'Campagne créée avec succès.');
    }

       // Détail d'une campagne

    public function show(Request $request, Campagne $campagne)
    {
        $destinataires = $campagne->destinataires()
            ->parStatutAppel($request->input('statut_appel'))
            ->paginate(15)
            ->withQueryString();

        return view('campagnes.show', [
            'campagne'      => $campagne,
            'destinataires' => $destinataires,
            'stats'         => $campagne->statistiques(),
            'statuts_appel' => Destinataire::STATUTS,
            'filtre_statut' => $request->input('statut_appel'),
        ]);
    }
    //  Formulaire modification

    public function edit(Campagne $campagne)
    {
        if (!$campagne->estModifiable()) {
            return redirect()
                ->route('campagnes.show', $campagne)
                ->with('erreur', 'Une campagne terminée ou annulée ne peut plus être modifiée.');
        }

        return view('campagnes.edit', [
            'campagne' => $campagne,
            'statuts'  => Campagne::STATUTS,
        ]);
    }

    //  Mise à jour d'une campagne
    public function update(UpdateCampagneRequest $request, Campagne $campagne)
    {
        if (!$campagne->estModifiable()) {
            return redirect()
                ->route('campagnes.show', $campagne)
                ->with('erreur', 'Cette campagne ne peut plus être modifiée.');
        }

        $campagne->update($request->validated());

        return redirect()
            ->route('campagnes.show', $campagne)
            ->with('succes', 'Campagne mise à jour avec succès.');
    }

    //  Suppression (DRAFT uniquement)
       public function destroy(Campagne $campagne)
    {
        if (!$campagne->estSupprimable()) {
            return redirect()
                ->route('campagnes.index')
                ->with('erreur', 'Seules les campagnes en brouillon peuvent être supprimées.');
        }

        $campagne->delete();

        return redirect()
            ->route('campagnes.index')
            ->with('succes', 'Campagne supprimée.');
    }

}
