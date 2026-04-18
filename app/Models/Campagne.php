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

      //  Règles métier

    public function estModifiable(): bool
    {
        return !in_array($this->statut, [
            self::STATUT_COMPLETED,
            self::STATUT_CANCELLED,
        ]);
    }

    public function estSupprimable(): bool
    {
        return $this->statut === self::STATUT_DRAFT;
    }

    //  Statistiques

    public function totalDestinataires(): int
    {
        return $this->destinataires()->count();
    }

    public function totalParStatut(string $statut): int
    {
        return $this->destinataires()
                    ->where('statut_appel', $statut)
                    ->count();
    }

    public function dureetotaleAppels(): int
    {
        return (int) $this->destinataires()
                          ->where('statut_appel', Destinataire::STATUT_ANSWERED)
                          ->sum('duree_appel');
    }

    public function statistiques(): array
    {
        return [
            'total'       => $this->totalDestinataires(),
            'en_attente'  => $this->totalParStatut(Destinataire::STATUT_PENDING),
            'reussis'     => $this->totalParStatut(Destinataire::STATUT_ANSWERED),
            'echoues'     => $this->totalParStatut(Destinataire::STATUT_FAILED),
            'sans_reponse'=> $this->totalParStatut(Destinataire::STATUT_NO_ANSWER),
            'duree_totale'=> $this->dureetoTaleAppels(),
        ];
    }

    //  Scopes (filtres)

    public function scopeParStatut($query, ?string $statut)
    {
        if ($statut) {
            return $query->where('statut', $statut);
        }
        return $query;
    }

    public function scopeRecherche($query, ?string $terme)
    {
        if ($terme) {
            return $query->where(function ($q) use ($terme) {
                $q->where('nom', 'like', "%{$terme}%")
                  ->orWhere('pays', 'like', "%{$terme}%");
            });
        }
        return $query;
    }

    //  Helpers affichage

    public function badgeStatut(): string
    {
        return match($this->statut) {
            self::STATUT_DRAFT     => 'secondary',
            self::STATUT_SCHEDULED => 'info',
            self::STATUT_RUNNING   => 'primary',
            self::STATUT_COMPLETED => 'success',
            self::STATUT_CANCELLED => 'danger',
            default                => 'secondary',
        };
    }

    public function labelStatut(): string
    {
        return match($this->statut) {
            self::STATUT_DRAFT     => 'Brouillon',
            self::STATUT_SCHEDULED => 'Planifiée',
            self::STATUT_RUNNING   => 'En cours',
            self::STATUT_COMPLETED => 'Terminée',
            self::STATUT_CANCELLED => 'Annulée',
            default                => $this->statut,
        };
    }
}
