<?php

namespace App\Repository;

use App\Entity\AdminTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdminTable>
 *
 * @method AdminTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminTable[]    findAll()
 * @method AdminTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminTable::class);
    }

    public function add(AdminTable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AdminTable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findById(int $id): AdminTable{
        return $this->getEntityManager()->find(AdminTable::class, $id);
    }
//    /**
//     * @return AdminTable[] Returns an array of AdminTable objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AdminTable
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
