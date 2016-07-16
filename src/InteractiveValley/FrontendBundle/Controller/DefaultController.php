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
                          ->findActivas();
        $servicios = $em->getRepository('PakmailBundle:Servicio')
                          ->findBy(array(), array('position'=>'ASC'));
        $aliados = $em->getRepository('PakmailBundle:Aliado')
                          ->findBy(array(), array('position'=>'ASC'));
        $menus = $em->getRepository('PakmailBundle:Menu')
                          ->findBy(array(), array('position'=>'ASC'));
        return array(
            'promociones'=>$promociones,
            'servicios'=>$servicios,
            'aliados'=>$aliados,
            'menus'=>$menus,
            'section' => 'inicio',
            'facebook' => $em->getRepository('BackendBundle:Configuraciones')->findOneByConfiguracion('enlace-facebook'),
        );
    }
    
    /**
     * @Route("/quienes/somos", name="quienes_somos")
     * @Template()
     */
    public function quienesSomosAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $preguntas = $em->getRepository('PakmailBundle:Pregunta')
                        ->findBy(array(),array('position'=>'ASC'));
        $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                        ->findOneBy(array('slug' => 'quienes-somos'));
        $menus = $em->getRepository('PakmailBundle:Menu')
                          ->findBy(array(), array('position'=>'ASC'));
        return array(
            'preguntas'=>$preguntas,
            'pagina'=>$configuracion,
            'section' => 'quienes',
            'menus'=>$menus,
            'facebook' => $em->getRepository('BackendBundle:Configuraciones')->findOneByConfiguracion('enlace-facebook'),
        );
    }
    
    /**
     * @Route("/calendario", name="calendario")
     * @Template()
     */
    public function calendarioAction(Request $request) {
        return $this->redirect($this->generateUrl('_portada'), 301);
        /*
        $em = $this->getDoctrine()->getManager();
        $tipos = $em->getRepository('PakmailBundle:TiposFecha')
                    ->findBy(array(),array('position'=>'ASC'));
        
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
            'calendario'=>$calendario,
            'section' => 'calendario'
        );
         * 
         */
    }
    
    /**
     * @Route("/servicios", name="frontend_servicios")
     * @Template()
     */
    public function serviciosAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $servicios = $em->getRepository('PakmailBundle:Servicio')
                          ->findBy(array(), array('position'=>'ASC'));
        $menus = $em->getRepository('PakmailBundle:Menu')
                          ->findBy(array(), array('position'=>'ASC'));
        return array(
            'servicios'=>$servicios,
            'section' => 'servicios',
            'menus'=>$menus,
            'facebook' => $em->getRepository('BackendBundle:Configuraciones')->findOneByConfiguracion('enlace-facebook'),
        );
    }
    
    /**
     * @Route("/aviso-privacidad", name="aviso_privacidad")
     * @Template()
     */
    public function avisoPrivacidadAction(Request $request){
       return array();
    }
    
    /**
     * @Route("/terminos-condiciones", name="terminos_condiciones")
     * @Template()
     */
    public function terminosAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $menus = $em->getRepository('PakmailBundle:Menu')
                          ->findBy(array(), array('position'=>'ASC'));
        $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                ->findOneBy(array('slug' => 'terminos-condiciones'));
        return array(
            'section' => 'servicios',
            'menus'=>$menus,
            'facebook' => $em->getRepository('BackendBundle:Configuraciones')->findOneByConfiguracion('enlace-facebook'),
            'configuracion' => $configuracion,
        );
    }
    
    private function createCalendar($month,$year){
        $fecha = new \DateTime("$year-$month-01 00:00:00");
        $diasDelMes = $fecha->format('t');
        $diaSemana = $fecha->format('w');
        
        $calendario = array();
        for($contSemanas = 0; $contSemanas<=5;$contSemanas++){
            $calendario[$contSemanas]=array();
            for($contDias = 0;$contDias<=6;$contDias++){
                $calendario[$contSemanas][$contDias]=array('dia'=>0,'actual'=>false);
            }
        }
        
        $semana = 0;
        for($dia = 1; $dia <= $diasDelMes;$dia++){
            $calendario[$semana][$diaSemana]['dia'] = $dia;
            $calendario[$semana][$diaSemana]['actual'] = true;
            $diaSemana++;
            if($diaSemana > 6){
                $semana++;
                $diaSemana = 0;
            }
        }
        
        for($dia = 1; $dia <= $diasDelMes;$dia++){
            $calendario[$semana][$diaSemana]['dia'] = $dia;
            $calendario[$semana][$diaSemana]['actual'] = false;
            $diaSemana++;
            if($diaSemana > 6){
                $semana++;
                $diaSemana = 0;
                if($semana>5){
                    break;
                }
            }
        }
        
        return $this->setDiasMesAnterior($month, $year, $calendario);
    }
    
    public function setDiasMesAnterior($month, $year, $calendario){
        if($month==1){
            $month = 12;
            $year--;
        }else{
            $month--;
        }
        //extraer los dias del mes
        $fecha = new \DateTime("$year-$month-01 00:00:00");
        $diasDelMes = $fecha->format('t');
        //tomar el dia de la semana del ultimo dia del mes
        $fecha = new \DateTime("$year-$month-$diasDelMes 00:00:00");
        $diaSemanaUltimo = $fecha->format('w');
        if($diaSemanaUltimo<6){ //no procede este codigo si el ultimo dia fue sabado
            //cuantos dias restan de la semana
            $diasDisponibles = $diasDelMes - ($diaSemanaUltimo + 1);
            for($dia = $diasDelMes; $dia>=$diasDisponibles; $dia--){
                $calendario[0][$diaSemanaUltimo]['dia'] = $dia;
                $calendario[0][$diaSemanaUltimo]['actual'] = false;
                $diaSemanaUltimo--;
                if($diaSemanaUltimo==-1){
                    break;
                }
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
        $menus = $em->getRepository('PakmailBundle:Menu')
                          ->findBy(array(), array('position'=>'ASC'));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $datos = $form->getData();
                $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                        			->findOneBy(array('slug' => 'email-contacto'));
                $message = \Swift_Message::newInstance()
                        ->setSubject('Contacto desde pagina')
                        //->setFrom($this->container->getParameter('richpolis.emails.to_email'))
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
                    'mensaje' => $mensaje,
                    'section' => 'contacto',
                    'menus'=>$menus,
                    'facebook' => $em->getRepository('BackendBundle:Configuraciones')->findOneByConfiguracion('enlace-facebook'),
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
                        //->setFrom($this->container->getParameter('richpolis.emails.to_email'))
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
                    'mensaje' => $mensaje,
                    'facebook' => $em->getRepository('BackendBundle:Configuraciones')->findOneByConfiguracion('enlace-facebook'),
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
                $mensaje = "¡Gracias por su registro!";
                $registro = new Newsletter();
                $form = $this->createForm(new NewsletterFrontendType(), $registro);
            } else {
                $status = 'notsend';
                $mensaje = "¡Gracias por su registro!";
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
                    'mensaje' => $mensaje,
                    'facebook' => $em->getRepository('BackendBundle:Configuraciones')->findOneByConfiguracion('enlace-facebook'),
        ));
    }
}
