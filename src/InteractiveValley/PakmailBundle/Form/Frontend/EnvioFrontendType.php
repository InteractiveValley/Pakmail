<?php

namespace InteractiveValley\PakmailBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use InteractiveValley\BackendBundle\Form\DataTransformer\UsuarioToNumberTransformer;
use InteractiveValley\PakmailBundle\Form\DataTransformer\ClienteToNumberTransformer;
use InteractiveValley\PakmailBundle\Form\DireccionFiscalType;
use InteractiveValley\PakmailBundle\Form\DireccionRemisionType;
use InteractiveValley\PakmailBundle\Form\DireccionDestinoType;

class EnvioFrontendType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];
        $usuarioTransformer = new UsuarioToNumberTransformer($em);
        $clienteTransformer = new ClienteToNumberTransformer($em);
        
        $builder
            ->add('direccionFiscal',new DireccionFiscalType())
            ->add('direccionRemitente',new DireccionRemisionType())
            ->add('direccionDestino',new DireccionDestinoType())
            ->add('referencia','text',array('attr'=>array('class'=>'form-control')))
            ->add('tipo','text',array('attr'=>array('class'=>'form-control')))
            ->add('kilogramos','text',array('attr'=>array('class'=>'form-control')))
            ->add('precio',null,array('attr'=>array('class'=>'form-control')))
            ->add('numGuia','text',array('attr'=>array('class'=>'form-control')))
            ->add('folio','text',array('attr'=>array('class'=>'form-control')))
            ->add('asegurarEnvio',null,array('label'=>'¿Asegurar envío?','attr'=>array(
                'class'=>'checkbox-inline',
                'placeholder'=>'asegurar envio',
                'data-bind'=>'value: asegurarEnvio'
             )))
            ->add('montoSeguro',null,array('attr'=>array('class'=>'form-control')))
            ->add('importeSeguro',null,array('attr'=>array('class'=>'form-control')))
            ->add('observaciones',null,array(
                'label'=>'Observaciones',
                'required'=>true,
                'attr'=>array(
                    'class'=>'cleditor tinymce form-control placeholder',
                   'data-theme' => 'advanced',
                    )
                ))
            ->add('perfil','hidden')
            ->add('hasPerfil','hidden')
            ->add('status','hidden')
            ->add('fechaSolicitud', 'date', array(
                    'attr' => array('class' => 'form-control datepicker'),
                    'widget' => 'single_text',
                    'format' => 'y-MM-dd'
                ))
            ->add($builder->create('cliente','hidden')->addModelTransformer($clienteTransformer))
            ->add($builder->create('usuario', 'hidden')->addModelTransformer($usuarioTransformer))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Envio'
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
        return 'interactivevalley_pakmailbundle_envio';
    }
}
