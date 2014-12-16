<?php
// src/FootBundle/Controller/UserController.php
namespace FootBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Export\SCSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use FOS\UserBundle\Model\UserInterface;

use FootBundle\Form\Type\ProfileUtilisateurFormType;
use FootBundle\Form\Type\PassUtilisateurFormType;
use FootBundle\Form\Type\RegistrationFormType;


/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // Liste des Users
        $source = new Entity('FootBundle:User');
        if(!$this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $idclub	= $this->container->get('security.context')->getToken()->getUser()->getClub()->getId();
            $source->manipulateQuery(
                    function ($query) use ($idclub)
                    {
                        $query	->andWhere('_a.club = '.$idclub) ;
                    }
            );            
        }
     	// Get a grid instance
        $grid = $this->get('grid');
        
     	// Attach the source to the grid
        $grid->setSource($source);
        $grid->addExport(new SCSVExport('SC-CSV membres Export','Membres',array(),'UTF-8','ROLE_ADMIN'));
        $grid->addExport(new ExcelExport('Excel membres Export','Membres',array(),'UTF-8','ROLE_ADMIN'));
        // prefixe pour la traduction
        $grid->setPrefixTitle('grid.');

        $source->manipulateRow(
            function ($row) 
            { 
                $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');

                $url = $helper->asset($row->getEntity(), 'user_pict');

                // Change the output of the new column with your own code at entity.
                $row->setField('imageName', $url);
                
                return $row;
            }
        );

        // Réglage des filtres
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $grid->getColumn('club.nom')->setFilterable(false);
        }
        // Réglage taille des colonnes

        $grid->setActionsColumnSize(120);
        // Réglage pagination
        $grid->setLimits(array(10, 15, 20, 50));
		
        // Add a typed column with a rendering callback
        $edit = new rowAction('Edit','user_edit', false, '_self',array(),'ROLE_ADMIN'); 
        $edit->setRouteParameters(array('id'));
        $grid->addRowAction($edit);

        $show = new rowAction('Show','user_show', false, '_self',array(),'ROLE_ADMIN'); 
        $show->setRouteParameters(array('id'));
        $grid->addRowAction($show);	

        // Prépa avant envoi	
        $grid->isReadyForRedirect();


        return $grid->getGridResponse('FootBundle:User:index.html.twig',
                                        array(  'grid'=>$grid,
                                                'entity'=>$source
                                                )
                                      );	
    }
    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Template()
     */
    public function newAction()
    {

        // traduction
        $t = $this->get('translator');
        // Récupération du user connecté
        $user = $this->container->get('security.context')->getToken()->getUser();
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        
        if ($process) {
            $utilisateurId = $form->getData()->getId();

            $userManager = $this->container->get('fos_user.user_manager');
            $utilisateur = $userManager->findUserBy(array('id' => $utilisateurId));

            $utilisateur->setClub($user->getClub());
            $route = 'user';

            $userManager->updateUser($utilisateur);

            // message de création réussie
            $this->get('session')->getFlashBag()->add(
                        'success',
                        $t->trans('form.succes.creation_ok')
            );
            
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            return $response;
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/show", name="user_show")
     * @Template()
     */
    public function showAction(Request $request)
    {
        // traduction
        $t = $this->get('translator');
        // Récupération des infos dans le request (GET)
        $id = $request->query->get('id');
        // utilisation du usermanager de FOSUserBundle
        $userManager = $this->container->get('fos_user.user_manager');
        $utilisateur = $userManager->findUserBy(array('id' => $id));

        $this->container->get('app.checkaccess')->Club($utilisateur);
        
        return $this->render('/Appli/User/show.html.twig', array(
            'entity'      => $utilisateur,
            ));
    } 

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/edit", name="user_edit")
     * @Template()
     */
    public function editAction(Request $request)
    {
        // traduction
        $t = $this->get('translator');
        // Liste des roles possibles
        $roles = $this->container->get('app.security.roles')->getRoles();
        // Récupération des infos dans le request (GET)
        $id = $request->query->get('id');
        // utilisation du usermanager de FOSUserBundle
        $userManager = $this->container->get('fos_user.user_manager');
        $utilisateur = $userManager->findUserBy(array('id' => $id));

        $this->container->get('app.checkaccess')->Club($utilisateur);

        $editForm = $this->createForm(new ProfileUtilisateurFormType(
                                                        $this->container->get('security.context'),
                                                        $roles), $utilisateur);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Appli\User\edit.html.twig', array(
            'entity'      => $utilisateur,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));

    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/update", name="user_update")
     */
    public function updateAction(Request $request)
    {
        // traduction
        $t = $this->get('translator');
        // entity manager
        $em = $this->getDoctrine()->getManager();
        // Récupération des infos dans le request (POST)
        $id = $request->request->get('id');
        
        // utilisation du usermanager de FOSUserBundle
        $userManager = $this->container->get('fos_user.user_manager');
        $utilisateur = $userManager->findUserBy(array('id' => $id));

        $this->container->get('app.checkaccess')->Club($utilisateur);
        
        // Liste des roles possibles
        $roles = $this->container->get('app.security.roles')->getRoles();

        if (!$utilisateur) {
            throw $this->createNotFoundException($t->trans('form.erreur.objet_introuvable'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ProfileUtilisateurFormType(
                                                        $this->container->get('security.context'),
                                                        $roles), $utilisateur);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($utilisateur);
            $em->flush();
            
            // message mise à jour réussie
            $this->get('session')->getFlashBag()->add(
                        'success',
                        $t->trans('form.success.modif_ok')
            );

            return $this->render('/Appli/User/show.html.twig', array(
            'entity'      => $utilisateur,
                ));
        }
        // message mise à jour ratée
        $this->get('session')->getFlashBag()->add(
            'error',
            $t->trans('form.erreur.saisie')
        );
        
        return $this->render('Appli\User\edit.html.twig', array(
            'entity'      => $utilisateur,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * @Route("/pass",name="user_pass")
     */
    public function passAction(Request $request)
    {
        // traduction
        $t = $this->get('translator');

        // Récupération des infos dans le request (GET)
        $id = $request->query->get('id');
        // utilisation du usermanager de FOSUserBundle
        $userManager = $this->container->get('fos_user.user_manager');
        $utilisateur = $userManager->findUserBy(array('id' => $id));

        $this->container->get('app.checkaccess')->Club($utilisateur);      

        $passForm = $this->createForm(new PassUtilisateurFormType());
        
        return $this->render('Appli\User\pass.html.twig', array(
            'entity'      => $utilisateur,
            'pass_form'   => $passForm->createView(),
        ));
    } 
    /**
     * Updates the password of an existing Utilisateur entity.
     * 
     * @Route("/updpass",name="user_updpass")
     */
    public function updpassAction(Request $request)
    {
        // traduction
        $t = $this->get('translator');
        // entity manager
        $em = $this->getDoctrine()->getManager();
        // Récupération des infos dans le request (POST)
        $id = $request->request->get('id');
        // utilisation du usermanager de FOSUserBundle
        $userManager = $this->container->get('fos_user.user_manager');
        $utilisateur = $userManager->findUserBy(array('id' => $id));
        
        $this->container->get('app.checkaccess')->Club($utilisateur);
        
        if (!$utilisateur) {
            throw $this->createNotFoundException($t->trans('form.erreur.objet_introuvable'));
        }

        $passForm = $this->createForm(new PassUtilisateurFormType());
        $passForm->bind($request);

        if ($passForm->isValid()) {
            var_dump($utilisateur);
            var_dump($passForm->getData()->new);
            $utilisateur->setPlainPassword($passForm->getData()->new);
            $userManager->updateUser($utilisateur);

            
            // message mise à jour réussie
            $this->get('session')->getFlashBag()->add(
                        'success',
                        $t->trans('form.succes.password_chgt')
            );
            
            $url = $this->container->get('router')->generate('user');
            $response = new RedirectResponse($url);
            return $response;

        }
        // message mise à jour ratée
        $this->get('session')->getFlashBag()->add(
            'error',
            'form.erreur.saisie'
        );
        
        return $this->render('Appli\User\pass.html.twig', array(
            'entity'      => $utilisateur,
            'pass_form'   => $passForm->createView(),
        ));
    }
    
     /**
     * Creates a form to delete a Utilisateur entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

