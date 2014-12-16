<?php

namespace FootBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TypematchType extends AbstractType
{
    private $roleFlag;

    public function __construct($roleFlag)
    {
      $this->isGranted = $roleFlag;
    }    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {            $builder->add('type','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.typematch',
                                )
                        );
            
            
                      
        ;  

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FootBundle\Entity\Typematch'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'footbundle_typematch';
    }
}
