<?php

namespace InteractiveValley\PakmailBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InteractiveValley\PakmailBundle\Entity\Promocion;
use InteractiveValley\PakmailBundle\Form\PromocionType;
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;

/**
 * Promocion controller.
 *
 * @Route("/promociones")
 */
class PromocionController extends Controller
{

    /**
     * Lists all Promocion entities.
     *
     * @Route("/", name="promociones")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PakmailBundle:Promocion')
                        ->findBy(array(),array('position'=>'ASC'));

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new Promocion entity.
     *
     * @Route("/", name="promociones_create")
     * @Method("POST")
     * @Template("PakmailBundle:Promocion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Promocion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('promociones_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Creates a form to create a Promocion entity.
     *
     * @param Promocion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Promocion $entity)
    {
        $form = $this->createForm(new PromocionType(), $entity, array(
            'action' => $this->generateUrl('promociones_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Promocion entity.
     *
     * @Route("/new", name="promociones_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Promocion();
        $max = $this->getDoctrine()->getRepository('PakmailBundle:Promocion')
                    ->getMaxPosicion();
        if (!is_null($max)) {
            $entity->setPosition($max + 1);
        } else {
            $entity->setPosition(1);
        }
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores'     => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Finds and displays a Promocion entity.
     *
     * @Route("/{id}", name="promociones_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Promocion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Promocion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Promocion entity.
     *
     * @Route("/{id}/edit", name="promociones_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Promocion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Promocion entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'        => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores'     => RpsStms::getErrorMessages($editForm),
        );
    }

    /**
    * Creates a form to edit a Promocion entity.
    *
    * @param Promocion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Promocion $entity)
    {
        $form = $this->createForm(new PromocionType(), $entity, array(
            'action' => $this->generateUrl('promociones_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Promocion entity.
     *
     * @Route("/{id}", name="promociones_update")
     * @Method("PUT")
     * @Template("PakmailBundle:Promocion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Promocion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Promocion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('promociones_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores'     => RpsStms::getErrorMessages($editForm),
        );
    }
    /**
     * Deletes a Promocion entity.
     *
     * @Route("/{id}", name="promociones_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PakmailBundle:Promocion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Promocion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('promociones'));
    }

    /**
     * Creates a form to delete a Promocion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('promociones_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Ordenar las posiciones de las promociones.
     *
     * @Route("/ordenar/registros", name="promociones_ordenar")
     * @Method("PATCH")
     */
    public function ordenarRegistrosAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $registro_order = $request->query->get('registro');
            $em = $this->getDoctrine()->getManager();
            $result['ok'] = true;
            foreach ($registro_order as $order => $id) {
                $registro = $em->getRepository('PakmailBundle:Promocion')->find($id);
                if ($registro->getPosition() != ($order + 1)) {
                    try {
                        $registro->setPosition($order + 1);
                        $em->flush();
                    } catch (Exception $e) {
                        $result['mensaje'] = $e->getMessage();
                        $result['ok'] = false;
                    }
                }
            }
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData($result);
            return $response;
        } else {
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData(array('ok' => false));
            return $response;
        }
    }
}
