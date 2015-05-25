<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PromocionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label'=>'Promocion/Novedad','attr'=>array('class'=>'form-control')))
            ->add('descripcion',null,array(
                'label'=>'Descripcion',
                'required'=>true,
                'attr'=>[
                    'class'=>'tinymce form-control placeholder',
                    'data-theme' => 'advanced',
                    ]
                ))
            ->add('tipo','choice', array(
                'choices' => ['promocion' => 'Promocion', 'novedad' => 'Novedad']
                ))
            ->add('file',null,array('label'=>'Imagen','attr'=>['class'=>'form-control']))
            ->add('isPrincipal',null,array('label'=>'Mostrar en pagina de inicio?','attr'=>array(
                'class'=>'checkbox-inline',
                'placeholder'=>'Es principal',
                'data-bind'=>'value: isPrincipal'
             )))
            ->add('position','hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Promocion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_psjeronimobundle_promocion';
    }
}
