<?php

namespace InteractiveValley\PakmailBundle\Repository;

/**
 * TiposFechaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TiposFechaRepository extends \Doctrine\ORM\EntityRepository
{
	public function getMaxPosicion()
    {
        $em=$this->getEntityManager();
       
        $query=$em->createQuery('
            SELECT MAX(e.position) as value 
            FROM PakmailBundle:TiposFecha e 
            ORDER BY e.position ASC
        ');
        
        $max=$query->getResult();
        return $max[0]['value'];
    }
}