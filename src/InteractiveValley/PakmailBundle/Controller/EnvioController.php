<?php

namespace InteractiveValley\PakmailBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InteractiveValley\PakmailBundle\Entity\Envio;
use InteractiveValley\PakmailBundle\Form\EnvioType;
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;
use InteractiveValley\PakmailBundle\Entity\DireccionFiscal;
use InteractiveValley\PakmailBundle\Entity\DireccionRemision;
use InteractiveValley\PakmailBundle\Entity\DireccionDestino;

/**
 * Envio controller.
 *
 * @Route("/backend/envios")
 */
class EnvioController extends Controller
{

    /**
     * Lists all Envio entities.
     *
     * @Route("/", name="envios")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PakmailBundle:Envio')
                       ->findByCreatedAt();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new Envio entity.
     *
     * @Route("/", name="envios_create")
     * @Method("POST")
     * @Template("PakmailBundle:Envio:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Envio();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $ruta = $this->generateUrl('envios_show', array('id' => $entity->getId()));
            $return = $this->get('session')->get('return','');
            if(strlen($return)>0){
                $session = $this->get('session');
                $session->set('return','');
                return $this->redirect($return);
            }else{
                return $this->redirect($ruta);
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Creates a form to create a Envio entity.
     *
     * @param Envio $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Envio $entity)
    {
        $form = $this->createForm(new EnvioType(), $entity, array(
            'action' => $this->generateUrl('envios_create'),
            'method' => 'POST',
            'em'=>$this->getDoctrine()->getManager(),
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Envio entity.
     *
     * @Route("/new", name="envios_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new Envio();
        if($request->query->has('cliente')){
            $cliente = $this->getDoctrine()->getRepository('PakmailBundle:Cliente')
                            ->find($request->query->get('cliente'));
            $entity->setCliente($cliente);
            $this->get('session')->set('return',$request->query->get('return'));
        }
        $direccionFiscal = new DireccionFiscal();
        $direccionRemision = new DireccionRemision();
        $direccionDestino = new DireccionDestino();
        
        $entity->setDireccionFiscal($direccionFiscal);
        $entity->setDireccionRemitente($direccionRemision);
        $entity->setDireccionDestino($direccionDestino);
        
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores'     => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Finds and displays a Envio entity.
     *
     * @Route("/{id}", name="envios_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Envio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Envio entity.');
        }
        
        if ($request->isXmlHttpRequest()) {
            return $this->render('PakmailBundle:Envio:envio.html.twig', array(
                        'entity' => $entity,
            ));
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Envio entity.
     *
     * @Route("/{id}/edit", name="envios_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Envio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Envio entity.');
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
    * Creates a form to edit a Envio entity.
    *
    * @param Envio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Envio $entity)
    {
        $form = $this->createForm(new EnvioType(), $entity, array(
            'action' => $this->generateUrl('envios_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'em'=>$this->getDoctrine()->getManager(),
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Envio entity.
     *
     * @Route("/{id}", name="envios_update")
     * @Method("PUT")
     * @Template("PakmailBundle:Envio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Envio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Envio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('envios_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores'     => RpsStms::getErrorMessages($editForm),
        );
    }
    /**
     * Deletes a Envio entity.
     *
     * @Route("/{id}", name="envios_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PakmailBundle:Envio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Envio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('envios'));
    }

    /**
     * Creates a form to delete a Envio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('envios_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Aceptar solicitud de envio.
     *
     * @Route("/{id}/aceptar", name="envios_aceptar", requirements={"id" = "\d+"})
     * @Method("PATCH")
     */
    public function aceptarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('PakmailBundle:Envio')->find($id);

        if (!$envio) {
            throw $this->createNotFoundException('Unable to find envio entity.');
        }
        $envio->setStatus(Envio::STATUS_ACEPTADA);
        $em->flush();

        return $this->render("PakmailBundle:Envio:item.html.twig", array(
                    'entity' => $envio
        ));
    }
    
    /**
     * Rechazar la solicitud de envio.
     *
     * @Route("/{id}/rechazar", name="envios_rechazar", requirements={"id" = "\d+"})
     * @Method("PATCH")
     */
    public function revisarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('PakmailBundle:Envio')->find($id);

        if (!$envio) {
            throw $this->createNotFoundException('Unable to find envio entity.');
        }
        $envio->setStatus(Envio::STATUS_RECHAZADA);
        $em->flush();

        return $this->render("PakmailBundle:Envio:item.html.twig", array(
                    'entity' => $envio
        ));
    }
}
