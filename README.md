# Statistics-APIs
Coding challenge - Statistics APIs - hotel reviews

Setup the development environment:
To setup the development environment you need to install docker and docker-compose ([how to install docker](https://docs.docker.com/get-docker/) and [docker-compose](https://docs.docker.com/compose/install/)):

Build docker project:

    $ cd docker
    $ docker-compose build

Setup project:

    $ git clone git@github.com:waqleh/Statistics-APIs.git
    $ cd docker
    $ docker-compose up -d
    $ docker-compose run --rm php-fpm php bin/console doctrine:migrations:migrate
    $ docker-compose run --rm php-fpm php bin/console doctrine:fixtures:load

API url: `http://localhost/api/getAvgScore/{hotelId}/{dateFrom}/{dateTo}`

Run tests:

    $ cd docker
    $ docker-compose up -d
    $ docker-compose run --rm php-fpm php bin/console doctrine:migrations:migrate -etest
    $ docker-compose run --rm php-fpm php bin/console doctrine:fixtures:load -etest
    $ docker-compose exec php-fpm composer test

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