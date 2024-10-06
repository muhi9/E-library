<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function searchAuthor(string $term): array
    {
        $qb = $this->createQueryBuilder('author');
        $qb->select('author');
        $qb->andWhere('author.name LIKE :searchTerm')
            ->setParameter('searchTerm', $term . '%');
        $result =  $qb->getQuery()->getResult();

        return $result;
    }

   public function findOneBySomeField($value): ?Author
   {
       return $this->createQueryBuilder('a')
           ->andWhere('a.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
