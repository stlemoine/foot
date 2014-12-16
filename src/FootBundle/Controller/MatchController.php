<?php
// src/FootBundle/Controller/MatchController.php
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

use FootBundle\Entity\Match;
use FootBundle\Form\MatchType;


/**
 * Match controller.
 *
 * @Route("/match")
 */
class MatchController extends Controller
{

    /**
     * Lists all Match entities.
     *
     * @Route("/", name="match")
     * @Template()
     */
    public function indexAction()
    {
        // traduction
        $t = $this->get('translator');
        
        $em = $this->getDoctrine()->getManager();

        // Liste des Matchs
        $source = new Entity('FootBundle:Match');
        
        // Ne selectionner que les entités relatives à un club
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $club	= $this->container->get('security.context')->getToken()->getUser()->getClub()->getId();
            $source->manipulateQuery(
                    function ($query) use ($club)
                    {
                        $query	->join('_equipe.club','_clubdom')
                                ->andWhere('_clubdom.id = '.$club);
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
                $today = new \Datetime('now');
            
                if($row->getField('date') <= $today){       
                
                    if ($row->getField('bp') > $row->getField('bc')) { 
                        $row->setColor('#00ff00');  // set background-color as inline style
                    }elseif ($row->getField('bp') < $row->getField('bc')) {
                        $row->setColor('#FF0000');  // set background-color as inline style
                    }else{
                        $row->setColor('#FFFF00');  // set background-color as inline style
                    }
                
                }

                // Don't show the row if the price is greater than 10
                if ($row->getField('price')>10) {
                    return null;
                }

                return $row;
            }
        );

        // Réglage taille des colonnes

        $grid->setActionsColumnSize(120);
        // Réglage pagination
        $grid->setLimits(array(10, 15, 20, 50));
        $grid->setPersistence(true);
		
        // Add a typed column with a rendering callback
        $edit = new rowAction($t->trans('Edit'),'match_edit', false, '_self',array(),'ROLE_COACH'); 
        $edit->setRouteParameters(array('id'));
        $grid->addRowAction($edit);

        $show = new rowAction($t->trans('Show'),'match_show', false, '_self');
        $show->setRouteParameters(array('id'));
        $grid->addRowAction($show);	

        // Prépa avant envoi	
        $grid->isReadyForRedirect();


        return $grid->getGridResponse('::Appli\Match\index.html.twig',
                                        array(  'grid'=>$grid,
                                                'entity'=>$source
                                                )
                                      );	
    }
    /**
     * Creates a new Match entity.
     *
     * @Route("/create", name="match_create")
     * @Method("POST")
     * @Template("::Appli\Match\new.html.twig")
     */
    public function createAction(Request $request)
    {
        // traduction
        $t = $this->get('translator');
        
        $entity = new Match();
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
            return $this->redirect($this->generateUrl('match'));

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
     * Creates a form to create a Match entity.
     *
     * @param Match $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Match $entity)
    {
        $form = $this->createForm(new MatchType($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD'),$this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('match_create'),
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
     * Displays a form to create a new Match entity.
     *
     * @Route("/new", name="match_new")
     * @Method("GET")
     * @Template("::Appli\Match\new.html.twig")
     */
    public function newAction()
    {
        $entity = new Match();
        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD')){
            $club	= $this->container->get('security.context')->getToken()->getUser()->getClub();
            $entity->setClub($club);
        }
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Match entity.
     *
     * @Route("/{id}", name="match_show")
     * @Method("GET")
     * @Template("::Appli\Match\show.html.twig")
     */
    public function showAction($id)
    {
        // traduction
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FootBundle:Match')->find($id);

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
     * Displays a form to edit an existing Match entity.
     *
     * @Route("/{id}/edit", name="match_edit")
     * @Method("GET")
     * @Template("::Appli\Match\edit.html.twig")
     */
    public function editAction($id)
    {
        // traduction
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FootBundle:Match')->find($id);

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
    * Creates a form to edit a Match entity.
    *
    * @param Match $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Match $entity)
    {
        $form = $this->createForm(new MatchType($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN_SLAD'),$this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('match_update', array('id' => $entity->getId())),
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
     * Edits an existing Match entity.
     *
     * @Route("/{id}", name="match_update")
     * @Method("PUT")
     * @Template("::Appli\Match\edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // traduction
        $t = $this->get('translator');
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FootBundle:Match')->find($id);

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

            return $this->redirect($this->generateUrl('match'));
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
     * Deletes a Match entity.
     *
     * @Route("/{id}", name="match_delete")
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
            $entity = $em->getRepository('FootBundle:Match')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException($t->trans('form.erreur.objet_introuvable'));
            }
            
            $this->container->get('app.checkaccess')->Club($entity);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('match'));
    }

    /**
     * Creates a form to delete a Match entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('match_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

