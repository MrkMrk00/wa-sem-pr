<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function add(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getTenTopRated(Connection $conn)
    {
        $qb0 = $conn->createQueryBuilder();
        $qb0->select(
            'r.car_id', 'r.user_id',
            'SUM(r.value) review',
            'COUNT(r.id) AS review_count')
            ->from('car_review', 'r')
            ->groupBy('r.id');

        $qb1 = $conn->createQueryBuilder();
        try {
            $result = $qb1->select(
                'c.*',
                'c.driven_axle AS drivenAxle',
                'c.seat_count AS seatCount',
                'c.added_by_id AS addedBy',
                'm.name AS manufacturer_name',
                'COALESCE(r.review, 0) AS review',
                'COALESCE(r.review_count, 1) AS review_count')
                ->from('car', 'c')
                ->leftJoin('c', '(' . $qb0->getSQL() . ')', 'r', 'r.car_id = c.id')
                ->leftJoin('c', 'manufacturer', 'm', 'c.manufacturer_id=m.id')
                ->setMaxResults(10)
                ->executeQuery()
                ->fetchAllAssociative();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $result;
    }
}