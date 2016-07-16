<?php

namespace InteractiveValley\PakmailBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PromocionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PromocionRepository extends EntityRepository {

    public function getMaxPosicion() {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT MAX(p.position) as value 
            FROM PakmailBundle:Promocion p 
            ORDER BY p.position ASC
        ');

        $max = $query->getResult();
        return $max[0]['value'];
    }

    public function queryFindEnPrincipal() {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('p')
                ->from('InteractiveValley\PakmailBundle\Entity\Promocion', 'p')
                ->where('p.isPrincipal=:principal')
                ->setParameter('principal', true);
        return $query->getQuery();
    }

    public function findEnPrincipal() {
        $query = $this->queryFindEnPrincipal();
        return $query->getResult();
    }

    public function findActivas() {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('p')
                ->from('InteractiveValley\PakmailBundle\Entity\Promocion', 'p')
                ->where('p.inicio<=:inicio')
                ->andWhere('p.fin>=:fin')
                ->orderBy('p.position', 'ASC')
                ->setParameter('inicio', date('Y-m-d'))
                ->setParameter('fin', date('Y-m-d'))
                ;
        return $query->getQuery()->getResult();
    }
}
