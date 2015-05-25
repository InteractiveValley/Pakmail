<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label'=>'Locatario','attr'=>array('class'=>'form-control')))
            ->add('descripcion',null,array(
                'label'=>'Descripcion',
                'required'=>true,
                'attr'=>[
                    'class'=>'tinymce form-control placeholder',
                    'data-theme' => 'advanced',
                    ]
                ))
            ->add('telefono','text',array('attr'=>array('class'=>'form-control')))
            ->add('web','text',array('attr'=>array('class'=>'form-control')))
            ->add('facebook','text',array('attr'=>array('class'=>'form-control')))
            ->add('twitter','text',array('attr'=>array('class'=>'form-control')))
            ->add('instagram','text',array('attr'=>array('class'=>'form-control')))
            ->add('horarios','text',array('attr'=>array('class'=>'form-control')))
            ->add('fileLogo',null,array('label'=>'Logo','attr'=>['class'=>'form-control']))
            ->add('file',null,array('label'=>'Mapa','attr'=>['class'=>'form-control']))    
            ->add('position','hidden')
            ->add('isActive','hidden')
            ->add('slug','hidden')
            ->add('top','hidden')
            ->add('left','hidden')
			->add('tooltip','hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Local'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_psjeronimobundle_local';
    }
}
