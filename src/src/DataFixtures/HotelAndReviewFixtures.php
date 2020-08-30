<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HotelAndReviewFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create random hotels
        for ($i = 0; $i < 10; $i++) {
            $hotel = new Hotel();
            $hotel->setName(substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 5)), 0, random_int(3, 20)));
            $manager->persist($hotel);
        }
        $manager->flush();

        // create random hotel reviews
        $hotels = $manager->getRepository(Hotel::class)
            ->findAll();
        for ($i = 0; $i < 1000; $i++) {
            $review = new Review();
            $review->setScore(random_int(1, 5));
            $review->setComment(substr(str_shuffle(str_repeat(" 123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, random_int(10, 200)));
            $review->setCreatedDate(new \DateTime(sprintf('-%d days', rand(1, (365 * 2)))));
            $review->setHotel($hotels[random_int(0, 9)]);
            $manager->persist($review);
        }
        $manager->flush();

    }
}
