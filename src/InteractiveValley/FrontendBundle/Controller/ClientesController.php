<?php

namespace InteractiveValley\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use InteractiveValley\BackendBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ClientesController extends BaseController {
    
    /**
     * @Route("/pakmail", name="pakmail_clientes")
     * @Template()
     */
    public function indexAction(Request $request) {
        return array();
    }
    
}
