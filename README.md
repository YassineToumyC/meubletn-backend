# meubletn — Documentation de déploiement

Plateforme de foire digitale du meuble en Tunisie.
Architecture : **Frontend Nuxt 3** + **Backend Laravel** déployés séparément via **Dokploy**.

---

## Structure du projet

```
meubletn/
├── frontend/        ← Application Nuxt 3 (Vue.js)
│   ├── Dockerfile
│   ├── nuxt.config.ts
│   └── app/
│       ├── pages/
│       ├── components/
│       ├── composables/
│       └── layouts/
│
└── backend/         ← API Laravel 12 (PHP 8.4)
    ├── Dockerfile
    ├── Caddyfile
    ├── entrypoint.sh
    └── app/
        ├── Http/Controllers/
        ├── Models/
        └── ...
```

---

## Stratégie de branches

| Branche    | Rôle                                      |
|------------|-------------------------------------------|
| `master`   | **Production** — page "coming soon" active, stable |
| `test`     | **Développement** — site complet visible, pour tester les nouvelles fonctionnalités |

> **Règle** : on développe sur `test`, on merge vers `master` uniquement quand une fonctionnalité est validée.

---

## Outils de déploiement

### Dokploy
Dokploy est la plateforme de déploiement utilisée. Elle gère deux services Docker indépendants :
- `meubletn-frontend` → pointe vers le repo frontend
- `meubletn-backend` → pointe vers le repo backend

Chaque service est lié à une branche Git. Quand on veut tester la branche `test` :
1. Aller dans Dokploy → Settings du service → Source → changer la branche de `master` à `test`
2. Cliquer **Redeploy**
3. Pour revenir en production, remettre `master` et redéployer

---

## Déploiement Backend (Laravel)

### Fichiers clés

#### `backend/Dockerfile`
```dockerfile
FROM dunglas/frankenphp:1-php8.4
```
- Utilise **FrankenPHP** comme serveur PHP (remplace Apache/Nginx+PHP-FPM)
- Installe les extensions PHP : `pdo_mysql`, `gd`, `zip`, `exif`, `opcache`, `composer`
- Configure les limites d'upload : 100 Mo fichier, 150 Mo POST, 512 Mo mémoire
- Copie le code et donne les permissions à `storage/` et `bootstrap/cache/`
- Expose le port **81**

#### `backend/entrypoint.sh`
Script exécuté au démarrage du container. Il effectue dans l'ordre :
```sh
php artisan migrate --force        # Applique les migrations en base
php artisan storage:link           # Crée le lien symbolique storage → public/storage
php artisan config:cache           # Cache la configuration Laravel
php artisan route:cache            # Cache les routes pour les performances
frankenphp run --config /etc/caddy/Caddyfile  # Démarre le serveur
```

#### `backend/Caddyfile`
Configuration du serveur web FrankenPHP/Caddy :
```caddy
{
    frankenphp
}

:81 {
    root * /app/public
    encode gzip
    php_server
}
```
- Écoute sur le port `81`
- Sert les fichiers statiques depuis `/app/public`
- Active la compression gzip
- Passe toutes les requêtes PHP à FrankenPHP

### Variables d'environnement backend (à configurer dans Dokploy)
```env
APP_KEY=base64:...
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=meubletn
DB_USERNAME=...
DB_PASSWORD=...
FILESYSTEM_DISK=public
```

---

## Déploiement Frontend (Nuxt 3)

### Fichiers clés

#### `frontend/Dockerfile`
```dockerfile
FROM node:23-alpine
```
- Utilise Node.js 23 (Alpine Linux, image légère)
- Installe les dépendances npm
- Expose le port **3002**
- **Important** : le build Nuxt se fait au démarrage du container (pas pendant l'image) pour que les variables d'environnement `NUXT_PUBLIC_*` soient prises en compte :
```sh
CMD ["sh", "-c", "npm run build && node .output/server/index.mjs"]
```

> Cette approche permet de changer les URLs de l'API via des variables d'environnement sans reconstruire l'image Docker.

#### `frontend/nuxt.config.ts`
```ts
runtimeConfig: {
  public: {
    apiBase:     'http://localhost:8000/api',      // URL de l'API backend
    storageBase: 'http://localhost:8000/storage',  // URL des fichiers média
  },
}
```
Ces valeurs sont les valeurs par défaut (développement local). En production, elles sont surchargées par les variables d'environnement Dokploy.

### Variables d'environnement frontend (à configurer dans Dokploy)
```env
NUXT_PUBLIC_API_BASE=https://api.meubletn.tn/api
NUXT_PUBLIC_STORAGE_BASE=https://api.meubletn.tn/storage
```

---

## Flow de déploiement complet

```
Développement local
        │
        ▼
   git push test          ← toujours tester sur la branche test d'abord
        │
        ▼
  Dokploy (test)          ← vérifier que tout fonctionne sur l'environnement test
        │
        ▼ (validation OK)
   merge test → master
        │
        ▼
  git push master
        │
        ▼
  Dokploy (master)        ← redéploiement automatique ou manuel en production
```

---

## Développement local

### Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve          # Lance sur http://localhost:8000
```

### Frontend
```bash
cd frontend
npm install
# Créer .env avec :
# NUXT_PUBLIC_API_BASE=http://localhost:8000/api
# NUXT_PUBLIC_STORAGE_BASE=http://localhost:8000/storage
npm run dev               # Lance sur http://localhost:3000
```

---

## Résumé des ports

| Service    | Port local | Port Docker |
|------------|-----------|-------------|
| Frontend   | 3000      | 3002        |
| Backend    | 8000      | 81          |
