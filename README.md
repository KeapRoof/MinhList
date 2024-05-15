# Projet Ho Chi Minh

Ce projet Ho Chi Minh est une application web de gestion de listes de courses. Les utilisateurs peuvent crée des listes y mettre des articles et ainsi suivre sur un pannel leur dépense.
Le projet a pour but de répondre au demande de jack asseur

## Fonctionnalités

- Crée,Supprimer une liste
- Ajouter des articles a des listes
- Visualisation avec des statistique des stats

## Installation

Pour installer et exécuter ce projet localement, suivez les étapes ci-dessous :

1. Clonez ce dépôt dans www :

   ```bash
   git clone https://forge.univ-lyon1.fr/p2201096/projetsymfony.git


2. Installez les dépendances PHP avec Composer :
    ```bash
    composer install

3. Installez les dépendances JavaScript avec npm :
   ```bash
    npm install

4. Créez une base de données **ProjetSymfony** dans PhPMyAdmin.


5. Configurez votre fichier .env.local en copiant le fichier .env et en ajustant les valeurs selon votre configuration :
   ```bash
   cp .env .env.local
Remplacez les valeurs de DATABASE_URL par les informations de votre connexion PhpMyAdmin.
Si vous êtes sous windows, vous pouvez utiliser l'URL suivant : 
**DATABASE_URL="mysql://root@127.0.0.1:3306/listeCourse?serverVersion=10.11.2-MySQL&charset=utf8mb4"**

6. Lancez votre serveur (Laragon ou Wamp)


7. Effectuez les migrations Doctrine pour créer la structure de la base de données :
   ```bash
   php bin/console do:mi:mi

## Utilisation

Pour exécuter l'application, suivez les étapes ci-dessous :

1. Compilez les fichiers CSS et JavaScript en mode de développement :
   ```bash
   npm run watch

2. Lancez un serveur PHP dans le dossier public. Dans un autre terminal appliquer les commandes suivantes :
   ```bash
   cd public
   php -S localhost:8080

3. Ouvrez votre navigateur et accédez à l'URL http://localhost:8080 pour utiliser l'application.

## Informations de connexion

Pour accéder à l'interface d'administration, vous pouvez utiliser les informations de connexion suivantes :

- **Email** : minh@minh.fr
- **Mot de passe** : MinhMinh



## Réalisateurs

Ce projet a été réalisé par un groupe d'étudiants :
- Haithem HADJ AZZEM
- Ramazan KUS
- Anis OTMANI