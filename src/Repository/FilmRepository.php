<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function save(Film $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Film $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Film[] Returns an array of Film objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Film
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function fetchFilms()
    {
        return $this->createQueryBuilder('f')
            ->getQuery()
            ->getResult();
    }

    public function fetchFilmByTitre($titre)
    {
        return $this->createQueryBuilder('f')
            ->Where("f.titre =:titre")
            ->setParameter('titre', $titre)
            ->getQuery()
            ->getResult();
    }

    public function fetchFilm($id)
    {
        return $this->createQueryBuilder('f')
            ->join('f.salle', 's')
            ->Where("f.id =:id")
            ->andWhere(" s.name = 'One' ")
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function fetchFilmByLettre()
    {
        $em = $this->getEntityManager();
        $req = $em->createQuery("select f from App\Entity\Film f where f.titre in (:value1,:value2) ")
            ->setParameters(['value1' => 'yargi', 'value2' => 'titanic']);
        return $req->getResult();
    }
}
