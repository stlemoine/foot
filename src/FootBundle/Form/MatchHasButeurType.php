<?php

namespace FootBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MatchHasButeurType extends AbstractType
{
    private $idmatch;

    public function __construct($idmatch,$em)
    {
      $this->id = $idmatch;
      $this->em = $em;
    }  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {            
        
        $builder->add('minute','integer',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.minute',
                                )
                        );
            
                                $builder->add('user','entity',
                                array(
                                    'class'       => 'FootBundle:User',
                                    'required'  => false,
                                    'label' 	=> 'grid.user',
                                )
                        );

                                  $builder->add('match','entity',
                                array(
                                    'class'       => 'FootBundle:Match',
                                    'required'  => false,
                                    'label' 	=> 'grid.match',
                                    'data'=>$this->em->getReference("FootBundle:Match",$this->id) ,
                                    'read_only'=>true
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
            'data_class' => 'FootBundle\Entity\MatchHasButeur'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'footbundle_matchhasbuteur';
    }
}
