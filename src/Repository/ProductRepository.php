<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllPaginated(int $page = 1, int $itemPerPage = 6, ?int $categoryId = null, ?string $searchTerm = null, array $filter = [], string $sortBy = 'name_desc')
    {
        $queryBuilder = $this->createQueryBuilder('p');
       
        if ($searchTerm) {
            $searchTerm = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($searchTerm));
            $queryBuilder->where('LOWER(p.name) LIKE :searchTerm or LOWER(p.symbol) LIKE :searchTerm ')
            ->setParameter('searchTerm', '%' . strtolower($searchTerm) . '%');
        }

        if($categoryId){
            $queryBuilder->leftJoin('p.category', 'c') 
            ->andWhere('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId);
        }
     
        foreach ($filter as $key => $value) {
            switch ($key) {
                case "latest":
                    $startDate = (new \DateTime())->sub(new \DateInterval('P30D'));
                    $startDate->setTime(0, 0, 0);
        
                    $endDate = new \DateTime();
                    $endDate->setTime(23, 59, 59);
        
                    $queryBuilder->andWhere('p.date_added BETWEEN :start AND :end')
                        ->setParameter('start', $startDate)
                        ->setParameter('end', $endDate);
                    break;
        
                case "minPrice":
                    $queryBuilder->andWhere("p.price >= :minPrice")
                        ->setParameter('minPrice', floatval($value));
                    break;
        
                case "maxPrice":
                    $queryBuilder->andWhere("p.price <= :maxPrice")
                        ->setParameter('maxPrice', floatval($value));
                    break;
        
                default:
                    $queryBuilder->andWhere("p.{$key} = :{$key}")
                        ->setParameter($key, $value);
                    break;
            }
        }
        
        $sortBy = explode('_', $sortBy);
        
        $queryBuilder->orderBy("p.{$sortBy[0]}", $sortBy[1]);

        $queryBuilder->setFirstResult($itemPerPage * ($page - 1))
            ->setMaxResults($itemPerPage);
    
        $paginator = new Paginator($queryBuilder, $fetchJoinCollection = true);
        
        return $paginator;
    }

    public function findBestsellers(int $limit = 6)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->where('p.is_bestseller = true')
            ->setMaxResults($limit);
            
        return $queryBuilder->getQuery()->getResult();
    }

    public function findLatests(int $limit = 6)
    {
        $startDate = (new \DateTime())->sub(new \DateInterval('P30D')); 
        $startDate->setTime(0, 0, 0); 
        
        $endDate = new \DateTime(); 
        $endDate->setTime(23, 59, 59);
       
        $queryBuilder = $this->createQueryBuilder('p');
     
        $queryBuilder->where('p.date_added BETWEEN :start AND :end')
            ->setParameter('start', $startDate) 
            ->setParameter('end', $endDate) 
            ->setMaxResults($limit);
    
        return $queryBuilder->getQuery()->getResult();
    }

    public function findBySimilaryProduct(int $categoryId, int $limit = 6)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        
        // Dodaj złączenie z encją kategorii
        $queryBuilder->join('p.category', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->setMaxResults($limit);
    
        return $queryBuilder->getQuery()->getResult();
    }

    


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
