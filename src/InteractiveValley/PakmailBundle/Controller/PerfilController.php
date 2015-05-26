<?php

namespace InteractiveValley\PakmailBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InteractiveValley\PakmailBundle\Entity\Perfil;
use InteractiveValley\PakmailBundle\Form\PerfilType;
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;

/**
 * Perfil controller.
 *
 * @Route("/perfiles/envio")
 */
class PerfilController extends Controller
{

    /**
     * Lists all Perfil entities.
     *
     * @Route("/", name="perfiles_envio")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PakmailBundle:Perfil')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new Perfil entity.
     *
     * @Route("/", name="perfiles_envio_create")
     * @Method("POST")
     * @Template("PakmailBundle:Perfil:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Perfil();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('perfiles_envio_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Creates a form to create a Perfil entity.
     *
     * @param Perfil $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Perfil $entity)
    {
        $form = $this->createForm(new PerfilType(), $entity, array(
            'action' => $this->generateUrl('perfiles_envio_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Perfil entity.
     *
     * @Route("/new", name="perfiles_envio_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Perfil();
        $max = $this->getDoctrine()->getRepository('PakmailBundle:Perfil')
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
     * Finds and displays a Perfil entity.
     *
     * @Route("/{id}", name="perfiles_envio_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Perfil entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Perfil entity.
     *
     * @Route("/{id}/edit", name="perfiles_envio_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Perfil entity.');
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
    * Creates a form to edit a Perfil entity.
    *
    * @param Perfil $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Perfil $entity)
    {
        $form = $this->createForm(new PerfilType(), $entity, array(
            'action' => $this->generateUrl('perfiles_envio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Perfil entity.
     *
     * @Route("/{id}", name="perfiles_envio_update")
     * @Method("PUT")
     * @Template("PakmailBundle:Perfil:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Perfil entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('perfiles_envio_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores'     => RpsStms::getErrorMessages($editForm),
        );
    }
    /**
     * Deletes a Perfil entity.
     *
     * @Route("/{id}", name="perfiles_envio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PakmailBundle:Perfil')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Perfil entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('perfiles_envio'));
    }

    /**
     * Creates a form to delete a Perfil entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('perfiles_envio_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
