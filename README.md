# AnnoncesImmo

Application web d’annonces immobilières (vente / location) construite avec **Laravel**.  
Les utilisateurs peuvent consulter et publier des annonces, ajouter des favoris et échanger via une messagerie. Une section **admin** permet de modérer les annonces.

## Fonctionnalités

### Public (sans connexion)
- Page d’accueil avec **liste des annonces actives**
- Consultation du **détail d’une annonce**
- Filtres de recherche (selon l’implémentation actuelle) :
  - type : `vente` / `location`
  - type de bien : `appartement`, `maison`, `terrain`, `bureau`, `commerce`
  - ville (recherche partielle)
  - prix min / max

### Utilisateur (connexion requise)
- Authentification (Laravel **Breeze**) : inscription / connexion / reset password / vérification email (selon config)
- Tableau de bord : `/dashboard`
- Gestion du profil : `/profile`
- Gestion des annonces :
  - créer une annonce
  - modifier / supprimer ses annonces (autorisations via policy)
  - page “Mes annonces”
- Photos d’annonces :
  - upload jusqu’à **5 photos**
  - la **1ère photo** devient la photo de couverture
  - stockage sur le disque `public`
- Favoris :
  - ajouter / retirer une annonce en favori (toggle)
  - page “Mes favoris”
- Messagerie :
  - boîte de réception
  - conversation avec un utilisateur
  - messages associés optionnellement à une annonce
  - gestion des messages non lus (marqués comme lus à l’ouverture)

### Administration (connexion + rôle admin)
Préfixe `/admin`
- Dashboard avec statistiques (annonces, utilisateurs, messages, annonces signalées)
- Liste de toutes les annonces
- Modération :
  - **signaler** une annonce (la désactive)
  - **réactiver** une annonce
  - suppression d’annonce
- Liste des utilisateurs

## Routes principales

### Public
- `GET /` : liste des annonces
- `GET /annonces/{listing}` : détail d’une annonce

### Auth / Profil (Breeze)
- `GET /dashboard`
- `GET|PATCH|DELETE /profile`
- + routes d’auth (login/register/forgot/reset/verify…)

### Annonces (auth)
- `GET /annonces/creer`
- `POST /annonces`
- `GET /annonces/{listing}/modifier`
- `PUT /annonces/{listing}`
- `DELETE /annonces/{listing}`
- `GET /mes-annonces`

### Favoris (auth)
- `POST /favoris/{listing}` (toggle)
- `GET /mes-favoris`

### Messagerie (auth)
- `GET /messages`
- `GET /messages/{user}`
- `POST /messages`

### Admin (auth + is_admin)
- `GET /admin`
- `GET /admin/annonces`
- `POST /admin/annonces/{listing}/signaler`
- `POST /admin/annonces/{listing}/reactiver`
- `DELETE /admin/annonces/{listing}`
- `GET /admin/utilisateurs`

## Modèle de données (aperçu)

- `Listing` (annonce) :
  - champs notables : `title`, `description`, `type`, `property_type`, `price`, `location`, `city`, `rooms`, `surface`
  - états : `is_active`, `is_flagged`
  - relations : `user`, `photos`, `coverPhoto`, `favorites`, `messages`
- `Photo` : images liées à une annonce (dont `is_cover`)
- `Favorite` : pivot utilisateur ↔ annonce
- `Message` : messages entre utilisateurs (+ `listing_id` optionnel)

## Stack technique

Back-end
- PHP `^8.3`
- Laravel `^13`
- Laravel Breeze (auth)

Front-end
- Vite
- Tailwind CSS
- Alpine.js
- Axios

## Installation (dev)

> Prérequis : PHP 8.3+, Composer, Node.js + npm, une base de données (MySQL/PostgreSQL/etc.)

1) Cloner le projet
```bash
git clone https://github.com/christoban/AnnoncesImmo.git
cd AnnoncesImmo
