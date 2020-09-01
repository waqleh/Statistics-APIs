<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * get reviews between two dates and hotel id
     * @param int $hotelId
     * @param string $dateFrom YYYY-MM-DD
     * @param string $dateTo YYYY-MM-DD
     * @return Review[] Returns an array of Review objects
     */
    public function findByHotelIdAndCreatedDateFields($hotelId, $dateFrom, $dateTo)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.hotel_id = :hotel_id')
            ->andWhere('r.created_date BETWEEN :date_from AND :date_to')
            ->setParameter('hotel_id', $hotelId)
            ->setParameter('date_from', $dateFrom)
            ->setParameter('date_to', $dateTo)
            ->orderBy('r.created_date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
