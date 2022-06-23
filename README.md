# Présentation
Garden Connect est un site visant à mettre en relation des particuliers et/ou professionnels autour de la vente de produits de potager.  
Son objectif premier est de favoriser le circuit court.  
Nous travaillons toujours sur ce projet afin de rajouter de nouvelles fonctionnalités.  
Tous vos retours sont les bienvenus ! :)  

# Installation

Les instructions suivantes doivent être éxecutées dans le dossier racine du projet

## Base de données
- Créez la base de données :

  `php bin/console doctrine:database:create`

- Mettez à jour la base de donnée grâce aux fichiers de migrations :

  `php bin/console doctrine:migrations:migrate`

- Remplissez la base de données avec les données des fixtures :

  `php bin/console doctrine:fixtures:load`

## Dépendances

Afin d'installer les dépendances nécessaires, rentrez les lignes de commandes suivantes :

### Pour Symfony

`composer install`

### Pour Webpack Encore :

`npm install`

# Démarrage des serveurs
Les lignes de commandes suivantes doivent être éxecutées à partir du dossier racine du projet.

### Serveur Apache:

`php -S localhost:3000 -t public`

### Serveur Webpack Encore:

`npm run watch` 
ou 
`npm run dev server`
