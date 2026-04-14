# Base de données — Spoil-Me

---

## Table `users`

Regroupe tous les utilisateurs inscrits sur la plateforme.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique de l'utilisateur |
| `pseudo` | VARCHAR(50) — UNIQUE | Nom affiché sur le site, unique pour chaque utilisateur |
| `email` | VARCHAR(150) — UNIQUE | Adresse email, utilisée pour la connexion |
| `mdp` | VARCHAR(255) | Mot de passe hashé (jamais stocké en clair) |
| `avatar` | VARCHAR(255) — NULL | Chemin vers l'image de profil (ex: `uploads/avatars/user_1.jpg`) |
| `role` | ENUM('user', 'admin') — DEFAULT 'user' | Rôle de l'utilisateur : accès standard ou accès administration |
| `date_inscription` | DATETIME — DEFAULT CURRENT_TIMESTAMP | Date et heure d'inscription, remplie automatiquement |

---

## Table `watchlist`

Regroupe toutes les séries ajoutées par chaque utilisateur. C'est une table de liaison entre `users` et `series`, enrichie d'un statut de visionnage.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique de l'entrée |
| `user_id` | INT — FK → users(id) | L'utilisateur concerné |
| `serie_id` | INT — FK → series(id) | La série ajoutée |
| `statut` | ENUM('vu', 'pas vu') — DEFAULT 'pas vu' | Indique si l'utilisateur a regardé la série ou non |
| `date_ajout` | DATETIME — DEFAULT CURRENT_TIMESTAMP | Date et heure à laquelle la série a été ajoutée à la watchlist |

---

## Table `series`

Regroupe toutes les informations sur chaque série disponible sur la plateforme.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique de la série en base |
| `api_id` | INT — UNIQUE | Identifiant de la série sur l'API externe (pour éviter les doublons lors des imports) |
| `titre` | VARCHAR(150) | Titre de la série |
| `synopsis` | TEXT | Description / résumé de la série |
| `genre` | VARCHAR(100) | Genre(s) de la série (ex: `Action, Drame`) |
| `plateforme` | VARCHAR(150) | Plateforme(s) où regarder la série (ex: `Netflix, Disney+`) |
| `date_sortie` | DATE | Date de première diffusion de la série |
| `image` | VARCHAR(255) | Chemin ou URL vers l'affiche de la série |
| `note_moyenne` | DECIMAL(3,1) — DEFAULT 0 | Moyenne des notes attribuées par les utilisateurs (ex: `7.4`) |
| `created_by` | INT — FK → users(id) | Identifiant de l'admin qui a ajouté la série |
| `statut` | ENUM('en cours', 'terminée', 'annulée') | Statut de diffusion actuel de la série |
| `date_creation` | DATETIME — DEFAULT CURRENT_TIMESTAMP | Date et heure d'ajout de la série dans la base |

---

## Table `reviews`

Regroupe tous les avis rédigés par les utilisateurs sur les séries.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique de la review |
| `serie_id` | INT — FK → series(id) | La série concernée par l'avis |
| `user_id` | INT — FK → users(id) | L'utilisateur qui a rédigé l'avis |
| `texte` | TEXT | Contenu de la review |
| `est_officielle` | TINYINT(1) — DEFAULT 0 | Indique si la review a été validée par un admin (0 = non, 1 = oui) |
| `date_creation` | DATETIME — DEFAULT CURRENT_TIMESTAMP | Date et heure de rédaction de la review |

---

## Table `notes`

Regroupe toutes les notes attribuées par les utilisateurs sur les séries, exprimées en étoiles.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique de la note |
| `user_id` | INT — FK → users(id) | L'utilisateur qui a attribué la note |
| `serie_id` | INT — FK → series(id) | La série notée |
| `valeur` | TINYINT — CHECK (valeur BETWEEN 1 AND 5) | Valeur de la note en étoiles (de 1 à 5) |
| *(contrainte)* | UNIQUE (user_id, serie_id) | Un utilisateur ne peut noter une série qu'une seule fois |
| `date_creation` | DATETIME — DEFAULT CURRENT_TIMESTAMP | Date et heure à laquelle la note a été donnée |

---

## Table `likes`

Regroupe tous les likes des utilisateurs, posés soit sur une review soit sur un commentaire.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique du like |
| `user_id` | INT — FK → users(id) | L'utilisateur qui a liké |
| `review_id` | INT — FK → reviews(id) — NULL | La review likée (NULL si c'est un commentaire) |
| `commentaire_id` | INT — FK → commentaires(id) — NULL | Le commentaire liké (NULL si c'est une review) |
| `date_creation` | DATETIME — DEFAULT CURRENT_TIMESTAMP | Date et heure du like |

---

## Table `commentaires`

Regroupe tous les commentaires rédigés par les utilisateurs sur les reviews.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique du commentaire |
| `review_id` | INT — FK → reviews(id) | La review sur laquelle le commentaire est posté |
| `user_id` | INT — FK → users(id) | L'utilisateur qui a rédigé le commentaire |
| `contenu` | TEXT | Contenu textuel du commentaire |
| `est_valide` | TINYINT(1) — DEFAULT 0 | Indique si le commentaire a été validé par un admin (0 = non, 1 = oui) |
| `date_creation` | DATETIME — DEFAULT CURRENT_TIMESTAMP | Date et heure de rédaction du commentaire |

---

## Table `badges`

Regroupe tous les types de badges disponibles sur la plateforme.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique du badge |
| `nom` | VARCHAR(100) — UNIQUE | Nom du badge (ex: "Binge Watcher") |
| `description` | TEXT | Description du badge affiché sur le profil |
| `icone` | VARCHAR(255) | Chemin vers l'image ou l'icône du badge |
| `condition_obtention` | VARCHAR(255) | Description de la condition pour obtenir le badge (ex: "Avoir vu 10 séries") |

---

## Table `badges_users`

Table de liaison entre les utilisateurs et les badges qu'ils ont obtenus.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique de l'entrée |
| `user_id` | INT — FK → users(id) | L'utilisateur qui a obtenu le badge |
| `badge_id` | INT — FK → badges(id) | Le badge obtenu |
| `date_obtention` | DATETIME — DEFAULT CURRENT_TIMESTAMP | Date et heure à laquelle le badge a été attribué |

---

## Table `plateformes`

Regroupe toutes les plateformes de streaming disponibles sur la plateforme.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique de la plateforme |
| `nom` | VARCHAR(100) — UNIQUE | Nom de la plateforme (ex: "Netflix", "Disney+") |
| `logo` | VARCHAR(255) — NULL | Chemin vers le logo de la plateforme |

---

## Table `series_plateformes`

Table de liaison entre les séries et les plateformes sur lesquelles elles sont disponibles.

| Colonne | Type | Description |
|--------|------|-------------|
| `serie_id` | INT — FK → series(id) | La série concernée |
| `plateforme_id` | INT — FK → plateformes(id) | La plateforme concernée |

---

## Table `genres`

Regroupe tous les genres disponibles pour catégoriser les séries.

| Colonne | Type | Description |
|--------|------|-------------|
| `id` | INT — PK — AUTO_INCREMENT | Identifiant unique du genre |
| `nom` | VARCHAR(50) — UNIQUE | Nom du genre (ex: "Action", "Drame", "Comédie") |

---

## Table `series_genres`

Table de liaison entre les séries et leurs genres.

| Colonne | Type | Description |
|--------|------|-------------|
| `serie_id` | INT — FK → series(id) | La série concernée |
| `genre_id` | INT — FK → genres(id) | Le genre associé |

---
