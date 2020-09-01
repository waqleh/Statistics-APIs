# Statistics-APIs
Coding challenge - Statistics APIs - hotel reviews


steps:

    $ cd docker
    $ docker-compose build
    $ docker-compose up
    $ docker-compose run --rm php-fpm php bin/console doctrine:migrations:migrate
    $ docker-compose run --rm php-fpm php bin/console doctrine:fixtures:load

url: http://localhost/

run tests:

    $ cd docker
    $ docker-compose exec php-fpm composer test