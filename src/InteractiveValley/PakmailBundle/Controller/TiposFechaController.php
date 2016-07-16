<?php

namespace InteractiveValley\PakmailBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InteractiveValley\PakmailBundle\Entity\TiposFecha;
use InteractiveValley\PakmailBundle\Form\TiposFechaType;
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;
/**
 * TiposFecha controller.
 *
 * @Route("/backend/tipos-fecha")
 */
class TiposFechaController extends Controller
{

    /**
     * Lists all TiposFecha entities.
     *
     * @Route("/", name="tiposfecha")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PakmailBundle:TiposFecha')
                       ->findBy(array(),array('position'=>'ASC'));

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new TiposFecha entity.
     *
     * @Route("/", name="tiposfecha_create")
     * @Method("POST")
     * @Template("PakmailBundle:TiposFecha:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TiposFecha();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tiposfecha_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Creates a form to create a TiposFecha entity.
     *
     * @param TiposFecha $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TiposFecha $entity)
    {
        $form = $this->createForm(new TiposFechaType(), $entity, array(
            'action' => $this->generateUrl('tiposfecha_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TiposFecha entity.
     *
     * @Route("/new", name="tiposfecha_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TiposFecha();
        $max = $this->getDoctrine()->getRepository('PakmailBundle:TiposFecha')
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
     * Finds and displays a TiposFecha entity.
     *
     * @Route("/{id}", name="tiposfecha_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:TiposFecha')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TiposFecha entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TiposFecha entity.
     *
     * @Route("/{id}/edit", name="tiposfecha_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:TiposFecha')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TiposFecha entity.');
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
    * Creates a form to edit a TiposFecha entity.
    *
    * @param TiposFecha $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TiposFecha $entity)
    {
        $form = $this->createForm(new TiposFechaType(), $entity, array(
            'action' => $this->generateUrl('tiposfecha_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TiposFecha entity.
     *
     * @Route("/{id}", name="tiposfecha_update")
     * @Method("PUT")
     * @Template("PakmailBundle:TiposFecha:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:TiposFecha')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TiposFecha entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tiposfecha_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores'     => RpsStms::getErrorMessages($editForm),
        );
    }
    /**
     * Deletes a TiposFecha entity.
     *
     * @Route("/{id}", name="tiposfecha_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PakmailBundle:TiposFecha')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TiposFecha entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('fechas'));
    }

    /**
     * Creates a form to delete a TiposFecha entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tiposfecha_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Ordenar las posiciones de los tipos de fecha.
     *
     * @Route("/ordenar/registros", name="tiposfecha_ordenar")
     * @Method("PATCH")
     */
    public function ordenarRegistrosAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $registro_order = $request->query->get('registro');
            $em = $this->getDoctrine()->getManager();
            $result['ok'] = true;
            foreach ($registro_order as $order => $id) {
                $registro = $em->getRepository('PakmailBundle:TiposFecha')->find($id);
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
