<?php
/**
 * @author: Walid Aqleh <w.aqleh@sportradar.com>
 * Date: 31.08.20
 */

namespace App\DTO;

class ReviewDTO
{
    /**
     * @var int
     */
    private $reviewCount;

    /**
     * @var float
     */
    private $averageScore;

    /**
     * @var string
     */
    private $dateGroup;

    /**
     * ReviewDTO constructor.
     * @param int $reviewCount
     * @param float $averageScore
     * @param string $dateGroup
     */
    public function __construct($reviewCount, $averageScore, $dateGroup)
    {
        $this->reviewCount = $reviewCount;
        $this->averageScore = $averageScore;
        $this->dateGroup = $dateGroup;
    }

    public function mapData(): array
    {
        return [
            'review-count' => $this->reviewCount,
            'average-score' => $this->averageScore,
            'date-group' => $this->dateGroup,
        ];
    }
}