<?php

// App\Repository\ClassicalFilmRepository.php

namespace App\Repository;

use App\Entity\ClassicalFilm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClassicalFilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassicalFilm::class);
    }

    // Add this method to find films by category name
    public function findByCategoryName(string $categoryName)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.categories', 'cat') // Use 'categories' (plural) as defined in the entity
            ->andWhere('cat.name = :categoryName')
            ->setParameter('categoryName', $categoryName)
            ->getQuery()
            ->getResult();
    }
}
