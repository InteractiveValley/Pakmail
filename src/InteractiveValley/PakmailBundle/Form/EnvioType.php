<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use InteractiveValley\BackendBundle\Form\DataTransformer\UsuarioToNumberTransformer;

class EnvioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];
        $usuarioTransformer = new UsuarioToNumberTransformer($em);
        
        $builder
            ->add('direccionFiscal',new DireccionFiscalType(),array('label'=>'DIRECCION FISCAL'))
            ->add('direccionRemitente',new DireccionRemisionType(),array('label'=>'DIRECCION REMITENTE'))
            ->add('direccionDestino',new DireccionDestinoType(),array('label'=>'DIRECCION DESTINO'))
            ->add('referencia','text',array('label'=>'Referencia *','attr'=>array('class'=>'form-control')))
            ->add('tipo','text',array('label'=>'Tipo *','attr'=>array('class'=>'form-control')))
            //->add('kilogramos','text',array('label'=>'Peso Kg','attr'=>array('class'=>'form-control')))
            ->add('kilogramos','hidden')
            ->add('precio',null,array('label'=>'Precio *','attr'=>array('class'=>'form-control')))
            ->add('numGuia','text',array('label'=>'No. Guia *','attr'=>array('class'=>'form-control')))
            ->add('folio','text',array('label'=>'No. de Control Ticket o Folio *','attr'=>array('class'=>'form-control')))
            ->add('asegurarEnvio',null,array('label'=>'Desea Asegurar el Envío?','attr'=>array(
                'class'=>'checkbox-inline',
                'placeholder'=>'asegurar envio',
                'data-bind'=>'value: asegurarEnvio'
             )))
            ->add('montoSeguro',null,array('label'=>'Monto a Asegurar (Max 100,000,00)','attr'=>array('class'=>'form-control')))
            ->add('importeSeguro',null,array('label'=>'Importe del Seguro (2%)','attr'=>array('class'=>'form-control')))
            ->add('observaciones',null,array(
                'label'=>'Observaciones',
                'required'=>false,
                'attr'=>array(
                    'class'=>'cleditor tinymce form-control placeholder',
                   'data-theme' => 'advanced',
                    )
                ))
            ->add('medidaPeso','text',array('label'=>'Peso','attr'=>array('class'=>'form-control')))
            ->add('medidaLargo','text',array('label'=>'Largo','attr'=>array('class'=>'form-control')))
            ->add('medidaAncho','text',array('label'=>'Ancho','attr'=>array('class'=>'form-control')))
            ->add('medidaAlto','text',array('label'=>'Alto','attr'=>array('class'=>'form-control')))
            ->add('generarGastosAduana',null,array('label'=>'¿Generar gastos de aduana?','attr'=>array('class'=>'form-control')))
            ->add('valorDeclarado','number',array('label'=>'Valor declarado','attr'=>array('class'=>'form-control')))
            ->add('perfil','hidden')
            ->add('hasPerfil','hidden')
            ->add('status','hidden')
            ->add('cliente','entity',array(
                'class'=> 'PakmailBundle:Cliente',
                'label'=>'Cliente',
                'required'=>true,
                'read_only'=>false,
                'property'=>'nombre',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nombre', 'ASC');
                },
                'attr'=>array(
                    'class'=>'form-control placeholder',
                    'placeholder'=>'Cliente',
                    'data-bind'=>'value: cliente',
                    )
                ))
            ->add($builder->create('usuario', 'hidden')->addModelTransformer($usuarioTransformer))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
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
