<?php
 
namespace FootBundle\Menu;
 
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
 
class MenuBuilder extends ContainerAware
{
    
    public function homeMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        
        //Menu super_admin
             $menu->addChild('Admin', array('label' => 'menu.admin'))
             ->setAttribute('dropdown', true)
             ->setAttribute('icon', 'fa fa-users');
             // pour super_admin_slad
        if($this->container->get('security.context')->isGranted(array('ROLE_SUPER_ADMIN_SLAD')))
        {	
            $menu['Admin']  ->addChild( 'saison', 		
                                        array(  'route' => 'saison',		
                                                'label' => 'menu.saison'
                                            )
                                        )		
                            ->setAttribute('icon', 'fa fa-list');
            $menu['Admin']  ->addChild( 'scoring', 		
                                        array(  'route' => 'scoring',		
                                                'label' => 'menu.scoring'
                                            )
                                        )		
                            ->setAttribute('icon', 'fa fa-list');
            $menu['Admin']  ->addChild( 'typematch', 		
                                        array(  'route' => 'typematch',		
                                                'label' => 'menu.typematch'
                                            )
                                        )		
                            ->setAttribute('icon', 'fa fa-list');
            $menu['Admin']  ->addChild( 'club', 		
                                        array(  'route' => 'club',		
                                                'label' => 'menu.club'
                                            )
                                        )		
                            ->setAttribute('icon', 'fa fa-list');
        }
        // pour admin
        if($this->container->get('security.context')->isGranted(array('ROLE_ADMIN')))
        {	
            
            $menu['Admin']  ->addChild( 'User', 		
                                        array(  'route' => 'user',		
                                                'label' => 'menu.user'
                                            )
                                        )		
                            ->setAttribute('icon', 'fa fa-list');
        }
        // pour admin
        if($this->container->get('security.context')->isGranted(array('ROLE_PLAYER')))
        {
        $menu['Admin']  ->addChild( 'match', 		
                                        array(  'route' => 'match',		
                                                'label' => 'menu.match'
                                            )
                                        )		
                            ->setAttribute('icon', 'fa fa-list');
            $menu['Admin']  ->addChild( 'equipe', 		
                                        array(  'route' => 'equipe',		
                                                'label' => 'menu.equipe'
                                            )
                                        )		
                            ->setAttribute('icon', 'fa fa-list');
        }
        return $menu;
    }
 
    public function userMenu(FactoryInterface $factory, array $options)
    {
        $trans = $this->container->get('translator');
        $niveau ='';
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

//        $menu->addChild('Aide', array('label' => ''))
//            ->setAttribute('icon', 'fa fa-question-circle')
//			->setAttribute('dropdown', false)
//			->setAttribute('id', $this->container->get('request')->get('_route'))
//            ->setAttribute('class', 'aide btn btn-success');	
			
        /*
        You probably want to show user specific information such as the username here. That's possible! Use any of the below methods to do this.*/

        if($this->container->get('security.context')->isGranted(array('ROLE_USER'))) 
        {
            $username = $this->container->get('security.context')->getToken()->getUser()->getUsername(); // Get username of the current logged in user
            $niveau = $this->container->get('app.security.roles')->getRoleuser();
        } else {
            $username = $trans->trans('form.visiteur');
        }// Check if the visitor has any authenticated roles
        
        

		$bonjour = $trans->trans('form.bonjour');
        $menu->addChild('User', array('label' => $niveau.'|'.$bonjour.' '.$username))
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'fa fa-user');
 
        
			
        /* Menu different si utilisateur connecté */
 
        if($this->container->get('security.context')->isGranted(array('ROLE_USER'))) 
        {
            $menu['User']   ->addChild('Edit profile', array('route' => 'fos_user_profile_edit','label' => 'form.profil.edit'))
                        ->setAttribute('icon', 'fa fa-tag');
            $menu['User']   ->addChild('Change password', array('route' => 'fos_user_change_password','label' => 'form.change_password'))
                        ->setAttribute('icon', 'fa fa-shield');
            $menu['User']   ->addChild('Logout', array('route' => 'fos_user_security_logout','label' => 'form.logout'))
                            ->setAttribute('icon', 'fa fa-sign-out');
        } else {
            $username = 'Visiteur';
            $menu['User']->addChild('Login', array('route' => 'fos_user_security_login','label' => 'form.login'))
            ->setAttribute('icon', 'fa fa-sign-in');
            $menu['User']->addChild('Register', array('route' => 'user_new','label' => 'form.register'))
            ->setAttribute('icon', 'fa fa-sign-in');
        }// Check if the visitor has any authenticated roles

        return $menu;
    }
   
 

}