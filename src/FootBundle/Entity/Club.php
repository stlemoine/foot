<?php
namespace FootBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * FootBundle\Entity\Club
 *
 * @ORM\Entity(repositoryClass="ClubRepository")
 * @ORM\Table(name="club")
 * @GRID\Source(columns="	id, 
                            nom,
                            tel,
                            adresse,
                            ville,
                            imageName
                            ")
 * @Vich\Uploadable
 */
class Club
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false, filterable=false)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @GRID\Column(operators = {"like","rlike","llike"})
     */
    protected $nom;
    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="club_logo", fileNameProperty="imageName")
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
     * @ORM\Column(type="string", length=45, nullable=true)
     * @GRID\Column(operators = {"like","rlike","llike"})
     */
    protected $adresse;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @GRID\Column(operators = {"like","rlike","llike"})
     */
    protected $cp;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @GRID\Column(operators = {"like","rlike","llike"})
     */
    protected $ville;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $stade;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $tel;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $site;
    
    /**
     * @ORM\OneToMany(targetEntity="Equipe", mappedBy="club")
     * @ORM\JoinColumn(name="id", referencedColumnName="Club_id")
     */
    protected $equipes;

    /**
     * @ORM\OneToMany(targetEntity="Match", mappedBy="club")
     * @ORM\JoinColumn(name="id", referencedColumnName="Club_id")
     */
    protected $matches;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="club")
     * @ORM\JoinColumn(name="id", referencedColumnName="Club_id")
     */
    protected $users;

    public function __construct()
    {
        $this->equipes = new ArrayCollection();
        $this->matches = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \FootBundle\Entity\Club
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
     * @return \FootBundle\Entity\Club
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
     * @return Club
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
     * @return \FootBundle\Entity\Club
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
     * Set the value of adresse.
     *
     * @param string $adresse
     * @return \FootBundle\Entity\Club
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get the value of adresse.
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set the value of cp.
     *
     * @param string $cp
     * @return \FootBundle\Entity\Club
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get the value of cp.
     *
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set the value of ville.
     *
     * @param string $ville
     * @return \FootBundle\Entity\Club
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get the value of ville.
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set the value of stade.
     *
     * @param string $stade
     * @return \FootBundle\Entity\Club
     */
    public function setStade($stade)
    {
        $this->stade = $stade;

        return $this;
    }

    /**
     * Get the value of stade.
     *
     * @return string
     */
    public function getStade()
    {
        return $this->stade;
    }

    /**
     * Set the value of tel.
     *
     * @param string $tel
     * @return \FootBundle\Entity\Club
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
     * Set the value of site.
     *
     * @param string $site
     * @return \FootBundle\Entity\Club
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get the value of site.
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Add Equipe entity to collection (one to many).
     *
     * @param \FootBundle\Entity\Equipe $equipe
     * @return \FootBundle\Entity\Club
     */
    public function addEquipe(Equipe $equipe)
    {
        $this->equipes[] = $equipe;

        return $this;
    }

    /**
     * Remove Equipe entity from collection (one to many).
     *
     * @param \FootBundle\Entity\Equipe $equipe
     * @return \FootBundle\Entity\Club
     */
    public function removeEquipe(Equipe $equipe)
    {
        $this->equipes->removeElement($equipe);

        return $this;
    }

    /**
     * Get Equipe entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipes()
    {
        return $this->equipes;
    }

    /**
     * Add Match entity to collection (one to many).
     *
     * @param \FootBundle\Entity\Match $match
     * @return \FootBundle\Entity\Club
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
     * @return \FootBundle\Entity\Club
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
     * Add User entity to collection (one to many).
     *
     * @param \FootBundle\Entity\User $user
     * @return \FootBundle\Entity\Club
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove User entity from collection (one to many).
     *
     * @param \FootBundle\Entity\User $user
     * @return \FootBundle\Entity\Club
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * Get User entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function __sleep()
    {
        return array('id', 'nom');
    }
    public function __toString()
    {
        return $this->nom;
    }
}
