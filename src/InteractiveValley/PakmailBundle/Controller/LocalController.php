<?php

namespace InteractiveValley\PakmailBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InteractiveValley\PakmailBundle\Entity\Local;
use InteractiveValley\PakmailBundle\Form\LocalType;
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;
use InteractiveValley\BackendBundle\Utils\qqFileUploader;
use InteractiveValley\GaleriasBundle\Entity\Galeria;
/**
 * Local controller.
 *
 * @Route("/locales")
 */
class LocalController extends Controller
{

    /**
     * Lists all Local entities.
     *
     * @Route("/", name="locales")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PakmailBundle:Local')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new Local entity.
     *
     * @Route("/", name="locales_create")
     * @Method("POST")
     * @Template("PakmailBundle:Local:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Local();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			$entity->setSlug(RpsStms::slugify($entity->getNombre()));
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('locales_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => RpsStms::getErrorMessages($form),
        );
    }

    /**
     * Creates a form to create a Local entity.
     *
     * @param Local $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Local $entity)
    {
        $form = $this->createForm(new LocalType(), $entity, array(
            'action' => $this->generateUrl('locales_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Local entity.
     *
     * @Route("/new", name="locales_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Local();
        $max = $this->getDoctrine()->getRepository('PakmailBundle:Local')
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
     * Finds and displays a Local entity.
     *
     * @Route("/{id}", name="locales_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Local')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Local entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'get_galerias' =>$this->generateUrl('locales_galerias',array('id'=>$entity->getId()),true),
            'post_galerias' =>$this->generateUrl('locales_galerias_upload', array('id'=>$entity->getId()),true),
            'post_galerias_link_video' =>$this->generateUrl('locales_galerias_link_video', array('id'=>$entity->getId()),true),
            'url_delete' => $this->generateUrl('locales_galerias_delete',array('id'=>$entity->getId(),'idGaleria'=>'0'),true),
        );
    }

    /**
     * Displays a form to edit an existing Local entity.
     *
     * @Route("/{id}/edit", name="locales_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Local')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Local entity.');
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
    * Creates a form to edit a Local entity.
    *
    * @param Local $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Local $entity)
    {
        $form = $this->createForm(new LocalType(), $entity, array(
            'action' => $this->generateUrl('locales_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Local entity.
     *
     * @Route("/{id}", name="locales_update")
     * @Method("PUT")
     * @Template("PakmailBundle:Local:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PakmailBundle:Local')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Local entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
			$entity->setSlug(RpsStms::slugify($entity->getNombre()));
            $em->flush();

            return $this->redirect($this->generateUrl('locales_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores'     => RpsStms::getErrorMessages($editForm),
        );
    }
    /**
     * Deletes a Local entity.
     *
     * @Route("/{id}", name="locales_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PakmailBundle:Local')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Local entity.');
            }
            
            foreach($entity->getGalerias() as $galeria){
                $entity->removeGaleria($galeria);
                $em->remove($galeria);
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('locales'));
    }

    /**
     * Creates a form to delete a Local entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('locales_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Lists all Local galerias entities.
     *
     * @Route("/{id}/galerias", name="locales_galerias")
     * @Method("GET")
     */
    public function galeriasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $local = $em->getRepository('PakmailBundle:Local')->find($id);
        
        $galerias = $local->getGalerias();
        $get_galerias = $this->generateUrl('locales_galerias',array('id'=>$local->getId()),true);
        $post_galerias = $this->generateUrl('locales_galerias_upload', array('id'=>$local->getId()),true);
	$post_galerias_link_video = $this->generateUrl('locales_galerias_link_video', array('id'=>$local->getId()),true);
        $url_delete = $this->generateUrl('locales_galerias_delete',array('id'=>$local->getId(),'idGaleria'=>'0'),true);
        
        return $this->render('GaleriasBundle:Galeria:galerias.html.twig', array(
            'galerias'=>$galerias,
            'get_galerias' =>$get_galerias,
            'post_galerias' =>$post_galerias,
            'post_galerias_link_video' =>$post_galerias_link_video,
            'url_delete' => $url_delete,
        ));
    }
    
    /**
     * Crea una galeria de una local.
     *
     * @Route("/{id}/galerias", name="locales_galerias_upload")
     * @Method("POST")
     */
    public function galeriasUploadAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $local=$em->getRepository('PakmailBundle:Local')->find($id);
       
        if(!$request->request->has('tipoArchivo')){ 
            // list of valid extensions, ex. array("jpeg", "xml", "bmp")
            $allowedExtensions = array("jpeg","png","gif","jpg");
            // max file size in bytes
            $sizeLimit = 6 * 1024 * 1024;
            $uploader = new qqFileUploader($allowedExtensions, $sizeLimit,$request->server);
            $uploads= $this->container->getParameter('richpolis.uploads');
            $result = $uploader->handleUpload($uploads."/galerias/");
            // to pass data through iframe you will need to encode all html tags
            /*****************************************************************/
            //$file = $request->getParameter("qqfile");
            $max = $em->getRepository('GaleriasBundle:Galeria')->getMaxPosicion();
            if($max == null){
                $max=0;
            }
            if(isset($result["success"])){
                $registro = new Galeria();
                $registro->setArchivo($result["filename"]);
                $registro->setThumbnail($result["filename"]);
                $registro->setTitulo($result["titulo"]);
                $registro->setIsActive(true);
                $registro->setPosition($max+1);
                $registro->setTipoArchivo(RpsStms::TIPO_ARCHIVO_IMAGEN);
                //unset($result["filename"],$result['original'],$result['titulo'],$result['contenido']);
                $em->persist($registro);
                $registro->crearThumbnail();    
                $local->getGalerias()->add($registro);
                $em->flush();
            }
        }else{
            $result = $request->request->all(); 
            $registro = new Galeria();
            $videoData = RpsStms::getTitleAndImageVideoYoutube($result['archivo']);
            $registro->setArchivo($videoData['urlVideo']);
            $registro->setTitulo($videoData['title']);
            $registro->setDescripcion($videoData['description']);
            $registro->setThumbnail($videoData['thumbnail']);
            $registro->setIsActive($result['isActive']);
            $registro->setPosition($result['position']);
            $registro->setTipoArchivo($result['tipoArchivo']);
            $em->persist($registro);
            $local->getGalerias()->add($registro);
            $em->flush();  
        }
        
        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        $response->setData($result);
        return $response;
    }
    
    /**
     * Crea una galeria link video de una local.
     *
     * @Route("/{id}/galerias/link/video", name="locales_galerias_link_video", requirements={"id" = "\d+"})
     * @Method({"POST","GET"})
     */
    public function galeriasLinkVideoAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $local=$em->getRepository('PakmailBundle:Local')->find($id);
        $parameters = $request->request->all();
      
        if(isset($parameters['archivo'])){ 
            $registro = new Galeria();
            $videoData = RpsStms::getTitleAndImageVideoYoutube($parameters['archivo']);
            $registro->setArchivo($videoData['urlVideo']);
            $registro->setTitulo($videoData['title']);
            $registro->setDescripcion($videoData['description']);
            $registro->setThumbnail($videoData['thumbnail']);
            $registro->setIsActive($parameters['isActive']);
            $registro->setPosition($parameters['position']);
            $registro->setTipoArchivo($parameters['tipoArchivo']);
            $em->persist($registro);
            $local->getGalerias()->add($registro);
            $em->flush();  
        }
        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        $response->setData($parameters);
        return $response;
    }
    
    /**
     * Deletes una Galeria entity de una Local.
     *
     * @Route("/{id}/galerias/{idGaleria}", name="locales_galerias_delete")
     * @Method("DELETE")
     */
    public function deleteGaleriaAction(Request $request, $id, $idGaleria)
    {
            $em = $this->getDoctrine()->getManager();
            $local = $em->getRepository('PakmailBundle:Local')->find($id);
            $galeria = $em->getRepository('GaleriasBundle:Galeria')->find(intval($idGaleria));

            if (!$local) {
                throw $this->createNotFoundException('Unable to find Local entity.');
            }
            
            $local->getGalerias()->removeElement($galeria);
            $em->remove($galeria);
            $em->flush();
        

        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        $response->setData(array("ok"=>true));
        return $response;
    }

    /*
     * Crea el thumbnail especifico para la local de locales
     * 
     * @return void
     */
    public function crearThumbnailLocales(Galeria $galeria,$width=123,$height=123,$path=""){
        $imagine    = new \Imagine\Gd\Imagine();
        $collage    = $imagine->create(new \Imagine\Image\Box(123, 123));
        $mode       = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $image      = $imagine->open($galeria->getAbsolutePath());
        $sizeImage  = $image->getSize();
        if(strlen($path)==0){
            $path = $galeria->getAbosluteThumbnailPath();
        }
        if($height == null){
            $height = $sizeImage->getHeight();
            if($height>123){
                $height = 123;
            }
        }
        if($width == null){
            $width = $sizeImage->getWidth();
            if($width>123){
                $width = 123;
            }
        }
        $size   =new \Imagine\Image\Box($width,$height);
        $image->thumbnail($size,$mode)->save($path);
        $image = $imagine->open($path);
        $size = $image->getSize();
        if((123 - $size->getWidth())>1){
            $width = ceil((123 - $size->getWidth())/2);
        }else{
            $width = 0;
        }
        if((123 - $size->getHeight())>1){
            $height = ceil((123 - $size->getHeight())/2);
        }else{
            $height =0;
        }    
        $centrado = new \Imagine\Image\Point($width, $height);
        $collage->paste($image,$centrado);
        $collage->save($path);        
    }
    
    /**
     * Ordenar las posiciones de los locales.
     *
     * @Route("/ordenar/registros", name="locales_ordenar")
     * @Method("PATCH")
     */
    public function ordenarRegistrosAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $registro_order = $request->query->get('registro');
            $em = $this->getDoctrine()->getManager();
            $result['ok'] = true;
            foreach ($registro_order as $order => $id) {
                $registro = $em->getRepository('PakmailBundle:Local')->find($id);
                if ($registro->getPosition() != ($order + 1)) {
                    try {
                        $registro->setPosition($order + 1);
                        $em->flush();
                    } catch (Exception $e) {
                        $result['mensaje'] = $e->getMessage();
                        $result['ok'] = false;
                    }
                }
            }
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData($result);
            return $response;
        } else {
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData(array('ok' => false));
            return $response;
        }
    }
    
    /**
     * Actualizar las posiciones top y left.
     *
     * @Route("/{id}/posiciones", name="locales_posiciones")
     * @Method("PATCH")
     */
    public function posicionesAction(Request $request,$id) {
        if ($request->isXmlHttpRequest()) {
            $top = $request->request->get('top');
            $left = $request->request->get('left');
			$em = $this->getDoctrine()->getManager();
            $registro = $em->getRepository('PakmailBundle:Local')->find($id);
			if(!$registro){
                throw $this->createNotFoundException('Unable to find Local register.');
            }else{
                $registro->setTop($top);
                $registro->setLeft($left);
                $em->flush();
                $result = array('ok'=>true);
            }
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData($result);
            return $response;
        } else {
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData(array('ok' => false));
            return $response;
        }
    }
	
	/**
     * Actualizar las posiciones top y left.
     *
     * @Route("/{id}/direccion/tooltip", name="locales_direccion_tooltip")
     * @Method("PATCH")
     */
    public function direccionTooltipAction(Request $request,$id) {
        if ($request->isXmlHttpRequest()) {
            $direccion = $request->request->get('direccion');
			$em = $this->getDoctrine()->getManager();
            $registro = $em->getRepository('PakmailBundle:Local')->find($id);
			if(!$registro){
                throw $this->createNotFoundException('Unable to find Local register.');
            }else{
                $registro->setTooltip($direccion);
                $em->flush();
                $result = array('ok'=>true);
            }
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData($result);
            return $response;
        } else {
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData(array('ok' => false));
            return $response;
        }
    }
}
