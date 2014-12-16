<?php
// src/FootBundle/Form/RegistrationFormType.php

namespace FootBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        // add your custom field
        $builder
        ->add('prenom',         'text',		
            array( 
                'required'  => true,
                'label'     => 'form.label.prenom',
                  ))
        ->add('nom',            'text',		
            array( 
                'required'  => true,
                'label'     => 'form.label.nom'
                  ))
        ->add('tel',            'text',		
            array( 
                'required'  => true,
                'label'     => 'form.label.tel'
                  ));

        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FootBundle\Entity\User',
            'intention'  => 'registration',
        ));
    }
    
    public function getName()
    {
        return 'app_user_registration';
    }
}
