<?php
// src/FootBundle/Controller/SaisonController.php
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

use FootBundle\Entity\Saison;
use FootBundle\Form\SaisonType;


/**
 * Saison controller.
 *
 * @Route("/saison")
 */
class SaisonController extends Controller
{

    /**
     * Lists all Saison entities.
     *
     * @Route("/", name="saison")
     * @Template()
     */
    public function indexAction()
    {
        // traduction
        $t = $this->get('translator');
        
        $em = $this->getDoctrine()->getManager();

        // Liste des Saisons
        $source = new Entity('FootBundle:Saison');
        
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
        $grid->addExport(new SCSVExport('SC-CSV Saison Export','Saison',array(),'UTF-8','ROLE_ADMIN'));
        $grid->addExport(new ExcelExport('Excel Saison Export','Saison',array(),'UTF-8','ROLE_ADMIN'));
        // prefixe pour la traduction
        $grid->setPrefixTitle('grid.');

        // Réglage des filtres
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $grid->getColumn('club.nom')->setFilterable(false);
        }


        // Réglage taille des colonnes

        $grid->setActionsColumnSize(120);
        // Réglage pagination
        $grid->setLimits(array(10, 15, 20, 50));
		
        // Add a typed column with a rendering callback
        $edit = new rowAction($t->trans('Edit'),'saison_edit', false, '_self',array(),'ROLE_ADMIN'); 
        $edit->setRouteParameters(array('id'));
        $grid->addRowAction($edit);

        $show = new rowAction($t->trans('Show'),'saison_show', false, '_self',array(),'ROLE_ADMIN'); 
        $show->setRouteParameters(array('id'));
        $grid->addRowAction($show);	

        // Prépa avant envoi	
        $grid->isReadyForRedirect();


        return $grid->getGridResponse('::Appli\Saison\index.html.twig',
                                        array(  'grid'=>$grid,
                                                'entity'=>$source
                                                )
                                      );	
    }
    /**
     * Creates a new Saison entity.
     *
     * @Route("/create", name="saison_create")
     * @Method("POST")
     * @Template("::Appli\Saison\new.html.twig")
     */
    public function createAction(Request $request)
    {
        // traduction
        $t = $this->get('translator');
        
        $entity = new Saison();
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
            return $this->redirect($this->generateUrl('saison'));

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
     * Creates a form to create a Saison entity.
     *
     * @param Saison $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Saison $entity)
    {
        $form = $this->createForm(new SaisonType($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')), $entity, array(
            'action' => $this->generateUrl('saison_create'),
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
     * Displays a form to create a new Saison entity.
     *
     * @Route("/new", name="saison_new")
     * @Method("GET")
     * @Template("::Appli\Saison\new.html.twig")
     */
    public function newAction()
    {
        $entity = new Saison();
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
     * Finds and displays a Saison entity.
     *
     * @Route("/{id}", name="saison_show")
     * @Method("GET")
     * @Template("::Appli\Saison\show.html.twig")
     */
    public function showAction($id)
    {
        // traduction
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FootBundle:Saison')->find($id);

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
     * Displays a form to edit an existing Saison entity.
     *
     * @Route("/{id}/edit", name="saison_edit")
     * @Method("GET")
     * @Template("::Appli\Saison\edit.html.twig")
     */
    public function editAction($id)
    {
        // traduction
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FootBundle:Saison')->find($id);

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
    * Creates a form to edit a Saison entity.
    *
    * @param Saison $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Saison $entity)
    {
        $form = $this->createForm(new SaisonType($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')), $entity, array(
            'action' => $this->generateUrl('saison_update', array('id' => $entity->getId())),
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
     * Edits an existing Saison entity.
     *
     * @Route("/{id}", name="saison_update")
     * @Method("PUT")
     * @Template("::Appli\Saison\edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // traduction
        $t = $this->get('translator');
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FootBundle:Saison')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($t->trans('form.erreur.objet_introuvable'));
        }
        
        $this->container->get('app.checkaccess')->Client($entity);

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

            return $this->redirect($this->generateUrl('saison'));
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
     * Deletes a Saison entity.
     *
     * @Route("/{id}", name="saison_delete")
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
            $entity = $em->getRepository('FootBundle:Saison')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException($t->trans('form.erreur.objet_introuvable'));
            }
            
            $this->container->get('app.checkaccess')->Club($entity);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('saison'));
    }

    /**
     * Creates a form to delete a Saison entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('saison_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

