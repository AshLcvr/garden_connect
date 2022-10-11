# UPDATE :  
Le site est en ligne! Vous pouvez le visiter en cliquant sur le lien suivant:  
[https://garden-connect.fr](https://garden-connect.fr)

# Présentation
Garden Connect est un site factice visant à mettre en relation des particuliers et/ou professionnels autour de la vente de produits du potager.  
Son objectif premier est de favoriser le circuit-court.  
Ce projet a été réalisé sur Symfony 6.

Certaines parties du site sont accessibles seulement par le biais d'une connexion.
- Pour accéder à la partie utilisateur (donc simple acheteur), connectez-vous avec les identifiants :
  #### E-mail : user@user.fr
  #### Mot de passe : user
- Pour visualiser le site en tant que vendeur, connectez-vous avec les identifiants :
  #### E-mail : vendeur@vendeur.fr
  #### Mot de passe : vendeur
- Pour accéder au site en tant qu'administrateur, connectez-vous avec les identifiants :  
  #### E-mail : admin@admin.fr
  #### Mot de passe : admin

# Installation du projet

Les lignes de commandes suivantes doivent être éxecutées dans le dossier racine du projet

## Dépendances

Après avoir cloné le projet, installez les dépendances :

### Symfony

`composer install`

### Webpack Encore :

`npm install`

## Base de données

- Modifier le fichier .env.example :

  Dans un premier temps, il vous faudra modifier le fichier '.env.example'.  
  Ajouter les informations de votre base de données locale en décommentant si nécessaire la ligne correspondate.
  Puis renommez le ficher en '.env'.

- Créez la base de données :

  `php bin/console doctrine:database:create`

- Mettez à jour la base de données grâce au fichier de migration :

  `php bin/console doctrine:migrations:migrate`

- Remplissez la base de données avec les données des fixtures :

  `php bin/console doctrine:fixtures:load`

## Démarrage des serveurs
Les lignes de commandes suivantes doivent être éxecutées à partir du dossier racine du projet.

### Serveur Apache:

`php -S localhost:3000 -t public`

### Serveur Webpack Encore:

`npm run watch` 
ou 
`npm run dev server`

# Contributions 

## Contributions extérieures 

Les contenus (utilisateurs, boutiques, etc) factices du projet ont été générés grâce aux APIs des sites suivants :  

### - [Pixabay](https://pixabay.com/fr/service/about/api/)
Utilisation d'images pour les boutiques et annonces.

### - [RandomUser](https://randomuser.me/)
Utilisation de photos de profile pour les utilisateurs factices.

### - [Geo.api.gouv](https://geo.api.gouv.fr/decoupage-administratif/communes)
Api gouvernementale afin de récuperer les informations et coordonnées de villes françaises.
Utilisée pour la recherche d'annonce par lieu et la création de boutiques.

### - [ASDFast](http://asdfast.beobit.net/)
Générateur de Lorem Ipsum dans la création des titres et descriptions des annonces, boutiques et avis.

## À propos de nous : 

Développeurs ayant travaillé sur le projet : 

#### Orianne Cielat ([Ocmoz3](https://github.com/Ocmoz3)) :
 - Front : Maquettage, Design, Intégration
 - Langages favoris : HTML, CSS, JS
 
#### Paul Joret ([PaulJORET](https://github.com/PaulJORET)):
- Back :  Bases de données, Sécurité, Administation du site
- Langages favoris : PHP, HTML, MySQL

 
#### Sacha Lechevallier ([AshLcvr](https://github.com/AshLcvr)):
- Back : API, Fixtures, WebPack
- Langages favoris : PHP, JS, TWIG


