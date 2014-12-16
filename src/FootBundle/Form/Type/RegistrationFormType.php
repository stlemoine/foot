<?php
// src/FootBundle/Form/Type/RegistrationFormType.php

namespace FootBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    private $class;
    private $security;
    private $roles;
    // récip des arguments passés au service
    public function __construct($class,$security,$roles) {
        parent::__construct($class);
        $this->security = $security;
        $this->roles = $roles->getRoles();
    }
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
        ->add('photo')
        ->add('tel',            'text',		
            array( 
                'required'  => false,
                'label'     => 'form.label.tel'
                  ));
        if($this->security->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $builder->add('club',	'entity',
                                array( 'class'          => 'FootBundle:Club',
                                       'required'       => false,
                                       'label'          => 'form.club',
									   'empty_value'	=>'form.placeholder.clubChoice',
               ));
        }
        if($this->security->isGranted('ROLE_ADMIN')){
            $builder->add('roles', 'choice', 
                    array(
                    'choices'       => $this->roles,
                    'label'         => 'form.label.roles',
                    'multiple'      => true
                ));
        }
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FootBundle\Entity\User',
            'intention'  => 'registration',
            'roles'         =>null
        ));
    }
    
    public function getName()
    {
        return 'app_user_registration';
    }
}
