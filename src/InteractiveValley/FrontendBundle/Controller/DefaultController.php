<?php

namespace InteractiveValley\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use InteractiveValley\BackendBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use InteractiveValley\FrontendBundle\Entity\Contacto;
use InteractiveValley\FrontendBundle\Form\ContactoType;
use InteractiveValley\BackendBundle\Entity\Usuario;
use InteractiveValley\BackendBundle\Form\Frontend\UsuarioType;
use InteractiveValley\VentasBundle\Entity\Venta;
use InteractiveValley\VentasBundle\Entity\DetVenta;

class DefaultController extends BaseController {

    /**
     * @Route("/inicio", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request) {
        return array();
    }

    /**
     * @Route("/recuperar",name="recuperar")
     * @Template()
     * @Method({"GET","POST"})
     */
    public function recuperarAction(Request $request) {
        $sPassword = "";
        $sUsuario = "";
        $msg = "";
        if ($request->isMethod('POST')) {
            $email = $request->get('email');
            $usuario = $this->getDoctrine()->getRepository('BackendBundle:Usuario')
                    ->findOneBy(array('email' => $email));
            if (!$usuario) {
                $this->get('session')->getFlashBag()->add(
                        'error', 'El email no esta registrado.'
                );
                return $this->redirect($this->generateUrl('recuperar'));
            } else {
                $sPassword = substr(md5(time()), 0, 7);
                $sUsuario = $usuario->getUsername();
                $encoder = $this->get('security.encoder_factory')
                        ->getEncoder($usuario);
                $passwordCodificado = $encoder->encodePassword(
                        $sPassword, $usuario->getSalt()
                );
                $usuario->setPassword($passwordCodificado);
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add(
                        'notice', 'Se ha enviado un correo con la nueva contraseña.'
                );

                $this->enviarRecuperar($sUsuario, $sPassword, $usuario);
                return $this->redirect($this->generateUrl('login'));
            }
        }
        return array('msg' => $msg);
    }

    /**
     * @Route("/registro",name="registro")
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
     * @Route("/perfil",name="perfil_usuario")
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
    
    
    /**
     * @Route("/contacto", name="frontend_contacto")
     * @Method({"GET", "POST"})
     */
    public function contactoAction(Request $request) {
        $contacto = new Contacto();
        $form = $this->createForm(new ContactoType(), $contacto);
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $datos = $form->getData();
                $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                        ->findOneBy(array('slug' => 'email-contacto'));
                $message = \Swift_Message::newInstance()
                        ->setSubject('Contacto desde pagina')
                        ->setFrom($datos->getEmail())
                        ->setTo($configuracion->getTexto())
                        ->setBody($this->renderView('FrontendBundle:Default:contactoEmail.html.twig', array('datos' => $datos)), 'text/html');
                $this->get('mailer')->send($message);
                // Redirige - Esto es importante para prevenir que el usuario
                // reenvíe el formulario si actualiza la página
                $status = 'send';
                $mensaje = "Se ha enviado el mensaje";
                $contacto = new Contacto();
                $form = $this->createForm(new ContactoType(), $contacto);
            } else {
                $status = 'notsend';
                $mensaje = "El mensaje no se ha podido enviar";
            }
        } else {
            $status = 'new';
            $mensaje = "";
        }

        if ($request->isXmlHttpRequest()) {
            $vista = $this->renderView('FrontendBundle:Default:formContacto.html.twig', array(
                'form' => $form->createView(),
                'status' => $status,
                'mensaje' => $mensaje,
            ));
            return new JsonResponse(array(
                'form' => $vista,
                'status' => $status,
                'mensaje' => $mensaje,
            ));
        }

        return $this->render('FrontendBundle:Default:formContacto.html.twig', array(
                    'form' => $form->createView(),
                    'status' => $status,
                    'mensaje' => $mensaje
        ));
    }

    private function enviarRecuperar($sUsuario, $sPassword, Usuario $usuario, $isNew = false) {
        $asunto = 'Se ha reestablecido su contraseña';
        $message = \Swift_Message::newInstance()
                ->setSubject($asunto)
                ->setFrom($this->container->get('richpolis.emails.to_email'))
                ->setTo($usuario->getEmail())
                ->setBody(
                $this->renderView('FrontendBundle:Default:enviarCorreo.html.twig', compact('usuario', 'sUsuario', 'sPassword', 'isNew', 'asunto')), 'text/html'
        );
        $this->get('mailer')->send($message);
    }
}
