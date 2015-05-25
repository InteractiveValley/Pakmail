<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label'=>'Evento','attr'=>array('class'=>'form-control')))
            ->add('descripcion',null,array(
                'label'=>'Descripcion',
                'required'=>true,
                'attr'=>[
                    'class'=>'tinymce form-control placeholder',
                    'data-theme' => 'advanced',
                    ]
                ))
            ->add('fechaEvento','date',array(
				'attr'=>array('class'=>'form-control datepicker'),
				'widget' => 'single_text', 
                'format' => 'y-MM-dd'
			))
            ->add('file','file',array('label'=>'Imagen','attr'=>array('class'=>'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Evento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_psjeronimobundle_evento';
    }
}
