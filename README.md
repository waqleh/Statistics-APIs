# Statistics-APIs
Coding challenge - Statistics APIs - hotel reviews

API url: `http://localhost/api/getAvgScore/{hotelId}/{dateFrom}/{dateTo}`

e.g: http://localhost/api/getAvgScore/1/2019-01-10/2020-02-01

## Setup the development environment:

### Step 1 install docker and docker-compose:

[how to install docker](https://docs.docker.com/get-docker/) and [docker-compose](https://docs.docker.com/compose/install/):

### step 2 clone:

    $ git clone git@github.com:waqleh/Statistics-APIs.git

### step 3 build:

    $ cd {projectDir}/docker
    $ docker-compose build

### step 4 run:

    $ docker-compose up -d

### step 5 composer install:

    $ docker-compose exec php-fpm composer install

### step 6 dev env migration:

    $ docker-compose run --rm php-fpm php bin/console doctrine:migrations:migrate
    $ docker-compose run --rm php-fpm php bin/console doctrine:fixtures:load

### step 7 test env migration, to be able to run phpunit tests:

    $ docker-compose run --rm php-fpm php bin/console doctrine:migrations:migrate -etest
    $ docker-compose run --rm php-fpm php bin/console doctrine:fixtures:load -etest

## Starting Project:

    $ cd {projectDir}/docker
    $ docker-compose up

## Stopping Project:

    $ cd {projectDir}/docker
    $ docker-compose down

## Running tests, make sure that db is migrated and fixtures are ready:

    $ cd {projectDir}/docker
    $ docker-compose up -d
    $ docker-compose exec php-fpm composer test

## Access DB:

    $ cd {projectDir}/docker

dev env: `$ docker exec -it docker_database mysql wa_hotels -uhotels_admin -pdb-password`

test env: `$ docker exec -it docker_test-database mysql test_hotels -uhotels_admin -pdb-password`

## This project was created as a coding challenge:

### Coding challenge - Statistics APIs

Please use PHP 7.4, Symfony 5, Doctrine and an RDMS of your choice to create one service which provides a REST API endpoint as explained bellow.
 
### Todo
- Create these two tables:
    1) `hotel`(`id`, `name`)
    2) `review`(`id`, `hotel_id`, `score`, `comment`, `created_date`)
- Fill the `hotel` table with 10 rows with random names
- Fill the `review` table with a total number of 100.000 reviews which are distributed randomly over the last two years. Score and comments should be randomly filled and each hotel should have a random number of reviews as well.
- #### Overtime Endpoint:
  It gets a hotel-id and a date range from http requests and returns the overtime average score of the hotel for grouped date ranges. The date range is grouped as follows:
  - 1 - 29 days: Grouped daily
  - 30 - 89 days: Grouped weekly
  - More than 89 days: Grouped monthly
  
  The response should contain "review-count", "average-score" and "date-group" (either the day, calendar-week or the month) per data point.
- Use a DTO layer and a serializer to generate the response for the endpoint.
- Use Doctrine QueryBuilder for fetching the data.
- Test the application by functional or unit tests using PHPUnit.
- Note that it is a minimal amount of data to work with, the implementation should ideally work with larger amount of hotels and reviews.
- Upload the project on github, gitlab or bitbucket and send it to us. You can use a temporary or throwaway account to maintain privacy.

Happy coding!