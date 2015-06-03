<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TiposFechaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label'=>'Tipo de fecha','attr'=>array('class'=>'tipo-fecha form-control')))
            ->add('bgColor','choice',array(
                    'label'     =>  'Color',
                    'choices'   =>  array('azul' => 'Azul', 'verde' => 'Verde','vino'=>'Vino','gris'=>'Gris claro'),
                    'attr'      =>  array('class'=>'bgcolor form-control')
                ))
            ->add('fontColor','choice',array(
                    'label'     =>  'Color de letra',
                    'choices'   =>  array('white' => 'Blanco', 'black' => 'Negro'),
                    'attr'      =>  array('class'=>'fontcolor form-control')
                ))
            ->add('position','hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\TiposFecha'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_pakmailbundle_tiposfecha';
    }
}
