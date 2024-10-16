<?php

namespace App\Repository;

use App\Entity\Investments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Investments>
 */
class InvestmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investments::class);
    }
    public function findAllSortedByFundingDifference()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.currentFunding / i.price ', 'DESC') // Sort by the difference
            ->addOrderBy('i.createdAt', 'DESC') // Optional: secondary sort by creation date
            ->getQuery()
            ->getResult();
    }
    
    public function findPaginated(int $page, int $perPage)
    {
            $query = $this->createQueryBuilder('i')
            ->orderBy('i.price - i.currentFunding', 'ASC') // Sort by the difference
            ->addOrderBy('i.createdAt', 'DESC') // Optional: secondary sort by creation date
            ->getQuery();

        return $this->paginate($query, $perPage, $page);
    }
    public function findByCategory(int $categoryId)
    {
        return $this->createQueryBuilder('i')
            ->join('i.categorie', 'c') 
            ->andWhere('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('i.price - i.currentFunding', 'ASC') // Sort by the difference
            ->addOrderBy('i.createdAt', 'DESC') // Optional: secondary sort by creation date
            ->getQuery()
            ->getResult();
    }
    public function findPaginatedbycat(int $page, int $perPage,int $categoryId)
    {
            $query = $this->createQueryBuilder('i')
            ->join('i.categorie', 'c') // Assuming 'categorie' is the correct property name in the Project entity
            ->andWhere('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('i.price - i.currentFunding', 'ASC') // Sort by the difference
            ->addOrderBy('i.createdAt', 'DESC') // Optional: secondary sort by creation date
            ->getQuery();

        return $this->paginate($query, $perPage, $page);
    }
    private function paginate($query, $perPage, $page)
    {
        $offset = ($page - 1) * $perPage;
        $query->setFirstResult($offset)
            ->setMaxResults($perPage);

        return new Paginator($query, $fetchJoinCollection = true);
    }


    //    /**
    //     * @return Investments[] Returns an array of Investments objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Investments
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
