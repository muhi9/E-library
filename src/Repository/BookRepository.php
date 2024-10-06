<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    function findByFilters($filter)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('b')
        ->andWhere('b.validation = 1');

        if (!empty($filter['category'])) {
            $qb->leftJoin('b.categories', 'cats')
                ->andWhere('cats.id =:category')
                ->andWhere('b.validation != 0')
                ->setParameter('category', $filter['category']);
        }

        if (!empty($filter['year_min'])) {
            $qb->andWhere('b.releaseYear BETWEEN :year_min AND :year_max')
                ->andWhere('b.validation = 1')
                ->setParameter('year_min', $filter['year_min'])
                ->setParameter('year_max', $filter['year_max']);
        }

        if (!empty($filter['bookName'])) {
            $qb = $this->createQueryBuilder('book');
            $qb->select('book');
            $qb->andWhere('book.title LIKE :searchTerm')
                ->andWhere('book.validation = 1')
                ->setParameter('searchTerm', $filter['bookName'] . '%');
        }

        $result =  $qb->getQuery()->getResult();
        return $result;
    }

    public function getBooks($admin)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('b');
        if (!$admin=='admin') {
            $qb->where('b.validation = 1');
        }
        $result =  $qb->getQuery()->getResult();
        $books = array();
        foreach ($result as $b) {
            $books[] = [
                'id' => $b->getId(),
                'PDF' => $b->getBook(),
                'title' => $b->getTitle(),
                'releaseYear' => $b->getReleaseYear(),
                'cover' => $b->getCover(),
                'description' => $b->getDescription(),
                'price' => $b->getPrice(),
                'views' => $b->getViews(),
                'isFree' => $b->isIsFree(),
                'validation' => $b->isValidation(),
                'isPublish' => $b->isIsPublish(),
                'category' => $b->getCategoriList(),
                'publishingHouse' => $b->getPublishingHouse(),
                'author' => $b->getAuthorList(),
                'authorId' => $b->getAuthorId(),
            ];
        };

        return $books;
    }

    public function getAuthorsBooks(string $term)
    {
        $qb = $this->createQueryBuilder('book');
        $qb->leftJoin('book.avtor', 'avt')
        ->andWhere('avt.name LIKE :searchTerm')
        ->setParameter('searchTerm', $term . '%');

        return $qb->getQuery()->getResult();
    }

    public function findByTitle(string $term)
    {
        $qb = $this->createQueryBuilder('book');
        $qb->andWhere('book.title LIKE :searchTerm')
            ->setParameter('searchTerm', $term . '%');

        $result =  $qb->getQuery()->getResult();

        return $result;
    }

    public function Convert($data){
        foreach ($data as $b) {
            $books[] = [
                'id' => $b->getId(),
                'PDF' => $b->getBook(),
                'title' => $b->getTitle(),
                'releaseYear' => $b->getReleaseYear(),
                'cover' => $b->getCover(),
                'description' => $b->getDescription(),
                'price' => $b->getPrice(),
                'views' => $b->getViews(),
                'isFree' => $b->isIsFree(),
                'validation' => $b->isValidation(),
                'isPublish' => $b->isIsPublish(),
                'category' => $b->getCategoriList(),
                'publishingHouse' => $b->getPublishingHouse(),
                'author' => $b->getAuthorList(),
            ];
        };
        return $books;
    }
}
