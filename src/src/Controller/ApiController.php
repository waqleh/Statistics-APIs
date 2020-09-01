<?php

namespace App\Controller;

use App\DTO\ReviewDTO;
use App\Entity\Hotel;
use App\Entity\Review;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @todo return date group
     * @todo limit date from to to avoid crash
     * @todo return http code
     * @todo add phpdocs
     */
    public function getAvgScore($hotelId, $dateFrom, $dateTo, EntityManagerInterface $em)
    {
        try {
            $validation = $this->validate($hotelId, $dateFrom, $dateTo);
            if (empty($validation)) {
                $reviewRepository = $em->getRepository(Review::class);
                /** @var Review $reviews */
                $reviews = $reviewRepository->findByHotelIdAndCreatedDateFields($hotelId, $dateFrom, $dateTo);

                $avgScores = null;
                $earlier = new DateTime($dateFrom);
                $later = new DateTime($dateTo);

                $days = (int)$later->diff($earlier)->days;

                if ($days < 30) {
                    //1 - 29 days: Grouped daily
                    $score = $this->groupScoreOfEachDay($reviews);
                } else if ($days < 90) {
                    //30 - 89 days: Grouped weekly
                    $score = $this->groupScoreOfEachWeek($reviews);
                } else {
                    //More than 89 days: Grouped monthly
                    $score = $this->groupScoreOfEachMonth($reviews);
                }
                $response = [
                    'status' => 'success',
                    'data' => $score,
                ];
            } else {
                $response = [
                    'status' => 'fail',
                    'data' => $validation,
                ];
            }
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong, contact system administrator',
            ];
        }
        return new JsonResponse($response);
    }

    /**
     * Grouped daily scores
     * @param $reviews
     * @return array
     */
    private function groupScoreOfEachDay($reviews)
    {
        return $this->groupScore($reviews, "Y-m-d");
    }

    /**
     * Grouped weekly scores
     * @param $reviews
     * @return array
     */
    private function groupScoreOfEachWeek($reviews)
    {
        return $this->groupScore($reviews, "Y-m W");
    }

    /**
     * Grouped monthly scores
     * @param $reviews
     * @return array
     */
    private function groupScoreOfEachMonth($reviews)
    {
        return $this->groupScore($reviews, "Y-m");
    }

    /**
     * group hotel review scores
     * @param $reviews
     * @param $range
     * @return array
     * @todo try and make it with one loop by calculating sum in first loop then dividing by count
     * @todo add cache
     */
    private function groupScore($reviews, $range)
    {
        $reviewsGrouped = [];
        $avgScores = [];
        $avgScoresCount = [];
        $response = [];
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

        foreach ($reviewsGrouped as $currentKey => $reviews) {
            $count = count($reviews);
            $avgScores[$currentKey] = array_sum($reviews) / $count;
            $avgScoresCount[$currentKey] = $count;
        }

        foreach ($avgScores as $currentKey => $avgScore) {
            $response[] = (new ReviewDTO($avgScoresCount[$currentKey], $avgScore, $currentKey))->mapData();
        }
        return $response;
    }

    /**
     * validate the user input
     * @param int $hotelId
     * @param string $dateFrom
     * @param string $dateTo
     * @return array
     */
    private function validate($hotelId, $dateFrom, $dateTo)
    {
        $errorMessages = [];
        $format = 'Y-m-d';

        $hotel = $this->getDoctrine()
            ->getRepository(Hotel::class)
            ->find($hotelId);
        if(is_null($hotel)){
            $errorMessages[] = "Invalid hotel ID $hotelId";
        }
        if(!$this->validateDate($dateFrom)){
            $errorMessages[] = "Invalid date from $dateFrom, expected format: $format";
        }
        if(!$this->validateDate($dateTo)){
            $errorMessages[] = "Invalid date to $dateTo, expected format: $format";
        }
        if($dateFrom >= $dateTo){
            $errorMessages[] = "Invalid date range, from ($dateFrom) should be before to ($dateTo)";
        }

        return $errorMessages;
    }

    /**
     * make sure the date is in the correct format
     * @param string $date
     * @param string $format
     * @return bool
     */
    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
