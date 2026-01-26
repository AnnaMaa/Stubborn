# 🧥 Stubborn – Boutique de sweat-shirts (Symfony)

Projet réalisé avec **Symfony 7** dans le cadre d’un TP de développement web.  
L’application permet de consulter et acheter des sweat-shirts, avec un **espace administrateur** pour la gestion des produits et des stocks.

---

## 🚀 Fonctionnalités

### 👤 Utilisateur (visiteur / client)
- Consultation de la page d’accueil
- Consultation de la boutique (liste des produits)
- Fiche produit détaillée
- Ajout de produits au panier
- Gestion du panier (ajout, suppression, vider)
- Simulation de paiement (Stripe en mode test)
- Création de compte et connexion
- Déconnexion

### 🛠️ Administrateur (ROLE_ADMIN)

- Accès à un **back-office sécurisé** (`/admin`)
- Liste de tous les sweat-shirts
- Ajout d’un produit
- Modification :
  - prix
  - image
  - mise en avant sur la page d’accueil
  - stock par taille
- Suppression d’un produit

---

## 🔐 Gestion des rôles

- `ROLE_USER` : utilisateur connecté
- `ROLE_ADMIN` : accès au back-office

Le lien **Back-office** n’apparaît dans la navigation **que pour les administrateurs**.

---

## 🧪 Tests unitaires

Des tests unitaires sont présents pour :
- le panier
- le processus d’achat

Commande pour lancer les tests :

php bin/phpunit

🧰 Technologies utilisées

PHP 8.2+

Symfony 7

Twig

Doctrine ORM

MySQL

Stripe (mode test)

PHPUnit

⚙️ Installation du projet


1️⃣ Cloner le dépôt

git clone <lien_du_repository>
cd stubborn

2️⃣ Installer les dépendances

composer install

3️⃣ Configurer l’environnement

Créer un fichier .env.local et configurer la base de données :

DATABASE_URL="mysql://user:password@127.0.0.1:3306/stubborn?charset=utf8mb4"
STRIPE_SECRET_KEY=sk_test_xxx

4️⃣ Créer la base de données

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

5️⃣ Lancer le serveur
symfony server:start

👑 Création d’un administrateur

Une commande Symfony est disponible pour créer ou promouvoir un administrateur :

php bin/console app:make-admin email@example.com MotDePasse


Exemple :

php bin/console app:make-admin stubborn@test.com Admin123

🌐 Accès à l’application

Accueil : http://127.0.0.1:8000/

Boutique : http://127.0.0.1:8000/products

Panier : http://127.0.0.1:8000/cart

Back-office (admin) : http://127.0.0.1:8000/admin


