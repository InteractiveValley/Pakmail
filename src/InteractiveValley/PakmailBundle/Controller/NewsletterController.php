<?php

namespace InteractiveValley\PakmailBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InteractiveValley\PakmailBundle\Entity\Newsletter;
use InteractiveValley\PakmailBundle\Form\NewsletterType;
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;

/**
 * Newsletter controller.
 *
 * @Route("/backend/newsletters")
 */
class NewsletterController extends Controller
{

    /**
     * Lists all Newsletter entities.
     *
     * @Route("/", name="newsletters")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PakmailBundle:Newsletter')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new Newsletter entity.
     *
     * @Route("/", name="newsletters_create")
     * @Method("POST")
     * @Template("PakmailBundle:Newsletter:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Newsletter();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('newsletters_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Creates a form to create a Newsletter entity.
     *
     * @param Newsletter $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Newsletter $entity)
    {
        $form = $this->createForm(new NewsletterType(), $entity, array(
            'action' => $this->generateUrl('newsletters_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Newsletter entity.
     *
     * @Route("/new", name="newsletters_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Newsletter();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores'     => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Finds and displays a Newsletter entity.
     *
     * @Route("/{id}", name="newsletters_show",requirements= {"id":"\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Newsletter entity.
     *
     * @Route("/{id}/edit", name="newsletters_edit",requirements= {"id":"\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
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
    * Creates a form to edit a Newsletter entity.
    *
    * @param Newsletter $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Newsletter $entity)
    {
        $form = $this->createForm(new NewsletterType(), $entity, array(
            'action' => $this->generateUrl('newsletters_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Newsletter entity.
     *
     * @Route("/{id}", name="newsletters_update",requirements= {"id":"\d+"})
     * @Method("PUT")
     * @Template("PakmailBundle:Newsletter:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('newsletters_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores'     => RpsStms::getErrorMessages($editForm),
        );
    }
    /**
     * Deletes a Newsletter entity.
     *
     * @Route("/{id}", name="newsletters_delete",requirements= {"id":"\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PakmailBundle:Newsletter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Newsletter entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('newsletters'));
    }

    /**
     * Creates a form to delete a Newsletter entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('newsletters_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Exportar los registros.
     *
     * @Route("/exportar", name="newsletters_exportar")
     */
    public function exportarAction(Request $request)
    {
        $registros = $this->getDoctrine()->getRepository('PakmailBundle:Newsletter')->findAll();

        $response = $this->render(
                'PakmailBundle:Newsletter:list.xls.twig', array('entities' => $registros)
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export-newsletters.xls"');
        return $response;
    }
}
