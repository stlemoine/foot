<?php

namespace FootBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaisonType extends AbstractType
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
    {            $builder->add('debut','date',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.debut',
                                )
                        );
            
            
                                $builder->add('fin','date',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.fin',
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
            'data_class' => 'FootBundle\Entity\Saison'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'footbundle_saison';
    }
}
