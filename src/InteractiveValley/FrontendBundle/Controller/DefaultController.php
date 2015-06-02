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
use InteractiveValley\FrontendBundle\Form\QuejaType;
use InteractiveValley\PakmailBundle\Entity\Newsletter;
use InteractiveValley\PakmailBundle\Form\Frontend\NewsletterFrontendType;

class DefaultController extends BaseController {

    /**
     * @Route("/inicio", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $promociones = $em->getRepository('PakmailBundle:Promocion')
                          ->findBy(array(), array('position'=>'ASC'));
        
        $servicios = $em->getRepository('PakmailBundle:Servicio')
                          ->findBy(array(), array('position'=>'ASC'));
        
        
        return array(
            'promociones'=>$promociones,
            'servicios'=>$servicios
        );
    }
    
    /**
     * @Route("/quienes/somos", name="quienes_somos")
     * @Template()
     */
    public function quienesSomosAction(Request $request) {
        return array();
    }
    
    /**
     * @Route("/calendario", name="calendario")
     * @Template()
     */
    public function calendarioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $tipos = $em->getRepository('PakmailBundle:Fecha')->getTiposFechas();
        
        $fecha = new \DateTime();
        $year = $request->query->get('year', $fecha->format('Y'));
        $month = $request->query->get('month', $fecha->format('m'));
		$nombreMes = $this->getNombreMes($month);
        
        $fechas = $em->getRepository('PakmailBundle:Fecha')
                          ->findFechasPorFecha($month,$year);
        
        $calendario = $this->createCalendar($month, $year);
        
        return array(
            'tipos'=>$tipos,
            'fechas'=>$fechas,
            'nombreMes'=>$nombreMes,
            'month'=>$month,
            'year'=>$year,
            'calendario'=>$calendario
        );
    }
    
    private function createCalendar($month,$year){
        $fecha = new \DateTime("$year-$month-01 00:00:00");
        $diasDelMes = $fecha->format('t');
        $diaSemana = $fecha->format('w');
        
        $calendario = array(
            '0'=>array(0,0,0,0,0,0,0),
            '1'=>array(0,0,0,0,0,0,0),
            '2'=>array(0,0,0,0,0,0,0),
            '3'=>array(0,0,0,0,0,0,0),
            '4'=>array(0,0,0,0,0,0,0),
            '5'=>array(0,0,0,0,0,0,0)
        );
        
        $semana = 0;
        for($dia = 1; $dia <= $diasDelMes;$dia++){
            $calendario[$semana][$diaSemana] = $dia;
            $diaSemana++;
            if($diaSemana > 6){
                $semana++;
                $diaSemana = 0;
            }
        }
        
        return $calendario;
    }
    

    /**
     * @Route("/contacto", name="contacto")
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
                        ->setFrom($this->container->getParameter('richpolis.emails.to_email'))
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

        return $this->render('FrontendBundle:Default:contacto.html.twig', array(
                    'form' => $form->createView(),
                    'status' => $status,
                    'mensaje' => $mensaje
        ));
    }
    
    /**
     * @Route("/quejas", name="quejas")
     * @Method({"GET", "POST"})
     */
    public function quejasAction(Request $request) {
        $contacto = new Contacto();
        $form = $this->createForm(new QuejaType(), $contacto);
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $datos = $form->getData();
                $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                        ->findOneBy(array('slug' => 'email-quejas'));
                $message = \Swift_Message::newInstance()
                        ->setSubject('Contacto desde pagina')
                        ->setFrom($this->container->getParameter('richpolis.emails.to_email'))
                        ->setTo($configuracion->getTexto())
                        ->setBody($this->renderView('FrontendBundle:Default:quejaEmail.html.twig', array('datos' => $datos)), 'text/html');
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
            $vista = $this->renderView('FrontendBundle:Default:formQuejas.html.twig', array(
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

        return $this->render('FrontendBundle:Default:formQuejas.html.twig', array(
                    'form' => $form->createView(),
                    'status' => $status,
                    'mensaje' => $mensaje
        ));
    }

    /**
     * @Route("/newsletter", name="newsletter")
     * @Method({"GET", "POST"})
     */
    public function newsletterAction(Request $request) {
        $registro = new Newsletter();
        $form = $this->createForm(new NewsletterFrontendType(), $registro);
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $newsletter = $em->getRepository('PakmailBundle:Newsletter')
                                 ->findOneBy(array('email'=>$registro->getEmail()));
                if(!$newsletter){
                    $registro->setIsActive(true);
                    $em->persist($registro);
                }else{
                    $newsletter->setNombre($registro->getNombre());
                    $em->persist($newsletter);
                    $registro = null;
                }
                $em->flush();
                $status = 'send';
                $mensaje = "Gracias por su registro.";
                $registro = new Newsletter();
                $form = $this->createForm(new NewsletterFrontendType(), $registro);
            } else {
                $status = 'notsend';
                $mensaje = "Gracias por su registro!!.";
				$registro = new Newsletter();
                $form = $this->createForm(new NewsletterFrontendType(), $registro);
            }
        } else {
            $status = 'new';
            $mensaje = "";
        }

        if ($request->isXmlHttpRequest()) {
            $vista = $this->renderView('FrontendBundle:Default:formNewsletter.html.twig', array(
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

        return $this->render('FrontendBundle:Default:formNewsletter.html.twig', array(
                    'form' => $form->createView(),
                    'status' => $status,
                    'mensaje' => $mensaje
        ));
    }
}
