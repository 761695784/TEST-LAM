<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampagneRequest;
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
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campagne $campagne)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campagne $campagne)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campagne $campagne)
    {
        //
    }
}
