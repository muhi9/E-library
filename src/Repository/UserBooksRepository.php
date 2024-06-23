<?php

namespace App\Repository;

use App\Entity\UserBooks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBooks>
 *
 * @method UserBooks|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBooks|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBooks[]    findAll()
 * @method UserBooks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBooks::class);
    }

//    /**
//     * @return UserBooks[] Returns an array of UserBooks objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserBooks
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
