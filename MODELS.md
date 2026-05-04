# Modèles PHP — Spoil-Me

---

## Modèle `User`

Représente un utilisateur de la plateforme.

**Propriétés** *(correspondent aux colonnes de la table `users`)*

| Propriété | Type PHP | Description |
|-----------|----------|-------------|
| `$id` | int | Identifiant unique |
| `$pseudo` | string | Nom affiché |
| `$email` | string | Adresse email |
| `$mdp` | string | Mot de passe hashé |
| `$avatar` | ?string | Chemin vers l'image de profil (nullable) |
| `$role` | string | Rôle : `user`, `vip` ou `admin` |
| `$date_inscription` | string | Date d'inscription |

**Getters / Setters** : un getter et un setter pour chaque propriété.

**Méthodes utilitaires**

| Méthode | Description |
|---------|-------------|
| `isAdmin()` | Retourne `true` si le rôle de l'utilisateur est `admin` |
| `isVip()` | Retourne `true` si le rôle de l'utilisateur est `vip` |
| `getAvatarUrl()` | Retourne le chemin de l'avatar, ou une image par défaut si aucun avatar n'est défini |

---

## Modèle `Serie`

Représente une série disponible sur la plateforme.

**Propriétés** *(correspondent aux colonnes de la table `series`)*

| Propriété | Type PHP | Description |
|-----------|----------|-------------|
| `$id` | ?int | Identifiant unique |
| `$api_id` | ?int | Identifiant sur l'API externe |
| `$titre` | string | Titre de la série |
| `$synopsis` | string | Résumé de la série |
| `$date_sortie` | string | Date de première diffusion |
| `$image` | string | Chemin ou URL vers l'affiche |
| `$note_moyenne` | float | Moyenne des notes des utilisateurs |
| `$created_by` | ?int | ID de l'admin qui a ajouté la série |
| `$statut` | string | Statut : `en cours`, `terminée` ou `annulée` |
| `$date_creation` | string | Date d'ajout dans la base |

**Getters / Setters** : un getter et un setter pour chaque propriété.

**Méthodes utilitaires**

| Méthode | Description |
|---------|-------------|
| `isFinish()` | Retourne `true` si le statut de la série est `terminée` |
| `getNote()` | Retourne la note moyenne de la série |

---

## Modèle `Watchlist`

Représente l'association entre un utilisateur et une série qu'il suit.

**Propriétés** *(correspondent aux colonnes de la table `watchlist`)*

| Propriété | Type PHP | Description |
|-----------|----------|-------------|
| `$id` | ?int | Identifiant unique |
| `$user_id` | ?int | ID de l'utilisateur |
| `$serie_id` | ?int | ID de la série |
| `$statut` | string | Statut : `vu` ou `pas vu` |
| `$date_ajout` | string | Date d'ajout à la watchlist |

**Getters / Setters** : un getter et un setter pour chaque propriété.

**Méthodes utilitaires**

| Méthode | Description |
|---------|-------------|
| `isWatch()` | Retourne `true` si le statut est `vu` |

---

## Modèle `Review`

Représente un avis rédigé par un utilisateur sur une série.

**Propriétés** *(correspondent aux colonnes de la table `reviews`)*

| Propriété | Type PHP | Description |
|-----------|----------|-------------|
| `$id` | ?int | Identifiant unique |
| `$serie_id` | ?int | ID de la série concernée |
| `$user_id` | ?int | ID de l'utilisateur auteur de la review |
| `$texte` | string | Contenu de la review |
| `$est_officielle` | bool | `true` si la review a été validée par un admin |
| `$date_creation` | string | Date de rédaction |

**Getters / Setters** : un getter et un setter pour chaque propriété.

**Méthodes utilitaires**

| Méthode | Description |
|---------|-------------|
| `isOfficial()` | Retourne `true` si la review a été validée par un admin |

---

## Modèle `Note`

Représente une note attribuée par un utilisateur sur une série.

**Propriétés** *(correspondent aux colonnes de la table `notes`)*

| Propriété | Type PHP | Description |
|-----------|----------|-------------|
| `$id` | ?int | Identifiant unique |
| `$user_id` | ?int | ID de l'utilisateur |
| `$serie_id` | ?int | ID de la série notée |
| `$valeur` | int | Valeur de la note (1 à 5 étoiles) |
| `$date_creation` | string | Date de la note |

**Getters / Setters** : un getter et un setter pour chaque propriété.

---

## Modèle `Like`

Représente un like posé par un utilisateur sur une review ou un commentaire.

**Propriétés** *(correspondent aux colonnes de la table `likes`)*

| Propriété | Type PHP | Description |
|-----------|----------|-------------|
| `$id` | ?int | Identifiant unique |
| `$id_user` | ?int | ID de l'utilisateur |
| `$id_review` | ?int | ID de la review likée (null si like sur commentaire) |
| `$id_commentaire` | ?int | ID du commentaire liké (null si like sur review) |
| `$date_creation` | string | Date du like |

**Getters / Setters** : un getter et un setter pour chaque propriété.

---

## Modèle `Commentaire`

Représente un commentaire rédigé par un utilisateur sur une review.

**Propriétés** *(correspondent aux colonnes de la table `commentaires`)*

| Propriété | Type PHP | Description |
|-----------|----------|-------------|
| `$id` | ?int | Identifiant unique |
| `$id_review` | ?int | ID de la review commentée |
| `$id_user` | ?int | ID de l'utilisateur auteur du commentaire |
| `$texte` | string | Contenu du commentaire |
| `$est_valide` | bool | `true` si le commentaire a été validé par un admin |
| `$date_creation` | string | Date de rédaction |

**Getters / Setters** : un getter et un setter pour chaque propriété.

**Méthodes utilitaires**

| Méthode | Description |
|---------|-------------|
| `isValidate()` | Retourne `true` si le commentaire a été validé par un admin |

---
