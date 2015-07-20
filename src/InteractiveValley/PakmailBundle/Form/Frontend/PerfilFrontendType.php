<?php

namespace InteractiveValley\PakmailBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use InteractiveValley\PakmailBundle\Form\DataTransformer\ClienteToNumberTransformer;
use InteractiveValley\PakmailBundle\Form\DireccionFiscalType;
use InteractiveValley\PakmailBundle\Form\DireccionRemisionType;
use InteractiveValley\PakmailBundle\Form\DireccionDestinoType;

class PerfilFrontendType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];
        $clienteTransformer = new ClienteToNumberTransformer($em);
        
        $builder
            ->add('direccionFiscal',new DireccionFiscalType(),array('label'=>'DIRECCION FISCAL'))
            ->add('direccionRemitente',new DireccionRemisionType(),array('label'=>'DIRECCION REMITENTE'))
            ->add('direccionDestino',new DireccionDestinoType(),array('label'=>'DIRECCION DESTINO'))
            ->add('referencia','text',array('attr'=>array('label'=>'Referencia','class'=>'form-control')))
            ->add('tipo','text',array('attr'=>array('label'=>'Tipo','class'=>'form-control')))
            ->add('kilogramos','text',array('label'=>'Peso Kg','attr'=>array('class'=>'form-control')))
            ->add('precio',null,array('label'=>'Precio','attr'=>array('class'=>'form-control')))
            ->add('numGuia','text',array('label'=>'No. Guia','attr'=>array('class'=>'form-control')))
            //->add('folio','text',array('label'=>'No. de Control Ticket o Folio','attr'=>array('class'=>'form-control')))
            ->add('asegurarEnvio',null,array('label'=>'Desea Asegurar el Envío?','attr'=>array(
                'class'=>'checkbox-inline',
                'placeholder'=>'asegurar envio',
                'data-bind'=>'value: asegurarEnvio'
             )))
            ->add('montoSeguro',null,array('label'=>'Monto a Asegurar (Max 100,000,00)','attr'=>array('class'=>'form-control')))
            ->add('importeSeguro',null,array('label'=>'Importe del Seguro (2%)','attr'=>array('class'=>'form-control')))
            ->add('observaciones',null,array(
                'label'=>'Observaciones',
                'required'=>true,
                'attr'=>array(
                    'class'=>'cleditor tinymce form-control placeholder',
                   'data-theme' => 'advanced',
                    )
                ))    
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
        ->setRequired(array('em'))
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
