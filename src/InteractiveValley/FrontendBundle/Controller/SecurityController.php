<?php

namespace InteractiveValley\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use InteractiveValley\BackendBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\SecurityContext;


/**
 * Seguridad de aplicacion Pakmail controller.
 *
 * @Route("/pakmail")
 */
class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="pakmail_login")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        // obtiene el error de inicio de sesión si lo hay
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('FrontendBundle:Security:login.html.twig',array(
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'ruta'          => 'pakmail_login_check',
            )
        );
    }
    
    /**
     * @Route("/login_check", name="pakmail_login_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="pakmail_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
        
    }
    
    /**
     * @Route("/recuperar",name="pakmail_recuperar")
     * @Template()
     * @Method({"GET","POST"})
     */
    public function recuperarAction(Request $request) {
        $sPassword = "";
        $sUsuario = "";
        $msg = "";
        if ($request->isMethod('POST')) {
            $email = $request->get('email');
            $cliente = $this->getDoctrine()->getRepository('PakmailBundle:Cliente')
                    ->findOneBy(array('email' => $email));

            if (!$cliente) {
                $this->get('session')->getFlashBag()->add(
                        'error', 'El email no esta registrado.'
                );
                return $this->redirect($this->generateUrl('pakmail_recuperar'));
            } else {
                $sPassword = substr(md5(time()), 0, 7);
                $sUsuario = $cliente->getUsername();
                $encoder = $this->get('security.encoder_factory')
                        ->getEncoder($cliente);
                $passwordCodificado = $encoder->encodePassword(
                        $sPassword, $cliente->getSalt()
                );
                $cliente->setPassword($passwordCodificado);
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add(
                        'notice', 'Se ha enviado un correo con la nueva contraseña.'
                );

                $this->enviarRecuperar($sUsuario, $sPassword, $cliente);
                return $this->redirect($this->generateUrl('pakmail_login'));
            }
        }
        return array('msg' => $msg);
    }
    
        
    
    private function enviarRecuperar($sUsuario, $sPassword, $usuario, $isNew = false) {
        $asunto = 'Se ha reestablecido su contraseña';
        $message = \Swift_Message::newInstance()
                ->setSubject($asunto)
                ->setFrom($this->container->getParameter('richpolis.emails.to_email'))
                ->setTo($usuario->getEmail())
                ->setBody(
                $this->renderView('FrontendBundle:Default:enviarCorreo.html.twig', compact('usuario', 'sUsuario', 'sPassword', 'isNew', 'asunto')), 'text/html'
        );
        $this->get('mailer')->send($message);
    }
}