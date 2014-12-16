<?php

namespace FootBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ScoringType extends AbstractType
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
    {            $builder->add('g','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.g',
                                )
                        );
            
            
                                $builder->add('n','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.n',
                                )
                        );
            
            
                                $builder->add('p','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.p',
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
            'data_class' => 'FootBundle\Entity\Scoring'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'footbundle_scoring';
    }
}
