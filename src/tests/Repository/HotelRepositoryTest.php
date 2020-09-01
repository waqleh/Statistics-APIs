<?php
/**
 * @author: Walid Aqleh <w.aqleh@sportradar.com>
 * Date: 01.09.20
 */

namespace App\Tests\Repository;

use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HotelRepositoryTest extends WebTestCase
{
    public function testGettingHotelReviews()
    {
        //@todo finish this test
        static::createClient();
        $hotelRepository = static::$container->get(HotelRepository::class);
        $hotels = $hotelRepository->findAll();

    }
}