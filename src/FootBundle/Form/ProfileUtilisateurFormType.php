<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FootBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ProfileUtilisateurFormType extends AbstractType
{

    private $roles;
    private $security;

    public function __construct($security,$roles)
    {
      $this->roles = $roles;
      $this->security = $security;
      
    } 

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username',       'text',		
            array( 
                'required'  => true,
                'label'     => 'form.label.username'
                  ))
        ->add('email',          'text',		
            array( 
                'required'  => true,
                'label'     => 'form.label.email'
                  ))
        ->add('prenom',         'text',		
            array( 
                'required'  => true,
                'label'     => 'form.label.prenom'
                  ))
        ->add('nom',            'text',		
            array( 
                'required'  => true,
                'label'     => 'form.label.nom'
                  ))
        ->add('tel',            'text',		
            array( 
                'required'  => false,
                'label'     => 'form.label.tel'
                  ))
        ->add('enabled',        'checkbox',		
            array( 
                'required'  => false,
                'label'     => 'form.label.enabled'
                  ))
        ->add('roles', 'choice', array(
            'choices'       => $this->roles,
            'label'     => 'form.label.roles',
            'multiple'      => true
        ))
        ;
        if($this->security->isGranted('ROLE_ADMIN_SLAD')){
            $builder->add('client',	'entity',
                                array( 'class' 	=> 'FootBundle:Club',
                                       'required'  => false,
                                       'label' 	=> 'form.user.club',
									   'empty_value'	=>'form.placeholder.clubChoice',
               ));
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'FootBundle\Entity\User',
            'roles'         =>null
        ));
    }

    public function getName()
    {
        return 'profile_utilisateur_edit';
    }

}
