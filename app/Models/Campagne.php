<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campagne extends Model
{
      use HasFactory;

    protected $table = 'campagnes';

    protected $fillable = [
        'nom',
        'pays',
        'sender',
        'message',
        'statut',
        'date_planification',
    ];

        protected $casts = [
        'date_planification' => 'datetime',
    ];

        // Constantes statuts

    const STATUT_DRAFT     = 'DRAFT';
    const STATUT_SCHEDULED = 'SCHEDULED';
    const STATUT_RUNNING   = 'RUNNING';
    const STATUT_COMPLETED = 'COMPLETED';
    const STATUT_CANCELLED = 'CANCELLED';

    const STATUTS = [
        self::STATUT_DRAFT,
        self::STATUT_SCHEDULED,
        self::STATUT_RUNNING,
        self::STATUT_COMPLETED,
        self::STATUT_CANCELLED,
    ];

    // Relation avec les destinataires
    public function destinataires()
    {
        return $this->hasMany(Destinataire::class);
    }
}
