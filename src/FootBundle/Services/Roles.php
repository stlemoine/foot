<?php

namespace FootBundle\Services;

class Roles{
       
    private $roles;
    private $security;
    // récip des arguments passés au service
    public function __construct($hierarchy = array(),$security) {
        $this->roles = $this->setRoles($hierarchy);
        $this->security = $security;
    }
    // Construction du tableau des roles disponibles directement utilisable dans le champ choice d'un formulaire
    public function setRoles($hierarchy = array()){
        $roles = array();
        //var_dump($hierarchy);
        foreach ($hierarchy as $key => $row) {
            $roles[$key] = $key;
            foreach ($row as $key => $haystack) {
                $roles[$haystack] = $haystack;
            }
        }
        return $roles;
    }
    // Envoi du tableau contenant les roles disponibles
    public function getRoles(){
        return $this->roles;
    }
    //Renvoie le role du user connecté sous forme d'une chaine de caratères épurée
    public function getRoleuser(){
        $level = $this->security->getToken()->getUser()->getRoles();
        $niveaux = explode('_', $level[0]);
        $niveau = '';
        foreach ($niveaux as $key => $value) {
            if($key>0){
                $niveau .= $value;
                if($key < count($niveaux)-1){
                    $niveau .= '_';
                }
            }
            
        }
        return $niveau;
    }
   
}

