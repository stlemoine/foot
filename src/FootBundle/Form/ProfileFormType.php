<?php
// src/FootBundle/Form/ProfileFormType.php

namespace FootBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{ 
   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        // add your custom field
        $builder->add('prenom','text',array());
        $builder->add('nom','text',array());
        $builder->add('tel','text',array());
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
        	'data_class'    => 'FootBundle\Entity\User',
    	);
    }

    public function getName()
    {
        return 'app_user_profile';
    }
}
