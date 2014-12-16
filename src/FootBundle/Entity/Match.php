<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.5-dev (doctrine2-annotation) on 2014-12-07 18:21:29.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace FootBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * FootBundle\Entity\Match
 *
 * @ORM\Entity(repositoryClass="MatchRepository")
 * @ORM\Table(name="`match`", indexes={@ORM\Index(name="fk_match_Equipe1_idx", columns={"Equipe_id"}), @ORM\Index(name="fk_match_Club1_idx", columns={"Club_id"}), @ORM\Index(name="fk_match_typematch1_idx", columns={"typematch_id"})})
 * @GRID\Source(columns="	id, 
                            date, 
                            domicile,
                            equipe.nom,
                            club.nom,
                            typematch.type,
                            bp,
                            bc
                            ")
 */
class Match
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false, filterable=false)
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     * @GRID\Column(    type="datetime", 
                        format="d-m-Y H:i",  
                        filter="select",
                        operators = {"like", "eq", "lt", "lte", "gt", "gte", "btw", "btwe"}
                    )
     */
    protected $date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $domicile;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $img;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $bp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $bc;

    /**
     * @ORM\OneToMany(targetEntity="MatchHasButeur", mappedBy="match", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="match_id")
     */
    protected $matchHasButeurs;

    /**
     * @ORM\OneToMany(targetEntity="MatchHasUser", mappedBy="match", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="match_id")
     */
    protected $matchHasUsers;

    /**
     * @ORM\OneToMany(targetEntity="Matchcom", mappedBy="match")
     * @ORM\JoinColumn(name="id", referencedColumnName="match_id")
     */
    protected $matchcoms;

    /**
     * @ORM\ManyToOne(targetEntity="Equipe", inversedBy="matches")
     * @ORM\JoinColumn(name="Equipe_id", referencedColumnName="id")
     * @GRID\Column(field="equipe.nom", filter="select", operators = {"like","rlike","llike"})
     */
    protected $equipe;

    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="matches")
     * @ORM\JoinColumn(name="Club_id", referencedColumnName="id")
     * @GRID\Column(field="club.nom", operators = {"like","rlike","llike"})
     */
    protected $club;

    /**
     * @ORM\ManyToOne(targetEntity="Typematch", inversedBy="matches")
     * @ORM\JoinColumn(name="typematch_id", referencedColumnName="id")
     * @GRID\Column(field="typematch.type", filter="select", operators = {"like","rlike","llike"})
     */
    protected $typematch;

    public function __construct()
    {
        $this->matchHasButeurs = new ArrayCollection();
        $this->matchHasUsers = new ArrayCollection();
        $this->matchcoms = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \FootBundle\Entity\Match
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
     * Set the value of date.
     *
     * @param datetime $date
     * @return \FootBundle\Entity\Match
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of date.
     *
     * @return datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of domicile.
     *
     * @param boolean $domicile
     * @return \FootBundle\Entity\Match
     */
    public function setDomicile($domicile)
    {
        $this->domicile = $domicile;

        return $this;
    }

    /**
     * Get the value of domicile.
     *
     * @return boolean
     */
    public function getDomicile()
    {
        return $this->domicile;
    }

    /**
     * Set the value of img.
     *
     * @param string $img
     * @return \FootBundle\Entity\Match
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get the value of img.
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set the value of bp.
     *
     * @param integer $bp
     * @return \FootBundle\Entity\Match
     */
    public function setBp($bp)
    {
        $this->bp = $bp;

        return $this;
    }

    /**
     * Get the value of bp.
     *
     * @return integer
     */
    public function getBp()
    {
        return $this->bp;
    }

    /**
     * Set the value of bc.
     *
     * @param integer $bc
     * @return \FootBundle\Entity\Match
     */
    public function setBc($bc)
    {
        $this->bc = $bc;

        return $this;
    }

    /**
     * Get the value of bc.
     *
     * @return integer
     */
    public function getBc()
    {
        return $this->bc;
    }

    /**
     * Add MatchHasButeur entity to collection (one to many).
     *
     * @param \FootBundle\Entity\MatchHasButeur $matchHasButeur
     * @return \FootBundle\Entity\Match
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
     * @return \FootBundle\Entity\Match
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
     * @return \FootBundle\Entity\Match
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
     * @return \FootBundle\Entity\Match
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
     * @return \FootBundle\Entity\Match
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
     * @return \FootBundle\Entity\Match
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
     * Set Equipe entity (many to one).
     *
     * @param \FootBundle\Entity\Equipe $equipe
     * @return \FootBundle\Entity\Match
     */
    public function setEquipe(Equipe $equipe = null)
    {
        $this->equipe = $equipe;

        return $this;
    }

    /**
     * Get Equipe entity (many to one).
     *
     * @return \FootBundle\Entity\Equipe
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * Set Club entity (many to one).
     *
     * @param \FootBundle\Entity\Club $club
     * @return \FootBundle\Entity\Match
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
     * Set Typematch entity (many to one).
     *
     * @param \FootBundle\Entity\Typematch $typematch
     * @return \FootBundle\Entity\Match
     */
    public function setTypematch(Typematch $typematch = null)
    {
        $this->typematch = $typematch;

        return $this;
    }

    /**
     * Get Typematch entity (many to one).
     *
     * @return \FootBundle\Entity\Typematch
     */
    public function getTypematch()
    {
        return $this->typematch;
    }

    public function __sleep()
    {
        return array('id', 'date', 'Equipe_id', 'Club_id', 'domicile', 'img', 'bp', 'bc', 'typematch_id');
    }
    public function __toString()
    {
        if($this->domicile){
            $dom = 'domicile';
        }else{
            $dom = 'extérieur';
        }
        return $this->getClub()->getNom().'|'.$this->getTypematch()->getType().'|'.$dom;
    }
}
