<?php

namespace App\Repository;

use App\Entity\ShoppingCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShoppingCart>
 *
 * @method ShoppingCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingCart[]    findAll()
 * @method ShoppingCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingCart::class);
    }

    public function add(ShoppingCart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShoppingCart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneById(int $id)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->addSelect("ShoppingCartEntry");
        $queryBuilder->addSelect("product");
        $queryBuilder->leftJoin('c.ShoppingCartEntry', 'ShoppingCartEntry');
        $queryBuilder->leftJoin('ShoppingCartEntry.product', 'product');
        $queryBuilder->andWhere('c.id = :id')->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findByDate()
    {
        $date = new \DateTime();
        $date->modify('-24 hour');

        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->addSelect("ShoppingCartEntry");
        $queryBuilder->leftJoin('c.ShoppingCartEntry', 'ShoppingCartEntry');
        $queryBuilder->where('c.dateadded < :date')
        ->setParameter(':date', $date);

        return $queryBuilder->getQuery()->getResult();
    }



//    /**
//     * @return ShoppingCart[] Returns an array of ShoppingCart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShoppingCart
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
