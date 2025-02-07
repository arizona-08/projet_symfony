## Installation

Il y a deux fichiers .env à la racine du projet, un pour le développement et un pour la recette.
enlever le .example pour les utiliser.

## Ne pas oublier de rajouter dans le .env

- `` WEATHER_API_KEY= on te donnera la clef dans le livrable `` 

## Pour la recette

- ``APP_DEBUG=0`` pour désactiver le mode debug en recette




- ``docker compose build --no-cache`` to build the project 
- ``docker compose up -d`` to start the containers
- ``docker exec -it web_app composer install`` to download dependencies and dev-dependencies
- ``docker exec -it web_app php bin/console doctrine:database:create`` to create the database
- ``docker exec -it web_app php bin/console tailwind:build --watch --poll`` to run tailwindcss (--poll when running on docker)
- ``docker exec -it web_app php bin/console hautelook:fixtures:load --env=dev`` jouer les fixtures



## Recette
Pour passer en recette il faut changer la valeur de APP_ENV dans le fichier .env puis :
- ``docker compose down `` 
et 
- ``docker compose up ``