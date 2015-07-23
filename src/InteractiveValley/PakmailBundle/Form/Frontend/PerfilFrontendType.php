<?php

namespace InteractiveValley\PakmailBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use InteractiveValley\PakmailBundle\Form\DataTransformer\ClienteToNumberTransformer;
use InteractiveValley\PakmailBundle\Form\Frontend\DireccionFiscalFrontendType;
use InteractiveValley\PakmailBundle\Form\Frontend\DireccionRemisionFrontendType;
use InteractiveValley\PakmailBundle\Form\Frontend\DireccionDestinoFrontendType;

class PerfilFrontendType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];
		$paisesDestino = $options['paisesDestino'];
		$paisesFiscal = $options['paisesFiscal'];
		$paisesRemision = $options['paisesRemision'];
        $clienteTransformer = new ClienteToNumberTransformer($em);
        
        $builder
            ->add('direccionFiscal',new DireccionFiscalFrontendType($paisesFiscal),array('label'=>'DIRECCION FISCAL'))
            ->add('direccionRemitente',new DireccionRemisionFrontendType($paisesRemision),array('label'=>'DIRECCION REMITENTE'))
            ->add('direccionDestino',new DireccionDestinoFrontendType($paisesDestino),array('label'=>'DIRECCION DESTINO'))
            ->add('referencia','text',array('label'=>'Referencia *','attr'=>array('class'=>'form-control required')))
            ->add('tipo','text',array('label'=>'Tipo *','attr'=>array('class'=>'form-control required')))
            ->add('precio',null,array('label'=>'Precio *','attr'=>array('class'=>'form-control required')))
            ->add('numGuia','text',array('label'=>'No. Guia *','attr'=>array('class'=>'form-control required')))
            ->add('asegurarEnvio',null,array('label'=>'Desea Asegurar el Envío?','required'=>false,'attr'=>array(
                'class'=>'checkbox-inline',
                'placeholder'=>'asegurar envio',
                'data-bind'=>'value: asegurarEnvio'
             )))
            ->add('montoSeguro',null,array('label'=>'Monto a Asegurar (Max 100,000,00)','required'=>false,'attr'=>array('class'=>'form-control')))
            ->add('importeSeguro',null,array('label'=>'Importe del Seguro (2%)','required'=>false,'attr'=>array('class'=>'form-control')))
            ->add('observaciones',null,array(
                'label'=>'Observaciones',
                'required'=>false,
                'attr'=>array(
                    'class'=>'cleditor tinymce form-control placeholder',
                   'data-theme' => 'advanced',
                    )
                ))
            ->add('medidaPeso','text',array('label'=>'Peso *','attr'=>array('class'=>'form-control required')))
            ->add('medidaLargo','text',array('label'=>'Largo *','attr'=>array('class'=>'form-control required')))
            ->add('medidaAncho','text',array('label'=>'Ancho *','attr'=>array('class'=>'form-control required')))
            ->add('medidaAlto','text',array('label'=>'Alto *','attr'=>array('class'=>'form-control required')))
            ->add('generarGastosAduana',null,array('label'=>'Generar Gastos de Aduana?','required'=>false,'attr'=>array(
                'class'=>'checkbox-inline',
                'placeholder'=>'generar gastos aduana',
                'data-bind'=>'value: generarGastosAduana'
             )))
            ->add('valorDeclarado','number',array('label'=>'Valor declarado','required'=>false,'attr'=>array('class'=>'form-control')))
            ->add('nombre','text',array('label'=>'Nombre del perfil','attr'=>array('class'=>'form-control')))
            ->add('isActive',null,array('label'=>'Activo?','attr'=>array(
                    'class'=>'checkbox-inline',
                    'placeholder'=>'Es activo',
                    'data-bind'=>'value: isActive'
                )))
            ->add('folio','hidden')
            ->add($builder->create('cliente','hidden')->addModelTransformer($clienteTransformer))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Perfil'
        ))
        ->setRequired(array('em','paisesRemision','paisesFiscal','paisesDestino'))
        ->setAllowedTypes(array('em' => 'Doctrine\Common\Persistence\ObjectManager'))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_pakmailbundle_perfil';
    }
}
