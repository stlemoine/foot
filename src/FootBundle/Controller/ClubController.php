<?php
// src/FootBundle/Controller/ClubController.php
namespace FootBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Export\SCSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;

use FootBundle\Entity\Club;
use FootBundle\Form\ClubType;


/**
 * Club controller.
 *
 * @Route("/club")
 */
class ClubController extends Controller
{

    /**
     * Lists all Club entities.
     *
     * @Route("/", name="club")
     * @Template()
     */
    public function indexAction()
    {
        // traduction
        $t = $this->get('translator');
        
        $em = $this->getDoctrine()->getManager();

        // Liste des Clubs
        $source = new Entity('FootBundle:Club');
        
        // Ne selectionner que les entités relatives à un client
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $club	= $this->container->get('security.context')->getToken()->getUser()->getClub()->getId();
            $source->manipulateQuery(
                    function ($query) use ($club)
                    {
                        $query	->andWhere('_a.club = '.$club)
                                        ;
                    }
            );
        }
        
     	// Get a grid instance
        $grid = $this->get('grid');

     	// Attach the source to the grid
        $grid->setSource($source);
        $grid->addExport(new SCSVExport('SC-CSV Export'));
        $grid->addExport(new ExcelExport('Excel Export'));
        // prefixe pour la traduction
        $grid->setPrefixTitle('grid.');

        // Réglage des filtres
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $grid->getColumn('club.nom')->setFilterable(false);
        }
        $source->manipulateRow(
            function ($row) 
            { 
                $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
                $url = $helper->asset($row->getEntity(), 'club_logo');
                // Change the output of the new column with your own code at entity.
                $row->setField('imageName', $url);
                
                return $row;
            }
        );
        // Réglage taille des colonnes

        $grid->setActionsColumnSize(120);
        // Réglage pagination
        $grid->setLimits(array(10, 15, 20, 50));
		
        // Add a typed column with a rendering callback
        $edit = new rowAction($t->trans('Edit'),'club_edit', false, '_self'); 
        $edit->setRouteParameters(array('id'));
        $grid->addRowAction($edit);

        $show = new rowAction($t->trans('Show'),'club_show', false, '_self');
        $show->setRouteParameters(array('id'));
        $grid->addRowAction($show);	

        // Prépa avant envoi	
        $grid->isReadyForRedirect();


        return $grid->getGridResponse('::Appli\Club\index.html.twig',
                                        array(  'grid'=>$grid,
                                                'entity'=>$source
                                                )
                                      );	
    }
    /**
     * Creates a new Club entity.
     *
     * @Route("/create", name="club_create")
     * @Method("POST")
     * @Template("::Appli\Club\new.html.twig")
     */
    public function createAction(Request $request)
    {
        // traduction
        $t = $this->get('translator');
        
        $entity = new Club();
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $club	= $this->container->get('security.context')->getToken()->getUser()->getClub();
            $entity->setClub($club);
        }
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            // message succès création 
            $this->get('session')->getFlashBag()->add(
                'success',
                $t->trans('form.success.creation_ok')
            );
            
            // retour à la grille
            return $this->redirect($this->generateUrl('club'));

        }

        
        // message mise à jour ratée
        $this->get('session')->getFlashBag()->add(
            'error',
            $t->trans('form.erreur.saisie')
        );
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Club entity.
     *
     * @param Club $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Club $entity)
    {
        $form = $this->createForm(new ClubType($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')), $entity, array(
            'action' => $this->generateUrl('club_create'),
            'method' => 'POST',
            'attr'      =>  array(
                                'class'     => 'form-horizontal'
                                )
        ));

        $form->add('submit', 'submit', array('label' => 'create_a_new',
                                                        'attr'  =>array(
                                                            'class'=>'btn btn-primary btn-xs'
                                                        )
                                                    ));

        return $form;
    }

    /**
     * Displays a form to create a new Club entity.
     *
     * @Route("/new", name="club_new")
     * @Method("GET")
     * @Template("::Appli\Club\new.html.twig")
     */
    public function newAction()
    {
        $entity = new Club();
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $client	= $this->container->get('security.context')->getToken()->getUser()->getClub();
            $entity->setClub($club);
        }
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Club entity.
     *
     * @Route("/{id}", name="club_show")
     * @Method("GET")
     * @Template("::Appli\Club\show.html.twig")
     */
    public function showAction($id)
    {
        // traduction
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FootBundle:Club')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($t->trans('form.erreur.objet_introuvable'));
        }
        
        $this->container->get('app.checkaccess')->Club($entity);
        

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Club entity.
     *
     * @Route("/{id}/edit", name="club_edit")
     * @Method("GET")
     * @Template("::Appli\Club\edit.html.twig")
     */
    public function editAction($id)
    {
        // traduction
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FootBundle:Club')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($t->trans('form.erreur.objet_introuvable'));
        }
        
        $this->container->get('app.checkaccess')->Club($entity);

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Club entity.
    *
    * @param Club $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Club $entity)
    {
        $form = $this->createForm(new ClubType($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')), $entity, array(
            'action' => $this->generateUrl('club_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr'      =>  array(
                                'class'     => 'form-horizontal'
                                )
        ));

        $form->add('submit', 'submit', array(   'label' => 'update',
                                                'attr'  =>array(
                                                    'class'=>'btn btn-primary btn-xs'
                                                    )
                                                ));

        return $form;
    }
    /**
     * Edits an existing Club entity.
     *
     * @Route("/{id}", name="club_update")
     * @Method("PUT")
     * @Template("::Appli\Club\edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // traduction
        $t = $this->get('translator');
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FootBundle:Club')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($t->trans('form.erreur.objet_introuvable'));
        }
        
        $this->container->get('app.checkaccess')->Club($entity);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            // message mise à jour réussie
            $this->get('session')->getFlashBag()->add(
                        'success',
                        $t->trans('form.success.modif_ok')
            );

            return $this->redirect($this->generateUrl('club'));
        }

        // message mise à jour ratée
        $this->get('session')->getFlashBag()->add(
            'error',
            $t->trans('form.erreur.saisie')
        );
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Club entity.
     *
     * @Route("/{id}", name="club_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        // traduction
        $t = $this->get('translator');
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FootBundle:Club')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException($t->trans('form.erreur.objet_introuvable'));
            }
            
            $this->container->get('app.checkaccess')->Club($entity);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('club'));
    }

    /**
     * Creates a form to delete a Club entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('club_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

