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

    //  Relation avec campagne

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(Campagne::class, 'campagne_id');
    }

    //  Scopes

    public function scopeParStatutAppel($query, ?string $statut)
    {
        if ($statut) {
            return $query->where('statut_appel', $statut);
        }
        return $query;
    }

    //  Helpers affichage

    public function badgeStatutAppel(): string
    {
        return match($this->statut_appel) {
            self::STATUT_PENDING   => 'warning',
            self::STATUT_SENT      => 'info',
            self::STATUT_ANSWERED  => 'success',
            self::STATUT_FAILED    => 'danger',
            self::STATUT_NO_ANSWER => 'secondary',
            default                => 'secondary',
        };
    }

    public function labelStatutAppel(): string
    {
        return match($this->statut_appel) {
            self::STATUT_PENDING   => 'En attente',
            self::STATUT_SENT      => 'Envoyé',
            self::STATUT_ANSWERED  => 'Répondu',
            self::STATUT_FAILED    => 'Échoué',
            self::STATUT_NO_ANSWER => 'Sans réponse',
            default                => $this->statut_appel,
        };
    }

    public function dureeFormatee(): string
    {
        if (!$this->duree_appel) return '—';

        $minutes = intdiv($this->duree_appel, 60);
        $secondes = $this->duree_appel % 60;

        return $minutes > 0
            ? "{$minutes}m {$secondes}s"
            : "{$secondes}s";
    }
}
