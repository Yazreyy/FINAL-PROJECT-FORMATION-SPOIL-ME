<div align="center">

<h1>Spoil-me</h1>

<p>Application web de suivi de séries TV.<br>
PHP orienté objet, architecture MVC construite sans framework.</p>

<p>
<img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white">
<img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white">
<img src="https://img.shields.io/badge/TMDB-API-01B4E4?style=flat-square">
</p>

<p><a href="URL_DU_SITE"><strong>Voir la démo</strong></a></p>

<img src="docs/screenshots/accueil.png" width="800">

</div>

<br>

## Fonctionnalités

- Recherche de séries via l'API TMDB
- Collection personnelle avec détection des doublons
- Suivi de progression épisode par épisode
- Comptes utilisateurs (inscription, connexion, sessions)
- Interface responsive

<div align="center">
<img src="docs/screenshots/recherche.png" width="400">
<img src="docs/screenshots/collection.png" width="400">
</div>

<br>

## Stack

PHP 8 · MySQL 8 / PDO · JavaScript · API TMDB

Architecture MVC écrite from scratch : routeur maison, point d'entrée unique, couche d'abstraction PDO. Le but était de comprendre ce qu'un framework fait à ma place.

<br>

## Sécurité

- Requêtes préparées PDO sur toutes les requêtes
- Échappement des sorties (`htmlspecialchars` avec `ENT_QUOTES`)
- Jeton CSRF vérifié sur chaque formulaire
- Mots de passe hachés en bcrypt

<br>

## Installation

```bash
git clone https://github.com/TON_PSEUDO/spoil-me.git
cd spoil-me

mysql -u root -p -e "CREATE DATABASE spoilme CHARACTER SET utf8mb4;"
mysql -u root -p spoilme < database/schema.sql

cp config/config.example.php config/config.php
# renseigner les identifiants BDD et la clé API TMDB

php -S localhost:8000
```

Clé d'API TMDB gratuite : https://www.themoviedb.org/settings/api

<br>

---

<div align="center">
<sub>Projet réalisé dans le cadre du Titre Professionnel Développeur Web et Web Mobile.</sub><br>
<sub><b>Marlonn Gillet</b> · <a href="URL_LINKEDIN">LinkedIn</a></sub>
</div>
