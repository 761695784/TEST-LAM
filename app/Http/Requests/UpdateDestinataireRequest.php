<?php

namespace App\Http\Requests;

use App\Models\Destinataire;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDestinataireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero_telephone' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9\s\-]+$/'],
            'statut_appel'     => ['required', 'in:' . implode(',', Destinataire::STATUTS)],
            'duree_appel'      => ['nullable', 'integer', 'min:0'],
            'motif_echec'      => [
                'nullable',
                'string',
                'max:255',
                // Obligatoire si statut_appel = FAILED
                'required_if:statut_appel,' . Destinataire::STATUT_FAILED,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_telephone.required'   => 'Le numéro de téléphone est obligatoire.',
            'numero_telephone.regex'      => 'Format de numéro invalide.',
            'statut_appel.required'       => 'Le statut d\'appel est obligatoire.',
            'statut_appel.in'             => 'Le statut d\'appel est invalide.',
            'duree_appel.integer'         => 'La durée doit être un nombre entier (en secondes).',
            'motif_echec.required_if'     => 'Le motif d\'échec est obligatoire quand le statut est FAILED.',
        ];
    }
}
