<?php

namespace InteractiveValley\PakmailBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InteractiveValley\PakmailBundle\Entity\Fecha;
use InteractiveValley\PakmailBundle\Form\FechaType;
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;

/**
 * Fecha controller.
 *
 * @Route("/backend/fechas")
 */
class FechaController extends Controller
{

    /**
     * Lists all Fecha entities.
     *
     * @Route("/", name="fechas")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PakmailBundle:Fecha')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new Fecha entity.
     *
     * @Route("/", name="fechas_create")
     * @Method("POST")
     * @Template("PakmailBundle:Fecha:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Fecha();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fechas_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Creates a form to create a Fecha entity.
     *
     * @param Fecha $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Fecha $entity)
    {
        $form = $this->createForm(new FechaType(), $entity, array(
            'action' => $this->generateUrl('fechas_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Fecha entity.
     *
     * @Route("/new", name="fechas_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Fecha();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores'     => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Finds and displays a Fecha entity.
     *
     * @Route("/{id}", name="fechas_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Fecha')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fecha entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Fecha entity.
     *
     * @Route("/{id}/edit", name="fechas_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Fecha')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fecha entity.');
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
    * Creates a form to edit a Fecha entity.
    *
    * @param Fecha $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Fecha $entity)
    {
        $form = $this->createForm(new FechaType(), $entity, array(
            'action' => $this->generateUrl('fechas_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Fecha entity.
     *
     * @Route("/{id}", name="fechas_update")
     * @Method("PUT")
     * @Template("PakmailBundle:Fecha:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Fecha')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fecha entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('fechas_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores'     => RpsStms::getErrorMessages($editForm),
        );
    }
    /**
     * Deletes a Fecha entity.
     *
     * @Route("/{id}", name="fechas_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PakmailBundle:Fecha')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Fecha entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('fechas'));
    }

    /**
     * Creates a form to delete a Fecha entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fechas_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
