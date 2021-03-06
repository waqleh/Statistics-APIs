<?php
/**
 * @author: Walid Aqleh <w.aqleh@sportradar.com>
 * Date: 01.09.20
 */

namespace App\Tests\Controller;

use App\Entity\Hotel;
use App\Entity\Review;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testGetAvgScoreValidationError()
    {
        $hotelId = 'x';
        $dailyFromDate = (new DateTime(sprintf('-%d days', 29)))->format('m-d-Y');
        $toDate = (new DateTime('now'))->format('m-d-Y');

        static::ensureKernelShutdown(); // creating factories boots the kernel; shutdown before creating the client
        $client = static::createClient();
        $client->request('GET', "/api/getAvgScore/$hotelId/$dailyFromDate/$toDate");
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertStringContainsString('status', $client->getResponse()->getContent());
        $this->assertStringContainsString('fail', $client->getResponse()->getContent());
        $this->assertStringContainsString(sprintf("Invalid hotel ID %s", $hotelId), $client->getResponse()->getContent());
        $this->assertStringContainsString(sprintf("Invalid date from %s", $dailyFromDate), $client->getResponse()->getContent());
        $this->assertStringContainsString(sprintf("Invalid date to %s", $toDate), $client->getResponse()->getContent());
    }

    public function testGetAvgScoreHotelValidationError()
    {
        $hotelId = 'x';
        $dailyFromDate = (new DateTime(sprintf('-%d days', 29)))->format('Y-m-d');
        $toDate = (new DateTime('now'))->format('Y-m-d');

        static::ensureKernelShutdown(); // creating factories boots the kernel; shutdown before creating the client
        $client = static::createClient();
        $client->request('GET', "/api/getAvgScore/$hotelId/$dailyFromDate/$toDate");
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertStringContainsString('status', $client->getResponse()->getContent());
        $this->assertStringContainsString('fail', $client->getResponse()->getContent());
        $this->assertStringContainsString(sprintf("Invalid hotel ID %s", $hotelId), $client->getResponse()->getContent());
    }

    public function testGetHotelAvgReviewScoreGroupedDaily()
    {
        $hotelRepository = $this->entityManager
            ->getRepository(Hotel::class);
        $hotel = $hotelRepository->findOneBy([], null, $limit = 1);

        // make sure that we have at least one review
        $this->createReview($hotel);

        $hotelId = $hotel->getId();
        $dailyFromDate = (new DateTime(sprintf('-%d days', 29)))->format('Y-m-d');
        $toDate = (new DateTime('now'))->format('Y-m-d');

        static::ensureKernelShutdown(); // creating factories boots the kernel; shutdown before creating the client
        $client = static::createClient();
        $client->request('GET', "/api/getAvgScore/$hotelId/$dailyFromDate/$toDate");
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertStringContainsString('status', $client->getResponse()->getContent());
        $this->assertStringContainsString('success', $client->getResponse()->getContent());

        $reviews = $hotel->getReviews()->filter(function($review) {
            return $review->getCreatedDate() > (new DateTime(sprintf('-%d days', 29)))
                && $review->getCreatedDate() < (new DateTime('now'));
        });

        foreach ($reviews as $review) {
            $dateGroup = $review->getCreatedDate()->format('Y-m-d');
            $this->assertStringContainsString('"date-group":"' . $dateGroup . '"', $client->getResponse()->getContent());
        }
    }

    public function testGetHotelAvgReviewScoreGroupedWeekly()
    {
        $hotelRepository = $this->entityManager
            ->getRepository(Hotel::class);
        $hotel = $hotelRepository->findOneBy([], null, $limit = 1);

        // make sure that we have at least one review
        $this->createReview($hotel);

        $hotelId = $hotel->getId();
        $dailyFromDate = (new DateTime(sprintf('-%d days', 89)))->format('Y-m-d');
        $toDate = (new DateTime('now'))->format('Y-m-d');

        static::ensureKernelShutdown(); // creating factories boots the kernel; shutdown before creating the client
        $client = static::createClient();
        $client->request('GET', "/api/getAvgScore/$hotelId/$dailyFromDate/$toDate");
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertStringContainsString('status', $client->getResponse()->getContent());
        $this->assertStringContainsString('success', $client->getResponse()->getContent());

        $reviews = $hotel->getReviews()->filter(function($review) {
            return $review->getCreatedDate() > (new DateTime(sprintf('-%d days', 89)))
                && $review->getCreatedDate() < (new DateTime('now'));
        });

        foreach ($reviews as $review) {
            $dateGroup = $review->getCreatedDate()->format('Y-m W');
            $this->assertStringContainsString('"date-group":"'.$dateGroup.'"', $client->getResponse()->getContent());
        }
    }

    public function testGetHotelAvgReviewScoreGroupedMonthly()
    {
        $hotelRepository = $this->entityManager
            ->getRepository(Hotel::class);
        $hotel = $hotelRepository->findOneBy([], null, $limit = 1);

        // make sure that we have at least one review
        $this->createReview($hotel);

        $hotelId = $hotel->getId();
        $dailyFromDate = (new DateTime(sprintf('-%d days', 365)))->format('Y-m-d');
        $toDate = (new DateTime('now'))->format('Y-m-d');

        static::ensureKernelShutdown(); // creating factories boots the kernel; shutdown before creating the client
        $client = static::createClient();
        $client->request('GET', "/api/getAvgScore/$hotelId/$dailyFromDate/$toDate");
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertStringContainsString('status', $client->getResponse()->getContent());
        $this->assertStringContainsString('success', $client->getResponse()->getContent());

        $reviews = $hotel->getReviews()->filter(function($review) {
            return $review->getCreatedDate() > (new DateTime(sprintf('-%d days', 365)))
                && $review->getCreatedDate() < (new DateTime('now'));
        });

        foreach ($reviews as $review) {
            $dateGroup = $review->getCreatedDate()->format('Y-m');
            $this->assertStringContainsString('"date-group":"'.$dateGroup.'"', $client->getResponse()->getContent());
        }
    }

    private function createReview($hotel)
    {
        $review = new Review();
        $score = 3;
        $review->setScore($score);
        $comment = "Want to sleep where Marilyn Monroe once lived? Dubbed the Pink Palace, the retro-luxe Beverly Hills Hotel on historic Sunset Boulevard has served as a meeting spot and makeshift home to a slew of movie stars like Monroe.";
        $review->setComment($comment);
        $hotel->getReviews($review);

        $this->entityManager->persist($hotel);

        return $review;
    }
}