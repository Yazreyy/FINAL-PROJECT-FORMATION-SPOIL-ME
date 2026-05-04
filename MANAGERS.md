# Managers PHP — Spoil-Me

---

## `AbstractManager`

Classe parente de tous les managers. Initialise la connexion PDO à la base de données et la partage via héritage.

**Propriétés**

| Propriété | Type | Description |
|-----------|------|-------------|
| `$db` | PDO | Instance de connexion à la base de données, accessible par toutes les classes enfants (`protected`) |

**Constructeur**

Déclare les variables de connexion (`$host`, `$dbname`, `$user`, `$password`) et crée l'instance PDO avec le mode d'erreur `ERRMODE_EXCEPTION` pour lever des exceptions en cas d'erreur SQL.

---

## `UserManager`

Gère toutes les requêtes SQL liées aux utilisateurs. Étend `AbstractManager`.

| Méthode | Paramètre(s) | Retour | Description |
|---------|-------------|--------|-------------|
| `findById` | `?int $id` | `?User` | Récupère un utilisateur par son ID |
| `findByEmail` | `string $email` | `?User` | Récupère un utilisateur par son email (connexion) |
| `findByPseudo` | `string $pseudo` | `?User` | Récupère un utilisateur par son pseudo (vérification disponibilité) |
| `findAll` | — | `array` | Récupère tous les utilisateurs (admin) |
| `create` | `User $user` | `void` | Insère un nouvel utilisateur en base et set son ID |
| `update` | `User $user` | `User` | Met à jour les informations d'un utilisateur |
| `delete` | `User $user` | `void` | Supprime un utilisateur de la base |

---

## `SerieManager`

Gère toutes les requêtes SQL liées aux séries. Étend `AbstractManager`.

| Méthode | Paramètre(s) | Retour | Description |
|---------|-------------|--------|-------------|
| `findAll` | — | `array` | Récupère toutes les séries |
| `findOne` | `?int $id` | `Serie` | Récupère une série par son ID |
| `findByGenre` | `?int $genre_id` | `array` | Récupère les séries d'un genre via la table de liaison `serie_genre` |
| `findByTitre` | `string $titre` | `array` | Recherche des séries par titre (LIKE) |
| `create` | `Serie $serie` | `void` | Insère une nouvelle série en base et set son ID |
| `update` | `Serie $serie` | `void` | Met à jour les informations d'une série |
| `delete` | `int $id` | `void` | Supprime une série de la base |

---

## `WatchlistManager`

Gère toutes les requêtes SQL liées aux watchlists des utilisateurs. Étend `AbstractManager`.

| Méthode | Paramètre(s) | Retour | Description |
|---------|-------------|--------|-------------|
| `add` | `Watchlist $watchlist` | `void` | Ajoute une série à la watchlist d'un utilisateur et set son ID |
| `remove` | `int $id` | `void` | Retire une série de la watchlist |
| `update` | `Watchlist $watchlist` | `void` | Met à jour le statut d'une entrée (ex: "à voir" → "vu") |
| `findByUser` | `int $id_user` | `array` | Récupère toute la watchlist d'un utilisateur |

---

## `NoteManager`

Gère toutes les requêtes SQL liées aux notes des utilisateurs. Étend `AbstractManager`.

| Méthode | Paramètre(s) | Retour | Description |
|---------|-------------|--------|-------------|
| `add` | `Note $note` | `void` | Insère une nouvelle note en base et set son ID |
| `update` | `Note $note` | `void` | Met à jour la valeur d'une note |
| `delete` | `int $id` | `void` | Supprime une note de la base |
| `getMoyenne` | `int $id_serie` | `float` | Calcule la moyenne des notes d'une série via AVG() SQL |

---

## `ReviewManager`

Gère toutes les requêtes SQL liées aux reviews. Étend `AbstractManager`.

| Méthode | Paramètre(s) | Retour | Description |
|---------|-------------|--------|-------------|
| `add` | `Review $review` | `void` | Insère une nouvelle review en base et set son ID |
| `update` | `Review $review` | `void` | Met à jour le texte et le statut officiel d'une review |
| `delete` | `int $id` | `void` | Supprime une review de la base |
| `findBySerie` | `int $id_serie` | `array` | Récupère toutes les reviews d'une série |

---

## `CommentaireManager`

Gère toutes les requêtes SQL liées aux commentaires. Étend `AbstractManager`.

| Méthode | Paramètre(s) | Retour | Description |
|---------|-------------|--------|-------------|
| `add` | `Commentaire $commentaire` | `void` | Insère un nouveau commentaire en base et set son ID |
| `update` | `Commentaire $commentaire` | `void` | Met à jour le texte et le statut de validation d'un commentaire |
| `delete` | `int $id` | `void` | Supprime un commentaire de la base |
| `findByReview` | `int $id_review` | `array` | Récupère tous les commentaires d'une review |
| `findByUser` | `int $id_user` | `array` | Récupère tous les commentaires d'un utilisateur |

---

## `LikeManager`

Gère toutes les requêtes SQL liées aux likes. Étend `AbstractManager`.

| Méthode | Paramètre(s) | Retour | Description |
|---------|-------------|--------|-------------|
| `add` | `Like $like` | `void` | Insère un like en base et set son ID |
| `delete` | `int $id` | `void` | Supprime un like de la base |
| `findByReview` | `int $id_review` | `array` | Récupère tous les likes d'une review |
| `findByCommentaire` | `int $id_commentaire` | `array` | Récupère tous les likes d'un commentaire |

---
