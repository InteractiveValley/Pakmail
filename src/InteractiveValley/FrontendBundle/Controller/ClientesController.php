<?php

namespace InteractiveValley\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use InteractiveValley\BackendBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use InteractiveValley\PakmailBundle\Entity\Envio;
use InteractiveValley\PakmailBundle\Form\Frontend\EnvioFrontendType;
use InteractiveValley\PakmailBundle\Entity\Perfil;
use InteractiveValley\PakmailBundle\Form\Frontend\PerfilFrontendType;
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;

/**
 * Clientes aplicacion Pakmail controller.
 *
 * @Route("/pakmail")
 */
class ClientesController extends BaseController {

    /**
     * @Route("/envios", name="pakmail_envios")
     * @Method("GET")
     * @Template("FrontendBundle:Clientes:index.html.twig")
     */
    public function indexAction(Request $request) {

        return $this->redirect($this->generateUrl('pakmail_envios_new'));
    }

    /**
     * Creates a new Envio entity.
     *
     * @Route("/envios", name="pakmail_envios_create")
     * @Method("POST")
     * @Template("FrontendBundle:Clientes:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Envio();
        $form = $this->createCreateFormEnvio($entity,$request);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if (!$entity->getHasPerfil()) {
                $this->get('session')->set('envio_creado', $entity->getId());
                $this->get('session')->set('perfil_guardado', 0);
            } else {
                $this->get('session')->set('envio_creado', $entity->getId());
                $this->get('session')->set('perfil_guardado', 1);
            }
            $this->enviarSolicitudEnvioCreado($this->getUser(), $entity);
            $ruta = $this->generateUrl('pakmail_envios_new');
            return $this->redirect($ruta);
        }else{
			$creacionEnvio = 0;
			$perfilGuardado = (!$entity->getHasPerfil())?0:1;
		}

        $cliente = $this->getUser();
        if (!isset($em)) {
            $em = $this->getDoctrine()->getManager();
        }
        $perfiles = $em->getRepository('PakmailBundle:Perfil')
                ->findBy(array('cliente' => $cliente), array('nombre' => 'ASC'));

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'perfiles' => $perfiles,
            'creacionEnvio' => $creacionEnvio,
            'perfilGuardado' => $perfilGuardado,
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
    private function createCreateFormEnvio(Envio $entity, $request=null) {
        $em = $this->getDoctrine()->getManager();
        
		$paisesDestino = $em->getRepository('PakmailBundle:DireccionDestino')->getArrayUserPaises($this->getUser());
        $paisesFiscal = $em->getRepository('PakmailBundle:DireccionFiscal')->getArrayUserPaises($this->getUser());
        $paisesRemision = $em->getRepository('PakmailBundle:DireccionRemision')->getArrayUserPaises($this->getUser());
		
		if(!$request==null){
			$datos = $request->request->get('interactivevalley_pakmailbundle_envio');
			$paisDestino = $datos['direccionDestino']['pais'];
			$paisFiscal = $datos['direccionFiscal']['pais'];
			$paisRemitente = $datos['direccionRemitente']['pais'];
			
			if(!array_key_exists($paisDestino, $paisesDestino)){
				$paisesDestino = array_merge($paisesDestino, array("$paisDestino"=>"$paisDestino"));
			}

			if(!array_key_exists($paisFiscal, $paisesFiscal)){
				$paisesFiscal = array_merge($paisesFiscal, array("$paisFiscal"=>"$paisFiscal"));
			}

			if(!array_key_exists($paisRemitente, $paisesRemision)){
				$paisesRemision = array_merge($paisesRemision, array("$paisRemitente"=>"$paisRemitente"));
			}
		}
		
        $form = $this->createForm(new EnvioFrontendType(), $entity, array(
            'action' => $this->generateUrl('pakmail_envios_create'),
            'method' => 'POST',
            'em' => $em,
            'paisesDestino' => $paisesDestino,
            'paisesFiscal' => $paisesFiscal,
            'paisesRemision' => $paisesRemision
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Envio entity.
     *
     * @Route("/envio/nuevo", name="pakmail_envios_new")
     * @Method("GET")
     * @Template("FrontendBundle:Clientes:new.html.twig")
     */
    public function newAction(Request $request) {
        $entity = new Envio();
        $cliente = $this->getUser();
        $entity->setCliente($cliente);
        $form = $this->createCreateFormEnvio($entity);
        $em = $this->getDoctrine()->getManager();
        $perfiles = $em->getRepository('PakmailBundle:Perfil')
                       ->findBy(array('cliente' => $cliente), array('nombre' => 'ASC'));
        $session = $this->get('session');

        if ($session->has('envio_creado') && strlen($session->get('envio_creado')) > 0) {
            $creacionEnvio = $session->get('envio_creado');
            $session->set('envio_creado', '0');
        } else {
            $creacionEnvio = '0';
        }

        if ($session->has('perfil_guardado')) {
            $perfilGuardado = $session->get('perfil_guardado');
            $session->set('perfil_guardado', '0');
        } else {
            $perfilGuardado = '0';
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'perfiles' => $perfiles,
            'creacionEnvio' => $creacionEnvio,
            'perfilGuardado' => $perfilGuardado,
            'errores' => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Displays a form to create a new Envio entity.
     *
     * @Route("/envio/copia/perfil/{id}", name="pakmail_perfiles_copia", requirements={"id" = "\d+"})
     * @Method("GET")
     * @Template("FrontendBundle:Clientes:new.html.twig")
     */
    public function perfilCopiaAction(Request $request, $id) {
        $entity = new Envio();
        $cliente = $this->getUser();
        $entity->setCliente($cliente);
        $em = $this->getDoctrine()->getManager();
        $perfil = $em->getRepository('PakmailBundle:Perfil')
                     ->find($id);

        $entity = $this->copiaPerfilAEnvio($perfil, $entity);

        $perfiles = $em->getRepository('PakmailBundle:Perfil')
                       ->findBy(array('cliente' => $cliente), array('nombre' => 'ASC'));

        $form = $this->createCreateFormEnvio($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'perfiles' => $perfiles,
            'creacionEnvio' => '0',
            'perfilGuardado' => '1',
            'errores' => RpsStms::getErrorMessages($form),
        );
    }

    public function copiaPerfilAEnvio($perfil, Envio $envio) {
        
        $envio->setDireccionFiscalToModel($perfil->getDireccionFiscal());
        $envio->setDireccionRemitenteToModel($perfil->getDireccionRemitente());
        $envio->setDireccionDestinoToModel($perfil->getDireccionDestino());
        
        $envio->setReferencia($perfil->getReferencia());
        $envio->setTipo($perfil->getTipo());
        $envio->setPrecio($perfil->getPrecio());
        $envio->setNumGuia($perfil->getNumGuia());
        $envio->setFolio($perfil->getFolio());
        $envio->setAsegurarEnvio($perfil->getAsegurarEnvio());
        $envio->setMontoSeguro($perfil->getMontoSeguro());
        $envio->setImporteSeguro($perfil->getImporteSeguro());
        $envio->setObservaciones($perfil->getObservaciones());
        $envio->setPerfil($perfil->getId());

        $envio->setMedidaPeso($perfil->getMedidaPeso());
        $envio->setMedidaLargo($perfil->getMedidaLargo());
        $envio->setMedidaAncho($perfil->getMedidaAncho());
        $envio->setMedidaAlto($perfil->getMedidaAlto());
        $envio->setGenerarGastosAduana($perfil->getGenerarGastosAduana());
        $envio->setValorDeclarado($perfil->getValorDeclarado());

        $envio->setHasPerfil(true);
        return $envio;
    }

    /**
     * @Route("/perfiles", name="pakmail_perfiles")
     * @Method("GET")
     * @Template("FrontendBundle:Perfiles:index.html.twig")
     */
    public function perfilesAction(Request $request) {

        $cliente = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $perfiles = $em->getRepository('PakmailBundle:Perfil')
                ->findBy(array('cliente' => $cliente), array('nombre' => 'ASC'));
        return array(
            'perfiles' => $perfiles
        );
    }

    /**
     * Displays a form to edit an existing Perfil entity.
     *
     * @Route("/{id}/perfil/editar", name="pakmail_perfiles_edit")
     * @Method("GET")
     * @Template("FrontendBundle:Perfiles:edit.html.twig")
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Perfil entity.');
        }

        $editForm = $this->createEditFormPerfil($entity);
        $deleteForm = $this->createDeleteFormPerfil($id);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores' => RpsStms::getErrorMessages($editForm),
        );
    }

    /**
     * Creates a form to edit a Perfil entity.
     *
     * @param Perfil $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditFormPerfil(Perfil $entity, $request=null) {
        $em = $this->getDoctrine()->getManager();
        $paisesDestino = $em->getRepository('PakmailBundle:DireccionDestino')->getArrayUserPaises($this->getUser());
        $paisesFiscal = $em->getRepository('PakmailBundle:DireccionFiscal')->getArrayUserPaises($this->getUser());
        $paisesRemision = $em->getRepository('PakmailBundle:DireccionRemision')->getArrayUserPaises($this->getUser());
		
		if(!$request==null){
			$datos = $request->request->get('interactivevalley_pakmailbundle_perfil');
			$paisDestino = $datos['direccionDestino']['pais'];
			$paisFiscal = $datos['direccionFiscal']['pais'];
			$paisRemitente = $datos['direccionRemitente']['pais'];
			
			if(!array_key_exists($paisDestino, $paisesDestino)){
				$paisesDestino = array_merge($paisesDestino, array("$paisDestino"=>"$paisDestino"));
			}

			if(!array_key_exists($paisFiscal, $paisesFiscal)){
				$paisesFiscal = array_merge($paisesFiscal, array("$paisFiscal"=>"$paisFiscal"));
			}

			if(!array_key_exists($paisRemitente, $paisesRemision)){
				$paisesRemision = array_merge($paisesRemision, array("$paisRemitente"=>"$paisRemitente"));
			}
		}
		
        $form = $this->createForm(new PerfilFrontendType(), $entity, array(
            'action' => $this->generateUrl('pakmail_perfiles_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'em' => $em,
            'paisesDestino' => $paisesDestino,
            'paisesFiscal' => $paisesFiscal,
            'paisesRemision' => $paisesRemision
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Perfil entity.
     *
     * @Route("/{id}/perfil/update", name="pakmail_perfiles_update")
     * @Method("PUT")
     * @Template("FrontendBundle:Perfiles:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Perfil entity.');
        }

        $deleteForm = $this->createDeleteFormPerfil($id);
        $editForm = $this->createEditFormPerfil($entity, $request);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('pakmail_perfiles', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores' => RpsStms::getErrorMessages($editForm),
        );
    }

    /**
     * Deletes a Perfil entity.
     *
     * @Route("/{id}/perfil/borrar", name="pakmail_perfiles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        if ($request->isMethod('DELETE')) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PakmailBundle:Perfil')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Perfil entity.');
            }

            //$em->remove($entity);
            $entity->setIsActive(false);
            $em->flush();

            return new JsonResponse(array('borrado' => 'ok'));
        } else {
            return new JsonResponse(array('borrado' => 'not'));
        }

        return $this->redirect($this->generateUrl('perfiles_envio'));
    }

    /**
     * Crear un formulario para eliminar un perfil.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteFormPerfil($id) {
        return $this->createFormBuilder()
                    ->setAction($this->generateUrl('pakmail_perfiles_delete', array('id' => $id)))
                    ->setMethod('DELETE')
                    //->add('submit', 'submit', array('label' => 'Delete'))
                    ->getForm()
        ;
    }

    /**
     * Creates a new Perfil entity.
     *
     * @Route("/perfiles", name="pakmail_perfiles_create")
     * @Method("POST")
     */
    public function createPerfilAction(Request $request) {
        $entity = new Perfil();
        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('PakmailBundle:Envio')
                ->find($request->request->get('envio'));
        if ($envio) {
            $entity->setCliente($this->getUser());
            $entity->setNombre($request->request->get('nombre'));
            $entity->setIsActive(true);
            $entity = $this->copiaEnvioAPerfil($envio, $entity);

            $em->persist($entity);
            $em->flush();

            return new JsonResponse(array(
                'status' => 'ok'
            ));
        } else {
            return new JsonResponse(array(
                'status' => 'bat'
            ));
        }
    }

    public function copiaEnvioAPerfil($envio, Perfil $perfil) {
        $perfil->setDireccionFiscalToModel($envio->getDireccionFiscal());
        $perfil->setDireccionRemitenteToModel($envio->getDireccionRemitente());
        $perfil->setDireccionDestinoToModel($envio->getDireccionDestino());
        $perfil->setReferencia($envio->getReferencia());
        $perfil->setTipo($envio->getTipo());
        $perfil->setPrecio($envio->getPrecio());
        $perfil->setNumGuia($envio->getNumGuia());
        $perfil->setFolio($envio->getFolio());
        $perfil->setAsegurarEnvio($envio->getAsegurarEnvio());
        $perfil->setMontoSeguro($envio->getMontoSeguro());
        $perfil->setImporteSeguro($envio->getImporteSeguro());
        $perfil->setObservaciones($envio->getObservaciones());

        $perfil->setMedidaPeso($envio->getMedidaPeso());
        $perfil->setMedidaLargo($envio->getMedidaLargo());
        $perfil->setMedidaAncho($envio->getMedidaAncho());
        $perfil->setMedidaAlto($envio->getMedidaAlto());
        $perfil->setGenerarGastosAduana($envio->getGenerarGastosAduana());
        $perfil->setValorDeclarado($envio->getValorDeclarado());

        return $perfil;
    }

    /**
     * @Route("/registro",name="pakmail_registro")
     * @Template()
     * @Method({"GET","POST"})
     */
    public function registroAction(Request $request) {
        $usuario = new Usuario();
        $form = $this->createForm(new UsuarioType(), $usuario);
        $isNew = true;
        if ($request->isMethod('POST')) {
            $parametros = $request->request->all();
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $this->setSecurePassword($usuario);
                $em->persist($usuario);
                $em->flush();

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(array('status' => true));
                }

                return $this->redirect($this->generateUrl('login'));
            }
        }

        return array(
            'form' => $form->createView(),
            'titulo' => 'Registro',
            'usuario' => $usuario,
            'isNew' => true,
        );
    }

    /**
     * @Route("/perfil",name="pakmail_perfil_usuario")
     * @Template("FrontendBundle:Default:registro.html.twig")
     * @Method({"GET","POST"})
     */
    public function perfilUsuarioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        if (!$usuario) {
            return $this->redirect($this->generateUrl('login'));
        }
        $form = $this->createForm(new UsuarioFrontendType(), $usuario, array(
            'em' => $this->getDoctrine()->getManager())
        );
        $isNew = false;
        if ($request->isMethod('POST')) {
            //obtiene la contraseña
            $current_pass = $usuario->getPassword();
            $form->handleRequest($request);
            if ($form->isValid()) {
                if (null == $usuario->getPassword()) {
                    // usuario no cambio contraseña
                    $usuario->setPassword($current_pass);
                } else {
                    // se actualiza la contraseña
                    $this->setSecurePassword($usuario);
                }
                $em->flush();
                $this->enviarUsuarioUpdate($usuario->getEmail(), $current_pass, $usuario);
                $this->get('session')->getFlashBag()->add(
                        'notice', 'Se han realizado los cambios solicitados.'
                );
            }
        }

        return array(
            'form' => $form->createView(),
            'usuario' => $usuario,
            'titulo' => 'Editar perfil',
            'isNew' => $isNew,
        );
    }

}
