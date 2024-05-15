<?php

namespace App\Repository;

use App\Entity\Contient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Contient>
 *
 * @method Contient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contient[]    findAll()
 * @method Contient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contient::class);
    }

    public function findMaxPriceByUser(User $user): ?float
    {
        return $this->createQueryBuilder('c')
            ->select('MAX(article.prix)')
            ->join('c.Contenue', 'article')
            ->join('c.Dans', 'liste')
            ->andWhere('liste.Id_user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findMinPriceByUser(User $user): ?float
    {
        return $this->createQueryBuilder('c')
            ->select('MIN(article.prix)')
            ->join('c.Contenue', 'article')
            ->join('c.Dans', 'liste')
            ->andWhere('liste.Id_user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }



    public function findByAvgPriceByUser(User $user): ?float
    {
        return $this->createQueryBuilder('c')
            ->select('AVG(article.prix)')
            ->join('c.Contenue', 'article')
            ->join('c.Dans', 'liste')
            ->andWhere('liste.Id_user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function sumPriceByUser(User $user): ?float
{
    return $this->createQueryBuilder('c')
        ->select('SUM(article.prix * c.quantite)')
        ->join('c.Contenue', 'article')
        ->join('c.Dans', 'liste')
        ->andWhere('liste.Id_user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getSingleScalarResult();
}

    public function findByTypeDepense(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->select('article.Type, SUM(article.prix * c.quantite) as total')
            ->join('c.Contenue', 'article')
            ->join('c.Dans', 'liste')
            ->andWhere('liste.Id_user = :user')
            ->groupBy('article.Type')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    

//    /**
//     * @return Contient[] Returns an array of Contient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Contient
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
