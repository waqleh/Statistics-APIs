<?php
/**
 * @author: Walid Aqleh <w.aqleh@sportradar.com>
 * Date: 31.08.20
 */

namespace App\Tests\DTO;

use App\DTO\ReviewDTO;
use PHPUnit\Framework\TestCase;

class ReviewDTOTest extends TestCase
{
    public function testConstructingReviewDTO()
    {
        $reviewCount = 5;
        $averageScore = 3;
        $dateGroup = "2019-01";//todo fix me
        $mappedData = (new ReviewDTO($reviewCount, $averageScore, $dateGroup))->mapData();
        $this->assertEquals([
            'review-count' => $reviewCount,
            'average-score' => $averageScore,
            'date-group' => $dateGroup,
        ], $mappedData);
    }
}