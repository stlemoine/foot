<?php

namespace FootBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EquipeType extends AbstractType
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
    {            $builder->add('nom','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'form.nom',
                                )
                        );

                                $builder->add('photo');
            
            
            
                                $builder->add('club','entity',
                                array(
                                    'class'       => 'FootBundle:Club',
                                    'required'  => false,
                                    'label' 	=> 'form.club',
                                )
                        );
            
            
                                $builder->add('saison','entity',
                                array(
                                    'class'       => 'FootBundle:Saison',
                                    'required'  => false,
                                    'label' 	=> 'form.saison',
                                )
                        );
            
            
                                $builder->add('scoring','entity',
                                array(
                                    'class'       => 'FootBundle:Scoring',
                                    'required'  => false,
                                    'label' 	=> 'form.scoring',
                                )
                        );
            
            
                                $builder->add('users','entity',
                                array(
                                    'class'       => 'FootBundle:User',
                                    'required'  => false,
                                    'label' 	=> 'form.users',
                                    'multiple'  => true
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
            'data_class' => 'FootBundle\Entity\Equipe'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'footbundle_equipe';
    }
}
