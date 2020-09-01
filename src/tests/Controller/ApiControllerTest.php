<?php
/**
 * @author: Walid Aqleh <w.aqleh@sportradar.com>
 * Date: 01.09.20
 */

namespace App\Tests\Controller;

//use App\Entity\Hotel;
//use App\Entity\Review;
//use App\Factory\HotelFactory;
//use App\Factory\ReviewFactory;
//use App\Repository\HotelRepository;
use App\Entity\Hotel;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//use function Zenstruck\Foundry\create;
//use function Zenstruck\Foundry\factory;

class ApiControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetHotelAvgReviewScoreggggg()
    {
        $hotelRepository = $this->entityManager
            ->getRepository(Hotel::class);
        $hotelId = $hotelRepository->findOneBy([], null, $limit = 1)->getId();
        $dailyFromDate = (new \DateTime(sprintf('-%d days', 365)))->format('Y-m-d');
        $toDate = (new \DateTime('now'))->format('Y-m-d');

        static::ensureKernelShutdown(); // creating factories boots the kernel; shutdown before creating the client
        $client = static::createClient();
        $client->request('GET', "/api/getAvgScore/$hotelId/$dailyFromDate/$toDate");
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertStringContainsString('status', $client->getResponse()->getContent());
        $this->assertStringContainsString('success', $client->getResponse()->getContent());

//        $this->assertJson($client->getResponse()->getContent(), 'asdasddsaasd');
    }

//    /**
//     * @dataProvider provideUrls
//     * @param string $url
//     */
//    public function testGetHotelAvgReviewScore($url)
//    {
//
////        $hotelRepository = static::$container->get(HotelRepository::class);
////        $hotels = $hotelRepository->findBy([], null, 1
//
//
//        $client = static::createClient();
//        $client->request('GET', $url);
//        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
//        $this->assertResponseHeaderSame('Content-Type', 'application/json');
//        $this->assertStringContainsString('status', $client->getResponse()->getContent());
//
////        $this->assertJson($client->getResponse()->getContent(), 'asdasddsaasd');
//    }
//    public function provideUrls()
//    {
//        $hotelId = $this->fakeHotelReviews();
//        $dailyFromDate = (new \DateTime(sprintf('-%d days', 29)))->format('Y-m-d ');
//        $weeklyFromDate = (new \DateTime(sprintf('-%d days', 89)))->format('Y-m-d');
//        $monthlyFromDate = (new \DateTime(sprintf('-%d days', 365)))->format('Y-m-d');
//        $toDate = (new \DateTime('now'))->format('Y-m-d');
//        return [
//            //1 - 29 days: Grouped daily
//            ["/api/getAvgScore/$hotelId/$dailyFromDate/$toDate"],
//            //30 - 89 days: Grouped weekly
//            ["/api/getAvgScore/$hotelId/$weeklyFromDate/$toDate"],
//            //More than 89 days: Grouped monthly
//            ["/api/getAvgScore/$hotelId/$monthlyFromDate/$toDate"],
//        ];
//    }
//
//    /**
//     * craete fake hotel and its reviews
//     * @return int
//     */
//    private function fakeHotelReviews()
//    {
//
//        $hotelRepository = static::$container->get(HotelRepository::class);
//        $hotels = $hotelRepository->findOneBy([], null, $limit = 1);
//        var_dump($hotels);
////        echo '--->:'.var_dump($hotels[0]->getId());die;
//
////        $numberOfHotelReviews = 100;
////        $hotel = create(Hotel::class, ['name' => 'foo']);
////        for ($i = 0; $i < $numberOfHotelReviews; $i++) {
////            // create random hotel reviews
////            factory(Review::class, ['hotel' => $hotel]);
////        }
////        echo '--->:'.var_dump($hotel);die;
////        return $hotel->getId();
//
////        $review = new Review();
////        $hotel = new Hotel();
////        $name = "The Beverly Hills Hotel, Los Angeles";
////        $hotel->setName($name);
////        $review->setHotel($hotel);
//
////        $numberOfHotelReviews = 100;
////        // create random hotels
////        $hotel = HotelFactory::new()->create();
////        for ($i = 0; $i < $numberOfHotelReviews; $i++) {
////            // create random hotel reviews
////            ReviewFactory::new(['hotel' => $hotel])->create();
////        }
////        return $hotel->getId();
//    }
}