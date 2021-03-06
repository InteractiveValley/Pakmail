<?php

namespace InteractiveValley\PakmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TiposFecha
 *
 * @ORM\Table(name="tipos_fecha")
 * @ORM\Entity(repositoryClass="InteractiveValley\PakmailBundle\Repository\TiposFechaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TiposFecha
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank(message="Ingresa un nombre para el tipo de fechas")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="bgColor", type="string", length=50)
     * @Assert\NotBlank(message="Ingresa el nombre de un color")
     */
    private $bgColor;

    /**
     * @var string
     *
     * @ORM\Column(name="fontColor", type="string", length=50)
     * @Assert\NotBlank(message="Elije el color blanco o negro para el color de la letra")
     */
    private $fontColor;
    
    /**
     * @var integer
     *
     * @ORM\OneToMany(targetEntity="Fecha",mappedBy="tipo",cascade={"remove"})
     * @ORM\OrderBy({"fecha" = "ASC"})
     */
    private $fechas;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    
    /*
     * Timestable
     */
    
    /**
     ** @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if(!$this->getCreatedAt())
        {
          $this->createdAt = new \DateTime();
        }
        if(!$this->getUpdatedAt())
        {
          $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }
    
    public function __toString() {
        return $this->getNombre();
    }
    
    public function __construct() {
        $this->fontColor ="black";
    }
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TiposFecha
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set bgColor
     *
     * @param string $bgColor
     *
     * @return TiposFecha
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    /**
     * Get bgColor
     *
     * @return string
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * Set fontColor
     *
     * @param string $fontColor
     *
     * @return TiposFecha
     */
    public function setFontColor($fontColor)
    {
        $this->fontColor = $fontColor;

        return $this;
    }

    /**
     * Get fontColor
     *
     * @return string
     */
    public function getFontColor()
    {
        return $this->fontColor;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TiposFecha
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TiposFecha
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add fecha
     *
     * @param \InteractiveValley\PakmailBundle\Entity\Fecha $fecha
     *
     * @return TiposFecha
     */
    public function addFecha(\InteractiveValley\PakmailBundle\Entity\Fecha $fecha)
    {
        $this->fechas[] = $fecha;

        return $this;
    }

    /**
     * Remove fecha
     *
     * @param \InteractiveValley\PakmailBundle\Entity\Fecha $fecha
     */
    public function removeFecha(\InteractiveValley\PakmailBundle\Entity\Fecha $fecha)
    {
        $this->fechas->removeElement($fecha);
    }

    /**
     * Get fechas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFechas()
    {
        return $this->fechas;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return TiposFecha
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
}
