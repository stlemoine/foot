<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.5-dev (doctrine2-annotation) on 2014-12-07 18:21:30.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace FootBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * FootBundle\Entity\Teamcom
 *
 * @ORM\Entity(repositoryClass="TeamcomRepository")
 * @ORM\Table(name="teamcom", indexes={@ORM\Index(name="fk_teamcom_Equipe1_idx", columns={"Equipe_id"})})
 */
class Teamcom
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="`comment`", type="text")
     */
    protected $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $datecrea;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $datemodif;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $note;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Equipe_id;

    /**
     * @ORM\OneToMany(targetEntity="Imgteamcom", mappedBy="teamcom")
     * @ORM\JoinColumn(name="id", referencedColumnName="teamcom_id")
     */
    protected $imgteamcoms;

    /**
     * @ORM\ManyToOne(targetEntity="Equipe", inversedBy="teamcoms")
     * @ORM\JoinColumn(name="Equipe_id", referencedColumnName="id")
     */
    protected $equipe;

    public function __construct()
    {
        $this->imgteamcoms = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \FootBundle\Entity\Teamcom
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
     * Set the value of comment.
     *
     * @param string $comment
     * @return \FootBundle\Entity\Teamcom
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the value of comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of datecrea.
     *
     * @param datetime $datecrea
     * @return \FootBundle\Entity\Teamcom
     */
    public function setDatecrea($datecrea)
    {
        $this->datecrea = $datecrea;

        return $this;
    }

    /**
     * Get the value of datecrea.
     *
     * @return datetime
     */
    public function getDatecrea()
    {
        return $this->datecrea;
    }

    /**
     * Set the value of datemodif.
     *
     * @param datetime $datemodif
     * @return \FootBundle\Entity\Teamcom
     */
    public function setDatemodif($datemodif)
    {
        $this->datemodif = $datemodif;

        return $this;
    }

    /**
     * Get the value of datemodif.
     *
     * @return datetime
     */
    public function getDatemodif()
    {
        return $this->datemodif;
    }

    /**
     * Set the value of note.
     *
     * @param float $note
     * @return \FootBundle\Entity\Teamcom
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get the value of note.
     *
     * @return float
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set the value of Equipe_id.
     *
     * @param integer $Equipe_id
     * @return \FootBundle\Entity\Teamcom
     */
    public function setEquipeId($Equipe_id)
    {
        $this->Equipe_id = $Equipe_id;

        return $this;
    }

    /**
     * Get the value of Equipe_id.
     *
     * @return integer
     */
    public function getEquipeId()
    {
        return $this->Equipe_id;
    }

    /**
     * Add Imgteamcom entity to collection (one to many).
     *
     * @param \FootBundle\Entity\Imgteamcom $imgteamcom
     * @return \FootBundle\Entity\Teamcom
     */
    public function addImgteamcom(Imgteamcom $imgteamcom)
    {
        $this->imgteamcoms[] = $imgteamcom;

        return $this;
    }

    /**
     * Remove Imgteamcom entity from collection (one to many).
     *
     * @param \FootBundle\Entity\Imgteamcom $imgteamcom
     * @return \FootBundle\Entity\Teamcom
     */
    public function removeImgteamcom(Imgteamcom $imgteamcom)
    {
        $this->imgteamcoms->removeElement($imgteamcom);

        return $this;
    }

    /**
     * Get Imgteamcom entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImgteamcoms()
    {
        return $this->imgteamcoms;
    }

    /**
     * Set Equipe entity (many to one).
     *
     * @param \FootBundle\Entity\Equipe $equipe
     * @return \FootBundle\Entity\Teamcom
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

    public function __sleep()
    {
        return array('id', 'comment', 'datecrea', 'datemodif', 'note', 'Equipe_id');
    }
}
