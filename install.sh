docker run --rm --interactive --tty  --volume $PWD:/app composer install
cp .env.example .env
docker-compose up -d
docker-compose exec app php artisan migrate