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
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * FootBundle\Entity\Scoring
 *
 * @ORM\Entity(repositoryClass="ScoringRepository")
 * @ORM\Table(name="scoring")
 */
class Scoring
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false, filterable=false)
     */
    protected $id;

    /**
     * @ORM\Column(name="`g`", type="integer", nullable=true)
     */
    protected $g;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $n;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $p;

    /**
     * @ORM\OneToMany(targetEntity="Equipe", mappedBy="scoring")
     * @ORM\JoinColumn(name="id", referencedColumnName="scoring_id")
     */
    protected $equipes;

    public function __construct()
    {
        $this->equipes = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \FootBundle\Entity\Scoring
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
     * Set the value of g.
     *
     * @param integer $g
     * @return \FootBundle\Entity\Scoring
     */
    public function setG($g)
    {
        $this->g = $g;

        return $this;
    }

    /**
     * Get the value of g.
     *
     * @return integer
     */
    public function getG()
    {
        return $this->g;
    }

    /**
     * Set the value of n.
     *
     * @param integer $n
     * @return \FootBundle\Entity\Scoring
     */
    public function setN($n)
    {
        $this->n = $n;

        return $this;
    }

    /**
     * Get the value of n.
     *
     * @return integer
     */
    public function getN()
    {
        return $this->n;
    }

    /**
     * Set the value of p.
     *
     * @param integer $p
     * @return \FootBundle\Entity\Scoring
     */
    public function setP($p)
    {
        $this->p = $p;

        return $this;
    }

    /**
     * Get the value of p.
     *
     * @return integer
     */
    public function getP()
    {
        return $this->p;
    }

    /**
     * Add Equipe entity to collection (one to many).
     *
     * @param \FootBundle\Entity\Equipe $equipe
     * @return \FootBundle\Entity\Scoring
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
     * @return \FootBundle\Entity\Scoring
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

    public function __sleep()
    {
        return array('id', 'g', 'n', 'p');
    }
    public function __toString()
    {
        return 'G'.$this->g.'/N'.$this->n.'/P'.$this->p;
    }
}
