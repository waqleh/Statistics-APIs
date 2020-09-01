# Statistics-APIs
Coding challenge - Statistics APIs - hotel reviews

build project:

    $ cd docker
    $ docker-compose build

steps:

    $ cd docker
    $ docker-compose up -d
    $ docker-compose run --rm php-fpm php bin/console doctrine:migrations:migrate
    $ docker-compose run --rm php-fpm php bin/console doctrine:fixtures:load

url: http://localhost/

run tests:

    $ cd docker
    $ docker-compose up -d
    $ docker-compose run --rm php-fpm php bin/console doctrine:migrations:migrate -etest
    $ docker-compose run --rm php-fpm php bin/console doctrine:fixtures:load -etest
    $ docker-compose exec php-fpm composer test