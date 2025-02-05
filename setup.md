## Run the following commands for setup:

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


