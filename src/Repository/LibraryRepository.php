<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Library>
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    /**
 * Find all libraries having an ID greater than or equal to the specified value.
 *
 * @return array[] Returns an array of arrays (i.e. a raw data set)
 */
    public function findLibrary(int $id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM library
            WHERE id = :id
        ';

        $resultSet = $conn->executeQuery($sql, ['id' => $id]);

        return $resultSet->fetchAssociative();
    }

    public function findOneByIsbn(string $isbn): ?Library
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.isbn = :isbn')
            ->setParameter('isbn', $isbn)
            ->getQuery()
            ->getOneOrNullResult();
    }


    //    /**
    //     * @return Library[] Returns an array of Library objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Library
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
