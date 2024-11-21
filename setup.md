Run the following commands for setup:

- ``docker compose build --no-cache`` to build the project
- ``docker compose up -d`` to start the containers
- ``docker exec -it web_app composer install`` to download dependencies and dev-dependencies
- ``docker exec -it web app php bin/console doctrine:database:create`` to create the database
- ``docker exec -it web app php bin/console tailwind:build --watch --poll`` to run tailwindcss (--poll when running on docker)