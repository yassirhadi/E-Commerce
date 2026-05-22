<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function findAllOrderedByDate()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function search(string $term)
    {
        return $this->createQueryBuilder('c')
            ->where('c.title LIKE :term OR c.summary LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('c.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
