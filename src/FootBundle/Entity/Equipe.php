<?php
namespace FootBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * FootBundle\Entity\Equipe
 *
 * @ORM\Entity(repositoryClass="EquipeRepository")
 * @ORM\Table(name="equipe", indexes={@ORM\Index(name="fk_Equipe_Club1_idx", columns={"Club_id"}), @ORM\Index(name="fk_Equipe_saison1_idx", columns={"saison_id"}), @ORM\Index(name="fk_Equipe_scoring1_idx", columns={"scoring_id"})})
 * @GRID\Source(columns="id,nom,club.nom,saison.debut,saison.fin,imageName")
 * @Vich\Uploadable
 * 
 */
class Equipe
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @GRID\Column(filterable=false,visible=false)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=45)
     * 
     */
    protected $nom;

    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="club_image", fileNameProperty="imageName")
     *
     * @var File $photo
     * @GRID\Column(filterable=false)
     */
    public $photo;
    
     /**
      * @ORM\Column(type="datetime")
      * 
      * @var \Datetime $updatedAt
      * 
      */
     protected $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, name="image_name", options={"default" = "manquephoto.jpg"})
     *
     * @var string $imageName
     * @GRID\Column(filterable=false)
     */
    protected $imageName;

    /**
     * @ORM\OneToMany(targetEntity="Match", mappedBy="equipe")
     * @ORM\JoinColumn(name="id", referencedColumnName="Equipe_id")
     * 
     *
     */
    protected $matches;

    /**
     * @ORM\OneToMany(targetEntity="Teamcom", mappedBy="equipe")
     * @ORM\JoinColumn(name="id", referencedColumnName="Equipe_id")
     */
    protected $teamcoms;

    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="equipes")
     * @ORM\JoinColumn(name="Club_id", referencedColumnName="id")
     * @GRID\Column(field="club.nom", operators = {"like","rlike","llike"})
     */
    protected $club;

    /**
     * @ORM\ManyToOne(targetEntity="Saison", inversedBy="equipes")
     * @ORM\JoinColumn(name="saison_id", referencedColumnName="id")
     * @GRID\Column(field="saison.debut", type="date", format="Y",filter="select",operators = {"eq","like","rlike","llike"})
     * @GRID\Column(field="saison.fin", type="date", format="Y", filterable=false, operators = {"eq","like","rlike","llike"})
     */
    protected $saison;

    /**
     * @ORM\ManyToOne(targetEntity="Scoring", inversedBy="equipes")
     * @ORM\JoinColumn(name="scoring_id", referencedColumnName="id")
     */
    protected $scoring;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="equipes", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="equipe_has_membre",
     *     joinColumns={@ORM\JoinColumn(name="Equipe_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $users;

    public function __construct()
    {
        $this->matches = new ArrayCollection();
        $this->teamcoms = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \FootBundle\Entity\Equipe
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
     * Set the value of nom.
     *
     * @param string $nom
     * @return \FootBundle\Entity\Equipe
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
     * @return Equipe
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
     * @return \FootBundle\Entity\Equipe
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
     * Add Match entity to collection (one to many).
     *
     * @param \FootBundle\Entity\Match $match
     * @return \FootBundle\Entity\Equipe
     */
    public function addMatch(Match $match)
    {
        $this->matches[] = $match;

        return $this;
    }

    /**
     * Remove Match entity from collection (one to many).
     *
     * @param \FootBundle\Entity\Match $match
     * @return \FootBundle\Entity\Equipe
     */
    public function removeMatch(Match $match)
    {
        $this->matches->removeElement($match);

        return $this;
    }

    /**
     * Get Match entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Add Teamcom entity to collection (one to many).
     *
     * @param \FootBundle\Entity\Teamcom $teamcom
     * @return \FootBundle\Entity\Equipe
     */
    public function addTeamcom(Teamcom $teamcom)
    {
        $this->teamcoms[] = $teamcom;

        return $this;
    }

    /**
     * Remove Teamcom entity from collection (one to many).
     *
     * @param \FootBundle\Entity\Teamcom $teamcom
     * @return \FootBundle\Entity\Equipe
     */
    public function removeTeamcom(Teamcom $teamcom)
    {
        $this->teamcoms->removeElement($teamcom);

        return $this;
    }

    /**
     * Get Teamcom entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeamcoms()
    {
        return $this->teamcoms;
    }

    /**
     * Set Club entity (many to one).
     *
     * @param \FootBundle\Entity\Club $club
     * @return \FootBundle\Entity\Equipe
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
     * Set Saison entity (many to one).
     *
     * @param \FootBundle\Entity\Saison $saison
     * @return \FootBundle\Entity\Equipe
     */
    public function setSaison(Saison $saison = null)
    {
        $this->saison = $saison;

        return $this;
    }

    /**
     * Get Saison entity (many to one).
     *
     * @return \FootBundle\Entity\Saison
     */
    public function getSaison()
    {
        return $this->saison;
    }

    /**
     * Set Scoring entity (many to one).
     *
     * @param \FootBundle\Entity\Scoring $scoring
     * @return \FootBundle\Entity\Equipe
     */
    public function setScoring(Scoring $scoring = null)
    {
        $this->scoring = $scoring;

        return $this;
    }

    /**
     * Get Scoring entity (many to one).
     *
     * @return \FootBundle\Entity\Scoring
     */
    public function getScoring()
    {
        return $this->scoring;
    }

    /**
     * Add User entity to collection.
     *
     * @param \FootBundle\Entity\User $user
     * @return \FootBundle\Entity\Equipe
     */
    public function addUser(User $user)
    {
        $user->addEquipe($this);
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove User entity from collection.
     *
     * @param \FootBundle\Entity\User $user
     * @return \FootBundle\Entity\Equipe
     */
    public function removeUser(User $user)
    {
        $user->removeEquipe($this);
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * Get User entity collection.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
    public function __toString()
    {
        return $this->nom;
    }
    
}
