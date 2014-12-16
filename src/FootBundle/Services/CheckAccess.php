<?php

namespace FootBundle\Services;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CheckAccess{
       
    private $t;
    private $s;
    // récip des arguments passés au service
    public function __construct($s,$t) {
        $this->t = $t;
        $this->secu = $s;
    }
    
    public function Club($entity)
    {
        // Récupération du user connecté
        $user = $this->secu->getToken()->getUser();
        if( (!$this->secu->isGranted('ROLE_SUPER_ADMIN_SLAD')) 
            AND 
            (!$this->secu->isGranted('ROLE_PLAYER')
                AND
            $user->getClub() !== $entity->getClub())
                ){
            throw new AccessDeniedException($this->t->trans('form.erreur.access_denied'));
        }   
    }
   
}