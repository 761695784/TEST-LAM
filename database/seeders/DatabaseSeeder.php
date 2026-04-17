<?php

namespace Database\Seeders;

use App\Models\Campagne;
use App\Models\Destinataire;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Campagne 1 : DRAFT
        $draft = Campagne::create([
            'nom'               => 'Promotion Ramadan 2025',
            'pays'              => 'Sénégal',
            'sender'            => 'LAM_SEN',
            'message'           => 'Chers clients, profitez de nos offres spéciales Ramadan. Rechargez 1000 FCFA et obtenez 500 FCFA de crédit bonus. Valable jusqu\'au 30 mars.',
            'statut'            => Campagne::STATUT_DRAFT,
            'date_planification'=> null,
        ]);

        // Destinataires en attente
        $this->ajouterDestinataires($draft, [
            ['+221 77 123 45 67', 'PENDING'],
            ['+221 78 234 56 78', 'PENDING'],
            ['+221 76 345 67 89', 'PENDING'],
        ]);

        //  Campagne 2 : SCHEDULED
        $scheduled = Campagne::create([
            'nom'               => 'Lancement Forfait Data 5G',
            'pays'              => 'Côte d\'Ivoire',
            'sender'            => 'LAM_CI',
            'message'           => 'Bonjour, découvrez notre nouveau forfait Data 5G à partir de 2500 FCFA par mois. Connectez-vous à la vitesse de demain. Appelez le 900 pour souscrire.',
            'statut'            => Campagne::STATUT_SCHEDULED,
            'date_planification'=> now()->addDays(3),
        ]);

        $this->ajouterDestinataires($scheduled, [
            ['+225 07 123 456 78', 'PENDING'],
            ['+225 05 234 567 89', 'PENDING'],
            ['+225 01 345 678 90', 'PENDING'],
            ['+225 07 456 789 01', 'PENDING'],
        ]);

        //  Campagne 3 : RUNNING
        $running = Campagne::create([
            'nom'               => 'Rappel Renouvellement Contrat',
            'pays'              => 'Mali',
            'sender'            => 'LAM_ML',
            'message'           => 'Votre contrat arrive à expiration dans 7 jours. Renouvelez maintenant et bénéficiez d\'un mois gratuit. Rendez-vous en agence ou sur notre application.',
            'statut'            => Campagne::STATUT_RUNNING,
            'date_planification'=> now()->subHours(2),
        ]);

        $this->ajouterDestinataires($running, [
            ['+223 70 123 456', 'ANSWERED', 45, null],
            ['+223 76 234 567', 'ANSWERED', 62, null],
            ['+223 65 345 678', 'FAILED',   null, 'Numéro non attribué'],
            ['+223 79 456 789', 'NO_ANSWER',null, null],
            ['+223 72 567 890', 'PENDING',  null, null],
            ['+223 73 678 901', 'SENT',     null, null],
            ['+223 91 789 012', 'FAILED',   null, 'Réseau indisponible'],
        ]);

        //  Campagne 4 : COMPLETED
        $completed = Campagne::create([
            'nom'               => 'Enquête Satisfaction Client',
            'pays'              => 'Sénégal',
            'sender'            => 'LAM_SEN',
            'message'           => 'Bonjour, nous réalisons une enquête de satisfaction. Votre avis compte ! Tapez 1 si vous êtes satisfait, 2 si vous ne l\'êtes pas. Merci pour votre participation.',
            'statut'            => Campagne::STATUT_COMPLETED,
            'date_planification'=> now()->subDays(5),
        ]);

        $this->ajouterDestinataires($completed, [
            ['+221 77 111 22 33', 'ANSWERED', 30, null],
            ['+221 78 222 33 44', 'ANSWERED', 28, null],
            ['+221 76 333 44 55', 'ANSWERED', 35, null],
            ['+221 77 444 55 66', 'NO_ANSWER',null, null],
            ['+221 78 555 66 77', 'FAILED',   null, 'Abonné absent'],
            ['+221 76 666 77 88', 'ANSWERED', 22, null],
        ]);

        //  Campagne 5 : CANCELLED
        $cancelled = Campagne::create([
            'nom'               => 'Test Campagne Guinée',
            'pays'              => 'Guinée',
            'sender'            => 'LAM_GN',
            'message'           => 'Ceci est un test de campagne voice pour la Guinée.',
            'statut'            => Campagne::STATUT_CANCELLED,
            'date_planification'=> null,
        ]);

        $this->ajouterDestinataires($cancelled, [
            ['+224 62 123 456', 'PENDING'],
            ['+224 65 234 567', 'PENDING'],
        ]);
    }

    //  Helper privé pour ajouter des destinataires à une campagne

    private function ajouterDestinataires(Campagne $campagne, array $destinataires): void
    {
        foreach ($destinataires as $d) {
            $campagne->destinataires()->create([
                'numero_telephone' => $d[0],
                'statut_appel'     => $d[1],
                'duree_appel'      => $d[2] ?? null,
                'motif_echec'      => $d[3] ?? null,
            ]);
        }
    }
}
