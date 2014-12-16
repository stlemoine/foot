<?php
// src/FootBundle/Entity/User.php

namespace FootBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserRepository")
 * @UniqueEntity(fields="usernameCanonical", errorPath="username", message="fos_user.username.already_used")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="email", column=@ORM\Column(type="string", name="email", length=255, unique=false, nullable=true)),
 *      @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false, nullable=true))
 * })
 * @GRID\Source(columns="id, prenom, nom, tel, username, email, enabled, lastLogin, roles ,club.nom, imageName")
 * @Vich\Uploadable
 *
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false, filterable=false)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="Please enter your firstname.", groups={"Registration", "Profile"})
     * @Assert\Length(
           min = "2",
           max = "50",
           minMessage = "Votre prenom doit faire au moins {{ limit }} caractères",
           maxMessage = "Votre prenom ne peut pas être plus long que {{ limit }} caractères"
      )
     * @GRID\Column(operators = {"like","rlike","llike"})
     * 
     */
    protected $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
           min = "2",
           max = "50",
           minMessage = "Votre nom doit faire au moins {{ limit }} caractères",
           maxMessage = "Votre nom ne peut pas être plus long que {{ limit }} caractères"
      )
     * @GRID\Column(operators = {"like","rlike","llike"})
     * 
     */
    protected $nom;
    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="user_pict", fileNameProperty="imageName")
     *
     * @var File $photo
     * @GRID\Column(filterable=false)
     */
    public $photo;
    
     /**
      * @ORM\Column(type="datetime", nullable=true)
      * 
      * @var \Datetime $updatedAt
      * 
      */
     protected $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, name="image_name", options={"default" = "manquephoto.jpg"}, nullable=true)
     *
     * @var string $imageName
     * @GRID\Column(filterable=false)
     */
    protected $imageName;
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20 , nullable=true)
     *
     * @GRID\Column(operators = {"like","rlike","llike"})
     * 
     */
    protected $tel;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dob;

    /**
     * @ORM\OneToMany(targetEntity="MatchHasButeur", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $matchHasButeurs;

    /**
     * @ORM\OneToMany(targetEntity="MatchHasUser", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $matchHasUsers;

    /**
     * @ORM\OneToMany(targetEntity="Matchcom", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $matchcoms;

    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="users")
     * @ORM\JoinColumn(name="Club_id", referencedColumnName="id")
     * @GRID\Column(field="club.nom", operators = {"like","rlike","llike"})
     * 
     */
    protected $club;

    /**
     * @ORM\ManyToMany(targetEntity="Equipe", mappedBy="users")
     */
    protected $equipes;

    public function __construct()
    {
        parent::__construct();
        $this->matchHasButeurs = new ArrayCollection();
        $this->matchHasUsers = new ArrayCollection();
        $this->matchcoms = new ArrayCollection();
        $this->equipes = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \FootBundle\Entity\User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of prenom.
     *
     * @param string $prenom
     * @return \FootBundle\Entity\User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of prenom.
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of nom.
     *
     * @param string $nom
     * @return \FootBundle\Entity\User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return User
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
        
        return $this;
    }  
    
    /**
     * Get imageName
     *
     * @return string 
     */
    public function getImageName()
    {
        return $this->imageName;
    }
    /**
     * Set the value of photo.
     *
     * @param string $photo
     * @return \FootBundle\Entity\User
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        
        if ($this->photo) {
            $this->updatedAt = new \DateTime('now');
        }    
        return $this;
    }

    /**
     * Get the value of photo.
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
    /**
     * Set the value of tel.
     *
     * @param string $tel
     * @return \FootBundle\Entity\User
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get the value of tel.
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set the value of Club_id.
     *
     * @param integer $Club_id
     * @return \FootBundle\Entity\User
     */
    public function setClubId($Club_id)
    {
        $this->Club_id = $Club_id;

        return $this;
    }

    /**
     * Get the value of Club_id.
     *
     * @return integer
     */
    public function getClubId()
    {
        return $this->Club_id;
    }

    /**
     * Set the value of dob.
     *
     * @param datetime $dob
     * @return \FootBundle\Entity\User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get the value of dob.
     *
     * @return datetime
     */
    public function getDob()
    {
        return $this->dob;
    }
    /**
     * Add MatchHasButeur entity to collection (one to many).
     *
     * @param \FootBundle\Entity\MatchHasButeur $matchHasButeur
     * @return \FootBundle\Entity\User
     */
    public function addMatchHasButeur(MatchHasButeur $matchHasButeur)
    {
        $this->matchHasButeurs[] = $matchHasButeur;

        return $this;
    }

    /**
     * Remove MatchHasButeur entity from collection (one to many).
     *
     * @param \FootBundle\Entity\MatchHasButeur $matchHasButeur
     * @return \FootBundle\Entity\User
     */
    public function removeMatchHasButeur(MatchHasButeur $matchHasButeur)
    {
        $this->matchHasButeurs->removeElement($matchHasButeur);

        return $this;
    }

    /**
     * Get MatchHasButeur entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchHasButeurs()
    {
        return $this->matchHasButeurs;
    }

    /**
     * Add MatchHasUser entity to collection (one to many).
     *
     * @param \FootBundle\Entity\MatchHasUser $matchHasUser
     * @return \FootBundle\Entity\User
     */
    public function addMatchHasUser(MatchHasUser $matchHasUser)
    {
        $this->matchHasUsers[] = $matchHasUser;

        return $this;
    }

    /**
     * Remove MatchHasUser entity from collection (one to many).
     *
     * @param \FootBundle\Entity\MatchHasUser $matchHasUser
     * @return \FootBundle\Entity\User
     */
    public function removeMatchHasUser(MatchHasUser $matchHasUser)
    {
        $this->matchHasUsers->removeElement($matchHasUser);

        return $this;
    }

    /**
     * Get MatchHasUser entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchHasUsers()
    {
        return $this->matchHasUsers;
    }

    /**
     * Add Matchcom entity to collection (one to many).
     *
     * @param \FootBundle\Entity\Matchcom $matchcom
     * @return \FootBundle\Entity\User
     */
    public function addMatchcom(Matchcom $matchcom)
    {
        $this->matchcoms[] = $matchcom;

        return $this;
    }

    /**
     * Remove Matchcom entity from collection (one to many).
     *
     * @param \FootBundle\Entity\Matchcom $matchcom
     * @return \FootBundle\Entity\User
     */
    public function removeMatchcom(Matchcom $matchcom)
    {
        $this->matchcoms->removeElement($matchcom);

        return $this;
    }

    /**
     * Get Matchcom entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchcoms()
    {
        return $this->matchcoms;
    }

    /**
     * Set Club entity (many to one).
     *
     * @param \FootBundle\Entity\Club $club
     * @return \FootBundle\Entity\User
     */
    public function setClub(Club $club = null)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * Get Club entity (many to one).
     *
     * @return \FootBundle\Entity\Club
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * Add Equipe entity to collection.
     *
     * @param \FootBundle\Entity\Equipe $equipe
     * @return \FootBundle\Entity\User
     */
    public function addEquipe(Equipe $equipe)
    {
        $this->equipes[] = $equipe;

        return $this;
    }

    /**
     * Remove Equipe entity from collection.
     *
     * @param \FootBundle\Entity\Equipe $equipe
     * @return \FootBundle\Entity\User
     */
    public function removeEquipe(Equipe $equipe)
    {
        $this->equipes->removeElement($equipe);

        return $this;
    }

    /**
     * Get Equipe entity collection.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipes()
    {
        return $this->equipes;
    }

    public function __sleep()
    {
        return array('id', 'prenom', 'nom', 'tel', 'Club_id', 'dob', 'photo');
    }
    public function __toString()
    {
        return $this->prenom.' '.$this->nom;
    }
}
