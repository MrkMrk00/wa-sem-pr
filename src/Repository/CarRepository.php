<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use \Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

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
            "SUM($distAlias.value) AS review",
            "COUNT($distAlias.car_id) AS review_count")
            ->from('car_review', $distAlias)
            ->groupBy("$distAlias.car_id");

        $qb1 = $this->createDbalQb();
        $qb1->select(
            'c.id AS car_id',
                'COALESCE(r.review, 0) AS review',
                'COALESCE(r.review_count, 1) AS review_count')
            ->distinct()
            ->from('car', 'c')
            ->leftJoin('c', '(' . $qb0->getSQL() . ')', 'r', 'c.id=r.car_id');

        $qb->leftJoin($fromAlias, '(' . $qb1->getSQL() . ')', $distAlias, "$fromAlias.id=$distAlias.car_id");
    }

    public function search(array $data): ?array
    {
        if (!in_array($data['order_by_direction'], ['ASC', 'DESC'])) {
            throw new BadRequestException();
        }

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

        $this->joinRatingsOnCars($qb0, 'c', 'r');

        $where_applied = false;

        if (!empty($data['manufacturer'])) {
            $qb0->where(
                $qb0->expr()->eq(
                    'c.manufacturer_id',
                    (int)$data['manufacturer']->getId()
                )
            );
            $where_applied = true;
        }

        if (!empty($data['engine_min_power'])) {
            $expr = $qb0->expr()
                ->gt('e.max_power', (int)$data['engine_min_power']);

            if ($where_applied) $qb0->andWhere($expr);
            else {
                $qb0->where($expr);
                $where_applied = true;
            }
        }

        if (!empty($data['rating'])) {
            $expr = $qb0->expr()
                ->gt('(r.review/r.review_count+1)', (int)$data['rating']/100);

            if ($where_applied) $qb0->andWhere($expr);
            else {
                $qb0->where($expr);
                $where_applied = true;
            }
        }

        if (!empty($data['fuel'])) {
            $expr = $qb0->expr()
                ->eq('e.fuel', '"'.htmlspecialchars($data['fuel']).'"');

            if ($where_applied) $qb0->andWhere($expr);
            else {
                $qb0->where($expr);
                $where_applied = true;
            }
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
                ->execute()
                ->fetchAllAssociative();

        } catch (Exception|DriverException $e) {
            return null;
        }
    }

    public function getTenTopRated(): ?array
    {
        $qb1 = $this->createDbalQb();
        $qb1->select(
            'c.*',
            'c.driven_axle AS drivenAxle',
            'c.seat_count AS seatCount',
            'c.added_by_id AS addedBy',
            'm.name AS manufacturer_name',
            'COALESCE(r.review, 0) AS review',
            'COALESCE(r.review_count, 1) AS review_count')
            ->from('car', 'c')
            ->leftJoin('c', 'manufacturer', 'm', 'c.manufacturer_id=m.id')
            ->setMaxResults(10);

        $this->joinRatingsOnCars($qb1, 'c', 'r');

        try {
            return $qb1->execute()->fetchAllAssociative();
        } catch (Exception|DriverException $e) {
            return null;
        }
    }
}