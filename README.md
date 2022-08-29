# Présentation
Garden Connect est un site factice visant à mettre en relation des particuliers et/ou professionnels autour de la vente de produits du potager.  
Son objectif premier est de favoriser le circuit court.  
Ce projet a été réalisé sur Symfony 6.  
Pour accéder à la totalité du site, vous pouvez vous connecter avec les identifiants suivants:  
#### E-mail : admin@admin.fr
#### Mot de passe : admin

# Installation du projet

Les instructions suivantes doivent être éxecutées dans le dossier racine du projet

## Dépendances

Après avoir cloné le projet, installez les dépendances :

### Symfony

`composer install`

### Webpack Encore :

`npm install`

## Base de données
- Créez la base de données :

  `php bin/console doctrine:database:create`

- Mettez à jour la base de donnée grâce aux fichiers de migrations :

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

Les contenus (utilisateurs,boutiques,..) factices du projet ont été générés grâce aux APIs des sites suivants :  

### - [Pixabay](https://pixabay.com/fr/service/about/api/)
Recherche d'image pour les boutique et annonces.

### - [RandomUser](https://randomuser.me/)
Recherche de photos de profil.

### - [Geo.api.gouv](https://geo.api.gouv.fr/decoupage-administratif/communes)
Informations et coordonnées de villes.

### - [ASDFast](http://asdfast.beobit.net/)
Generateur de Lorem Ipsum dans la création des titres et descriptions des annonces, boutiques et avis.

## A propos de nous : 

Développeurs ayant travaillé sur le projet : 

#### Orianne Cielat ([Ocmoz3](https://github.com/Ocmoz3)) :
 - Front: Maquettage, Design, Intégration
 - Langages favoris : HTML, CSS, JS
 
#### Paul Joret ([PaulJORET](https://github.com/PaulJORET)):
- Back:  Bases de données, Sécurité, Administation du site
- Langages favoris : PHP, HTML, MYSQL

 
#### Sacha Lechevallier ([AshLcvr](https://github.com/AshLcvr)):
- Back : API, Fixtures, WebPack
- Langages favoris : PHP, TWIG, JS


