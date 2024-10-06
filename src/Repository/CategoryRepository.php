<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }


    function findCategory($filter)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('b');

         if (!empty($filter['category'])) {
             $qb->andWhere('b.category LIKE :category')
                ->setParameter('category', $filter['category']);
        }

        $result =  $qb->getQuery()->getResult();
        return $result;
    }

    public function getCategory()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select("c.name");
        $result =  $qb->getQuery()->getResult();
        return $result;
    }
}
