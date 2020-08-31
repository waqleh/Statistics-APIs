<?php

namespace App\Controller;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/getAvgScore/{hotelId}/{dateFrom}/{dateTo}", name="get_hotel_avg_score")
     * @param int $hotelId
     * @param string $dateFrom YYYY-MM-DD
     * @param string $dateTo YYYY-MM-DD
     * @param EntityManagerInterface $em
     * @todo add validation
     * @todo identify number of days between dates
     */
    public function getAvgScore($hotelId, $dateFrom, $dateTo, EntityManagerInterface $em){
        //todo add validation

        $reviewRepository = $em->getRepository(Review::class);
        //todo identify number of days between dates
        /** @var Review $reviews */
        $reviews = $reviewRepository->findByHotelIdFromToField($hotelId, $dateFrom, $dateTo);
//        if (!$reviews) {
//            //@TODO return 0 result
//            throw $this->createNotFoundException(sprintf('No hotel with id "%s"', $hotelId));
//        }


        $avgScore = null;
        $earlier = new \DateTime($dateFrom);
        $later = new \DateTime($dateTo);

        $days = (int) $later->diff($earlier)->days;

        if ($days < 30) {
            //1 - 29 days: Grouped daily
            $avgScore = $this->getAvgScoreOfEachDay($reviews);
            echo 'daily';
        } else if ($days < 90) {
            //30 - 89 days: Grouped weekly
            $avgScore = $this->getAvgScoreOfEachWeek($reviews);
            echo 'weekly';
        } else {
            //More than 89 days: Grouped monthly
            $avgScore = $this->getAvgScoreOfEachMonth($reviews);
            echo 'monthly';
        }

        dump([$hotelId, $dateFrom, $dateTo, $days, $avgScore]);
        die('<br>getAvgScore');
    }

    private function getAvgScoreOfEachDay($reviews)
    {
        return $this->calculateAvgScore($reviews, "Y-m-d");
    }

    private function getAvgScoreOfEachWeek($reviews)
    {
        return $this->calculateAvgScore($reviews, "Y-m W");
    }

    private function getAvgScoreOfEachMonth($reviews)
    {
        return $this->calculateAvgScore($reviews, "Y-m");
    }

    /**
     * @param $reviews
     * @param $range
     * @return array
     * @todo try and make it with one loop by calculating sum in first loop then deviding by count
     * @todo add cache
     */
    private function calculateAvgScore($reviews, $range)
    {
        $reviewsGrouped = [];
        $avgScores = [];
        foreach ($reviews as $key => $review) {
            $currentKey = $review->getCreatedDate()->format($range);
            $reviewsGrouped[$currentKey][] = $review->getScore();
//
//            if ($key === array_key_last($reviews)){
//
//            }
//            if(isset($previousKey)
//                && $previousKey != $currentKey){
//                // calculate Avg score of $previousKey
//                //todo round the avg score
//                $avgScore[$previousKey] = (array_sum($reviewsGrouped[$previousKey])/count($reviewsGrouped[$previousKey]));
//            }
//            $previousKey = $currentKey;
        }
        dump([$reviews, $reviewsGrouped]);

        foreach ($reviewsGrouped as $currentKey => $reviews) {
            $avgScores[$currentKey] = array_sum($reviews) / count($reviews);
        }
        return $avgScores;
    }
}
