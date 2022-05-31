<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use \Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 *
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    private function createDbalQb(): QueryBuilder
    {
        return $this->_em->getConnection()->createQueryBuilder();
    }

    public function add(Image $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Image $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function dbalFind(?int $car_id=null): ?array
    {
        $qb = $this->createDbalQb();
        $qb->select('*')
            ->from('image', 'i');

        if ($car_id != null)
            $qb->where($qb->expr()->eq('car_id', (int)$car_id));

        try {
            return $qb->execute()->fetchAllAssociative();
        } catch (Exception|DriverException $e) {
            return null;
        }
    }

    public function attachImagesOnCars(array $cars): array
    {
        $map_fun = function ($car) {
            $car_id = $car['id'];
            $images = $this->dbalFind($car_id);

            $car['images'] = empty($images) ? [] : $images;
            return $car;
        };
        return array_map($map_fun, $cars);
    }
}
