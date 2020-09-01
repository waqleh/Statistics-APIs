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
        //@todo finish this test
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

    private function insertHotelAndReviews($dateFrom, $dateTo)
    {
        $hotel = new Hotel();
        $hotel->setName("The Beverly Hills Hotel, Los Angeles");
        $hotel->getReviews();

        // Now, mock the repository so it returns the mock of the employee
        $employeeRepository = $this->createMock(ObjectRepository::class);
        // use getMock() on PHPUnit 5.3 or below
        // $employeeRepository = $this->getMock(ObjectRepository::class);
        $employeeRepository->expects($this->any())
            ->method('find')
            ->willReturn($hotel);

        // Last, mock the EntityManager to return the mock of the repository
        // (this is not needed if the class being tested injects the
        // repository it uses instead of the entire object manager)
        $objectManager = $this->createMock(ObjectManager::class);
        // use getMock() on PHPUnit 5.3 or below
        // $objectManager = $this->getMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($employeeRepository);

        $salaryCalculator = new SalaryCalculator($objectManager);
        $this->assertEquals(2100, $salaryCalculator->calculateTotalSalary(1));
    }
}