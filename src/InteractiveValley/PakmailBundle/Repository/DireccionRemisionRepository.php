<?php

namespace InteractiveValley\PakmailBundle\Repository;

/**
 * DireccionRemisionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DireccionRemisionRepository extends \Doctrine\ORM\EntityRepository {

    public function getPaises() {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT DISTINCT e.pais as value    
            FROM PakmailBundle:DireccionRemision e
        ');

        $paises = $query->getResult();
        return $paises;
    }

    public function getArrayPaises() {
        $paises = $this->getPaises();
        $arreglo = array();
        foreach ($paises as $pais) {
            $arreglo[] = $pais['value'];
        }
        $convinado = array_combine($arreglo, $arreglo);
        return $convinado;
    }
    
    public function getUserPaises($user) {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT DISTINCT d.pais as value     
            FROM PakmailBundle:Envio e 
            JOIN e.direccionRemitente d 
            WHERE e.cliente=:usuario
        ')->setParameter('usuario', $user->getId());

        $paises = $query->getResult();
        return $paises;
    }

    public function getArrayUserPaises($user) {
        $paises = $this->getUserPaises($user);
        $arreglo = array();
        foreach ($paises as $pais) {
            $arreglo[] = $pais['value'];
        }
        $convinado = array_combine($arreglo, $arreglo);
        return $convinado;
    }
    
}
