<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\Review;
use App\Factory\HotelFactory;
use App\Factory\ReviewFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HotelAndReviewFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $numberOfHotels = 10;
        $numberOfHotelReviews = 1000;
        // create random hotels
        HotelFactory::new()->createMany($numberOfHotels);
        for ($i = 0; $i < $numberOfHotelReviews; $i++) {
            // create random hotel reviews
            ReviewFactory::new(['hotel' => HotelFactory::random()])->create();
        }
        $manager->flush();

    }
}
