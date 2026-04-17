<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Destinataire extends Model
{
        use HasFactory;

    protected $table = 'destinataires';

    protected $fillable = [
        'campagne_id',
        'numero_telephone',
        'statut_appel',
        'duree_appel',
        'motif_echec',
    ];

    protected $casts = [
        'duree_appel' => 'integer',
    ];

     //  Constantes statuts d'appel

    const STATUT_PENDING   = 'PENDING';
    const STATUT_SENT      = 'SENT';
    const STATUT_ANSWERED  = 'ANSWERED';
    const STATUT_FAILED    = 'FAILED';
    const STATUT_NO_ANSWER = 'NO_ANSWER';

    const STATUTS = [
        self::STATUT_PENDING,
        self::STATUT_SENT,
        self::STATUT_ANSWERED,
        self::STATUT_FAILED,
        self::STATUT_NO_ANSWER,
    ];

        public function campagne(): BelongsTo
    {
        return $this->belongsTo(Campagne::class, 'campagne_id');
    }

}
