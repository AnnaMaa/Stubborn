# 🧥 Stubborn – Boutique de sweat-shirts (Symfony)

Projet réalisé avec **Symfony 7** dans le cadre d’un TP de développement web.
L’application permet de consulter et acheter des sweat-shirts, avec un **espace administrateur** pour la gestion des produits et des stocks.

---

## 🚀 Fonctionnalités

### 👤 Utilisateur (visiteur / client)

* Consultation de la page d’accueil
* Consultation de la boutique (liste des produits)
* Fiche produit détaillée
* Ajout de produits au panier
* Gestion du panier (ajout, suppression, vider)
* Simulation de paiement (**Stripe en mode test**)
* Création de compte et connexion
* Déconnexion

### 🛠️ Administrateur (ROLE_ADMIN)

* Accès à un **back-office sécurisé** (`/admin`)
* Liste de tous les sweat-shirts
* Ajout d’un produit
* Modification :

  * prix
  * image
  * mise en avant sur la page d’accueil
  * stock par taille
* Suppression d’un produit

---

## 🔐 Gestion des rôles

* `ROLE_USER` : utilisateur connecté
* `ROLE_ADMIN` : accès au back-office

Le lien **Back-office** n’apparaît dans la navigation **que pour les administrateurs**.

---

## 🧪 Tests unitaires

Des tests unitaires sont présents pour :

* le panier
* le processus d’achat

Commande pour lancer les tests :

```bash
php bin/phpunit
```

---

## 🧰 Technologies utilisées

* PHP 8.2+
* Symfony 7
* Twig
* Doctrine ORM
* MySQL
* Stripe (mode test)
* PHPUnit

---

## ⚙️ Installation du projet (local)

### 1️⃣ Cloner le dépôt

```bash
git clone <lien_du_repository>
cd stubborn
```

### 2️⃣ Installer les dépendances

```bash
composer install
```

### 3️⃣ Configurer l’environnement

Créer un fichier `.env.local` :

```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/stubborn?charset=utf8mb4"
STRIPE_SECRET_KEY=sk_test_xxx
```

### 4️⃣ Créer la base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5️⃣ Lancer le serveur

```bash
symfony server:start
```

---

## 🚀 Déploiement (alwaysdata)

* Déployer les fichiers via FTP
* Installer les dépendances :

```bash
composer install --no-dev --optimize-autoloader
```

* Configurer les variables d’environnement (`.env` ou variables serveur)

* Vider le cache :

```bash
php bin/console cache:clear --env=prod
```

---

## 👑 Création d’un administrateur

Commande Symfony :

```bash
php bin/console app:make-admin email@example.com MotDePasse
```

Exemple :

```bash
php bin/console app:make-admin stubborn@test.com Admin123
```

⚠️ **Important en production (alwaysdata)** :

* Cette commande doit être exécutée via SSH
* Sinon, créer manuellement un utilisateur dans la base de données avec :

```json
["ROLE_ADMIN"]
```

---

## 🔒 Sécurité

* Les mots de passe sont hashés avec Symfony Security
* L’accès au back-office est protégé par `ROLE_ADMIN`
* Les paiements Stripe sont en mode test (aucune transaction réelle)

⚠️ En production, utiliser une clé Stripe réelle (`sk_live_...`) si nécessaire.

---

## 🌐 Accès à l’application

* Accueil : https://maaroufi.alwaysdata.net/
* Boutique : https://maaroufi.alwaysdata.net/products
* Panier : https://maaroufi.alwaysdata.net/cart
* Back-office (admin) : https://maaroufi.alwaysdata.net/admin

