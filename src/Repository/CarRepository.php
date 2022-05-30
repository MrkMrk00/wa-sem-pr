<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
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

    private function createDbalQb(): QueryBuilder
    {
        return $this->_em->getConnection()->createQueryBuilder();
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

    public function joinRatingsOnCars(QueryBuilder $qb, string $fromAlias, string $distAlias)
    {
        $qb0 = $this->createDbalQb();
        $qb0->select(
            "$distAlias.car_id",
            "SUM($distAlias.value) review",
            "COUNT($distAlias.car_id) AS review_count")
            ->from('car_review', $distAlias)
            ->groupBy("$distAlias.car_id");

        $qb->leftJoin($fromAlias, '(' . $qb0->getSQL() . ')', $distAlias, "$fromAlias.id=$distAlias.car_id");
    }

    public function getRating(Car $car): ?array
    {
        $qb0 = $this->createDbalQb();
        try {
            return $qb0->select(
                'SUM(r.value) review',
                'COUNT(r.car_id) AS review_count')
                ->from('car_review', 'r')
                ->where($qb0->expr()->eq('c.car_id', $car->getId()))
                ->groupBy('r.car_id')
                ->executeQuery()
                ->fetchAllAssociative();
        } catch (Exception $e) {
            return null;
        }
    }

    public function search(array $data)
    {
        $qb0 = $this->createDbalQb();
        $qb0->select(
            'c.id',
            'c.name',
            'c.generation',
            'c.manufactured',
            'c.driven_axle as drivenAxle',
            'c.seat_count as seatCount'
        )
            ->from('car', 'c')
            ->leftJoin('c', 'engine', 'e', 'c.engine_id=e.id');

        $where_applied = false;

        if (!empty($data['manufacturer'])) {
            $qb0->where(
                $qb0->expr()->eq(
                    'c.manufacturer_id',
                    $data['manufacturer']->getId()
                )
            );
            $where_applied = true;
        }

        if (!empty($data['engine_min_power'])) {
            $expr = $qb0->expr()
                ->gt('e.max_power', (int)$data['engine_min_power']);

            if ($where_applied) $qb0->andWhere($expr);
            else $qb0->where($expr);
        }

        if (!empty($data['rating'])) {
            $this->joinRatingsOnCars($qb0, 'c', 'r');
            $expr = $qb0->expr()
                ->gt('(r.review/r.review_count+1)', (int)$data['rating']/100);

            if ($where_applied) $qb0->andWhere($expr);
            else $qb0->where($expr);
        }

        if (!empty($data['fuel'])) {
            $expr = $qb0->expr()
                ->eq('e.fuel', '"'.htmlspecialchars($data['fuel']).'"');

            if ($where_applied) $qb0->andWhere($expr);
            else $qb0->where($expr);
        }

        switch ($data['order_by_field']) {
            case 'power':
                $qb0->orderBy('e.max_power', $data['order_by_direction']);
                break;
            case 'torque':
                $qb0->orderBy('e.max_torque', $data['order_by_direction']);
                break;
            case 'rating':
                $qb0->orderBy('(r.review/r.review_count)', $data['order_by_direction']);
                break;
        }

        try {
            return $qb0
                ->executeQuery()
                ->fetchAllAssociative();

        } catch (Exception $e) {
            return $e->getMessage(); //TODO: dej tam return null
        }
    }

    public function getTenTopRated()
    {
        $qb0 = $this->createDbalQb();
        $qb0->select(
            'r.car_id', 'r.user_id',
            'SUM(r.value) review',
            'COUNT(r.id) AS review_count')
            ->from('car_review', 'r')
            ->groupBy('r.id');

        $qb1 = $this->createDbalQb();
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