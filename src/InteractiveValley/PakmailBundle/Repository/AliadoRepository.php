<?php

namespace InteractiveValley\PakmailBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AliadoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AliadoRepository extends EntityRepository
{
    public function getMaxPosicion()
    {
        $em=$this->getEntityManager();
       
        $query=$em->createQuery('
            SELECT MAX(p.position) as value 
            FROM PakmailBundle:Aliado p 
            ORDER BY p.position ASC
        ');
        
        $max=$query->getResult();
        return $max[0]['value'];
    }
    
    public function queryFindEnPrincipal()
    {
        $query= $this->getEntityManager()->createQueryBuilder();
        $query->select('p')
                ->from('InteractiveValley\PakmailBundle\Entity\Aliado', 'p')
                ->where('p.isPrincipal=:principal')
                ->setParameter('principal', true);
        return $query->getQuery();
    }
    
    public function findEnPrincipal()
    {
        $query= $this->queryFindEnPrincipal();
        return $query->getResult();
    }
}
