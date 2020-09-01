<?php
/**
 * @author: Walid Aqleh <w.aqleh@sportradar.com>
 * Date: 01.09.20
 */

namespace App\Tests\Repository;

use App\Entity\Hotel;
use App\Entity\Review;
use App\Repository\HotelRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewRepositoryTest extends WebTestCase
{
    public function testGettingHotelReviews()
    {
        //todo finish this test
        static::createClient();
        $hotelRepository = static::$container->get(HotelRepository::class);
        $hotels = $hotelRepository->findAll();
        $dateFrom = '2019-01-18';
        $dateTo = '2020-01-18';
        $hotelId = $this->insertHotelAndReviews($dateFrom, $dateTo);
        $reviewRepository = static::$container->get(ReviewRepository::class);
        $reviews = $reviewRepository->findByHotelIdAndCreatedDateFields($hotelId, $dateFrom, $dateTo);

        foreach ($reviews as $key => $review) {
            echo PHP_EOL.'--> hotelId:' . $review->getHotel()->getId() .' score:'. $review->getScore();
        }
    }
}