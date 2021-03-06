<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.5-dev (doctrine2-annotation) on 2014-12-07 18:21:29.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace FootBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FootBundle\Entity\Imgteamcom
 *
 * @ORM\Entity(repositoryClass="ImgteamcomRepository")
 * @ORM\Table(name="imgteamcom", indexes={@ORM\Index(name="fk_imgteamcom_teamcom1_idx", columns={"teamcom_id"})})
 */
class Imgteamcom
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="`name`", type="string", length=45)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="integer")
     */
    protected $teamcom_id;

    /**
     * @ORM\ManyToOne(targetEntity="Teamcom", inversedBy="imgteamcoms")
     * @ORM\JoinColumn(name="teamcom_id", referencedColumnName="id")
     */
    protected $teamcom;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \FootBundle\Entity\Imgteamcom
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
     * Set the value of name.
     *
     * @param string $name
     * @return \FootBundle\Entity\Imgteamcom
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of description.
     *
     * @param string $description
     * @return \FootBundle\Entity\Imgteamcom
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of teamcom_id.
     *
     * @param integer $teamcom_id
     * @return \FootBundle\Entity\Imgteamcom
     */
    public function setTeamcomId($teamcom_id)
    {
        $this->teamcom_id = $teamcom_id;

        return $this;
    }

    /**
     * Get the value of teamcom_id.
     *
     * @return integer
     */
    public function getTeamcomId()
    {
        return $this->teamcom_id;
    }

    /**
     * Set Teamcom entity (many to one).
     *
     * @param \FootBundle\Entity\Teamcom $teamcom
     * @return \FootBundle\Entity\Imgteamcom
     */
    public function setTeamcom(Teamcom $teamcom = null)
    {
        $this->teamcom = $teamcom;

        return $this;
    }

    /**
     * Get Teamcom entity (many to one).
     *
     * @return \FootBundle\Entity\Teamcom
     */
    public function getTeamcom()
    {
        return $this->teamcom;
    }

    public function __sleep()
    {
        return array('id', 'name', 'description', 'teamcom_id');
    }
}
