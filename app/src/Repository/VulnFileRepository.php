<?php

namespace App\Repository;

use App\Entity\VulnFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VulnFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method VulnFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method VulnFile[]    findAll()
 * @method VulnFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VulnFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VulnFile::class);
    }

    public function save(VulnFile $vulnFile)
    {
        $this->getEntityManager()->persist($vulnFile);
        $this->getEntityManager()->flush();
    }

    public function findByStatus(int $status)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.status = :val')
            ->setParameter('val', $status)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return VulnFile[] Returns an array of VulnFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VulnFile
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
