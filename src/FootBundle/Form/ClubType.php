<?php

namespace FootBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClubType extends AbstractType
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
    {            
        $builder->add('nom','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.nom',
                                )
                        );
        $builder->add('photo');
        $builder->add('tel','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.tel',
                                )
                        );
        $builder->add('adresse','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.adresse',
                                )
                        );
        $builder->add('cp','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.cp',
                                )
                        );
        $builder->add('ville','text',
                                array(
                                    'required'  => false,
                                    'label' 	=> 'grid.ville',
                                )
                        );


    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FootBundle\Entity\Club'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'footbundle_club';
    }
}
