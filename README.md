# LAM Voice — Gestion de Campagnes Voice

Application web de gestion de campagnes d'appels voice développée avec Laravel + MySQL.

## Prérequis

- PHP >= 8.3.29
- Composer
- MySQL >= 8.0.45
- Node.js >= 25.6.0

---

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/761695784/TEST-LAM.git
cd TEST-LAM
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Modifier le fichier `.env` avec vos informations de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lam_voice
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 4. Lancer la migration

```bash
php artisan migrate
```

### 5.  Créer la base de données 

```Apres avoir lancée la migration, on vous demandera si vous voulez qu'une bdd soit créée, vous entrez yes et la bdd est créée, et les migrations se poursuivent automatiquement 
```
### 6. Insérer les données de test 

```bash
php artisan db:seed
```

### 7. Lancer le serveur de développement

```bash
php artisan serve
```

L'application est accessible sur : **http://localhost:8000**

---

## Fonctionnalités

### Gestion des campagnes
- Lister, créer, modifier, consulter et supprimer des campagnes
- Filtrer par statut (DRAFT, SCHEDULED, RUNNING, COMPLETED, CANCELLED)
- Rechercher par nom ou pays
- Changer le statut d'une campagne

### Gestion des destinataires
- Ajouter un ou plusieurs destinataires
- Modifier et supprimer des destinataires
- Filtrer par statut d'appel
- **Importer depuis un CSV** (format : une colonne `numero_telephone`)

### Statistiques par campagne
- Nombre total de destinataires
- Appels en attente, réussis, échoués, sans réponse
- Durée totale des appels aboutis

---

## Structure des tables

### `campagnes`
| Colonne | Type | Description |
|---|---|---|
| id | bigint | Clé primaire |
| nom | varchar | Nom de la campagne |
| pays | varchar | Pays cible |
| sender | varchar | Expéditeur affiché |
| message | text | Message vocal |
| statut | enum | DRAFT / SCHEDULED / RUNNING / COMPLETED / CANCELLED |
| date_planification | datetime | Obligatoire si SCHEDULED |
| created_at / updated_at | timestamp | Dates automatiques |

### `destinataires`
| Colonne | Type | Description |
|---|---|---|
| id | bigint | Clé primaire |
| campagne_id | bigint | FK vers campagnes |
| numero_telephone | varchar | Numéro au format international |
| statut_appel | enum | PENDING / SENT / ANSWERED / FAILED / NO_ANSWER |
| duree_appel | int | Durée en secondes (si ANSWERED) |
| motif_echec | varchar | Obligatoire si FAILED |
| created_at / updated_at | timestamp | Dates automatiques |

---

## Format CSV pour l'import

Le fichier CSV doit contenir les numéros de téléphone, un par ligne :

```
numero_telephone
+221 77 123 45 67
+221 78 234 56 78
+225 07 123 456 78
```

La première ligne (header) est automatiquement ignorée.

---

## Règles métier

- Une campagne **COMPLETED** ou **CANCELLED** ne peut plus être modifiée
- Une campagne ne peut être supprimée que si elle est en **DRAFT**
- La date de planification est **obligatoire** si le statut est **SCHEDULED**
- Le motif d'échec est **obligatoire** si le statut d'appel est **FAILED**

---

## Technologies utilisées

- **Backend** : Laravel 12,PHP 8.3.29
- **Base de données** : MySQL V8.0.45
- **Frontend** : Blade, Bootstrap 5, JavaScript (AJAX)
- **Validation** : Laravel Form Requests

---

##  Captures d’écran

### Liste des campagnes
![Liste des campagnes](capture/captureLAM1.png)

### Détails d’une campagne
![Détails d'une campagne](capture/captureLAM2.png)

### Mise à jour d'une campagne
![Mise à jour d'une campagne](capture/captureLAM3.png)

### Creation d'une campagne
![Création d'une campagne](capture/captureLAM4.png)

### Modification d'un destinataire
![modification d'un destinataire](capture/captureLAM5.png)

### Creation d'un destinataire simple
(capture/captureLAM6.png)

### Ajout de destinataire via import csv
![modification d'un destinataire](capture/captureLAM7.png)

## Auteur
Malang MARNA
Développé dans le cadre du test technique LAM (L'AfricaMobile).
