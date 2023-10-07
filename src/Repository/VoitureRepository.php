<?php

namespace App\Repository;

use App\Entity\Voiture;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Voiture>
 *
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    public function save(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function paginationQuery()
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.id', 'ASC')
            ->getQuery();
    }


        /**
     * Get published posts thanks to Search data value 
     * 
     * @param SearchData $searchData
     * @return PaginationInterface
     */
    public function findbySearch(SearchData $searchData){
    
        $data = $this->createQueryBuilder('p')
            ->addOrderBy('p.createdAt', 'DESC');;

        if (!empty($searchData->q)) {
            $data = $data
                ->andWhere('p.nom LIKE :q')
                ->orWhere('p.couleur LIKE :q')
                ->orWhere('p.annee LIKE :q')
                ->orWhere('p.marque LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }
     
        $data = $data
            ->getQuery();
            // ->getResult();
            return $data ;  
         
    }
        
}

    //    public function findOneBySomeField($value): ?Voiture
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }





