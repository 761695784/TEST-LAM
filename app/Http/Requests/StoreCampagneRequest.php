<?php

namespace App\Http\Requests;

use App\Models\Campagne;
use Illuminate\Foundation\Http\FormRequest;

class StoreCampagneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'               => ['required', 'string', 'max:255'],
            'pays'              => ['required', 'string', 'max:100'],
            'sender'            => ['required', 'string', 'max:100'],
            'message'           => ['required', 'string'],
            'statut'            => ['required', 'in:' . implode(',', Campagne::STATUTS)],
            'date_planification'=> [
                'nullable',
                'date',
                'after:now',
                // Obligatoire si statut = SCHEDULED
                'required_if:statut,' . Campagne::STATUT_SCHEDULED,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'               => 'Le nom de la campagne est obligatoire.',
            'pays.required'              => 'Le pays est obligatoire.',
            'sender.required'            => 'L\'expéditeur (sender) est obligatoire.',
            'message.required'           => 'Le message vocal est obligatoire.',
            'statut.required'            => 'Le statut est obligatoire.',
            'statut.in'                  => 'Le statut choisi est invalide.',
            'date_planification.required_if' => 'La date de planification est obligatoire pour une campagne planifiée.',
            'date_planification.after'   => 'La date de planification doit être dans le futur.',
            'date_planification.date'    => 'Format de date invalide.',
        ];
    }
}
