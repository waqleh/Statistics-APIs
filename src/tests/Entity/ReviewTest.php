<?php
/**
 * @author: Walid Aqleh <w.aqleh@sportradar.com>
 * Date: 01.09.20
 */

namespace App\Tests\Entity;

use App\Entity\Hotel;
use App\Entity\Review;
use PHPUnit\Framework\TestCase;


class ReviewTest extends TestCase
{
    public function testSettingReviewHotel()
    {
        $review = new Review();
        $hotel = new Hotel();
        $name = "The Beverly Hills Hotel, Los Angeles";
        $hotel->setName($name);
        $review->setHotel($hotel);
        $this->assertEquals($hotel, $review->getHotel());
    }

    public function testSettingReviewScore()
    {
        $review = new Review();
        $score = 3;
        $review->setScore($score);
        $this->assertEquals($score, $review->getScore());
    }

    public function testSettingReviewComment()
    {
        $review = new Review();
        $comment = "Want to sleep where Marilyn Monroe once lived? Dubbed the Pink Palace, the retro-luxe Beverly Hills Hotel on historic Sunset Boulevard has served as a meeting spot and makeshift home to a slew of movie stars like Monroe.";
        $review->setComment($comment);
        $this->assertEquals($comment, $review->getComment());
    }

    public function testSettingReviewCreatedDate()
    {
        $review = new Review();
        $createdDate = new \DateTime('now');
        $review->setCreatedDate($createdDate);
        $this->assertEquals($createdDate, $review->getCreatedDate());
    }
}