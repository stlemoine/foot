<?php

namespace FootBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FootBundle\Form\MatchHasButeurType;

class MatchType extends AbstractType
{
    private $roleFlag;

    public function __construct($roleFlag,$em)
    {
      $this->isGranted = $roleFlag;
      $this->em = $em;
    }    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {           
      
        $builder->add('date','datetime',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.date',
                                )
                        );
            
            
                                $builder->add('domicile','checkbox',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.domicile',
                                )
                        );

                                $builder->add('bp','integer',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.bp',
                                )
                        );
            
            
                                $builder->add('bc','integer',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.bc',
                                )
                        );
            
            
                                $builder->add('equipe','entity',
                                array(
                                    'class'       => 'FootBundle:Equipe',
                                    'required'  => false,
                                    'label' 	=> 'grid.equipe',
                                )
                        );

                                $builder->add('club','entity',
                                array(
                                    'class'       => 'FootBundle:Club',
                                    'required'  => false,
                                    'label' 	=> 'grid.club',
                                )
                        );
            
            
                                $builder->add('typematch','entity',
                                array(
                                    'class'       => 'FootBundle:Typematch',
                                    'required'  => false,
                                    'label' 	=> 'grid.typematch',
                                )
                        );
                                if($builder->getData()->getId() > 0){
                                 $builder->add('matchHasButeurs', 'collection', array(
                                        'type' => new MatchHasButeurType($builder->getData()->getId(),$this->em),
                                        'allow_add' => true,
                                        'allow_delete' => true,
                                        'by_reference' => false,
                                    ));   
                                }
                                
                                if($builder->getData()->getId() > 0){
                                 $builder->add('matchHasUsers', 'collection', array(
                                        'type' => new MatchHasUserType($builder->getData()->getId(),$this->em),
                                        'allow_add' => true,
                                        'allow_delete' => true,
                                        'by_reference' => false,
                                    ));   
                                }
                                
            
            
                      
        ;  

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FootBundle\Entity\Match'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'footbundle_match';
    }
}
