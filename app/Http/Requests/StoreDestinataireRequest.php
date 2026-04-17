<?php

namespace App\Http\Requests;

use App\Models\Destinataire;
use Illuminate\Foundation\Http\FormRequest;

class StoreDestinataireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Accepte plusieurs numéros à la fois (tableau)
            'numeros'              => ['required', 'array', 'min:1'],
            'numeros.*'            => ['required', 'string', 'max:20', 'regex:/^\+?[0-9\s\-]+$/'],
            'statut_appel'         => ['nullable', 'in:' . implode(',', Destinataire::STATUTS)],
        ];
    }

    public function messages(): array
    {
        return [
            'numeros.required'   => 'Au moins un numéro de téléphone est requis.',
            'numeros.*.required' => 'Le numéro de téléphone est obligatoire.',
            'numeros.*.regex'    => 'Format de numéro invalide (ex: +221 77 000 00 00).',
            'statut_appel.in'    => 'Le statut d\'appel est invalide.',
        ];
    }
}
