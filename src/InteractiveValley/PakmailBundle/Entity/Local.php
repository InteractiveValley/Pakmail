<?php

namespace InteractiveValley\PakmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;

/**
 * Local
 *
 * @ORM\Table(name="directorio")
 * @ORM\Entity(repositoryClass="InteractiveValley\PakmailBundle\Repository\LocalRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Local
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
     * @Assert\NotBlank
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255,nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=255, nullable=true)
     */
    private $web;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string", length=255, nullable=true)
     */
    private $instagram;
    
    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="InteractiveValley\GaleriasBundle\Entity\Galeria")
     * @ORM\JoinTable(name="locales_galeria")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $galerias;

    /**
     * @var string
     * @todo Slug del local
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     * @todo top
     *
     * @ORM\Column(name="posicion_top", type="string", length=5, nullable=true)
     */
    private $top;

    /**
     * @var string
     * @todo left
     *
     * @ORM\Column(name="posicion_left", type="string", length=5, nullable=true)
     */
    private $left;
	
	/**
     * @var string
     * @todo left
     *
     * @ORM\Column(name="posicion_tooltip", type="string", length=10, nullable=true)
     */
    private $tooltip;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean" )
     */
    private $isActive;

    /**
     * @var string
     * @todo left
     *
     * @ORM\Column(name="horarios", type="string", length=255, nullable=true)
     */
    private $horarios;

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
    
    public function __construct() {
        $this->isActive = true;
        $this->galerias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->top = "100px";
        $this->left = "100px";
		$this->tooltip = "izquierda";
    }
    
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

    /*
     * Slugable
     */
    
    /*
     * Esta funcion es para slugar el valor.
     * @ORM\PrePersist
     * @ORM\PreUpdate 
     */
    public function setSlugAtValue()
    {
        $this->slug = RpsStms::slugify($this->getNombre());
    }
    
    
    /*** uploads ***/
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->imagen)) {
            // store the old name to delete after the update
            $this->temp = $this->imagen;
            $this->imagen = null;
        } else {
            $this->imagen = 'initial';
        }
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
   /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preUpload()
    {
      $var = true;  
      if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->imagen = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function upload()
    {
      $var = true;  
      if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->imagen);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        
        $this->file = null;
    }

    /**
    * @ORM\PostRemove
    */
    public function removeUpload()
    {
      if ($file = $this->getAbsolutePath()) {
        if(file_exists($file)){
            unlink($file);
        }
      }
    }
    
    protected function getUploadDir()
    {
        return '/uploads/directorio';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web'.$this->getUploadDir();
    }
    
    public function getWebPath()
    {
        return null === $this->imagen ? null : $this->getUploadDir().'/'.$this->imagen;
    }
    
    public function getAbsolutePath()
    {
        return null === $this->imagen ? null : $this->getUploadRootDir().'/'.$this->imagen;
    }

    /*** uploads ***/
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $fileLogo;
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFileLogo(UploadedFile $file = null)
    {
        $this->fileLogo = $file;
        // check if we have an old image path
        if (isset($this->logo)) {
            // store the old name to delete after the update
            $this->tempLogo = $this->logo;
            $this->logo = null;
        } else {
            $this->logo = 'initial';
        }
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFileLogo()
    {
        return $this->fileLogo;
    }
    
   /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preUploadLogo()
    {
      $var = true;  
      if (null !== $this->getFileLogo()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->logo = $filename.'.'.$this->getFileLogo()->guessExtension();
        }
    }

    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function uploadLogo()
    {
      $var = true;  
      if (null === $this->getFileLogo()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFileLogo()->move($this->getUploadRootDir(), $this->logo);

        // check if we have an old image
        if (isset($this->tempLogo)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempLogo);
            // clear the temp image path
            $this->tempLogo = null;
        }
        
        $this->fileLogo = null;
    }

    /**
    * @ORM\PostRemove
    */
    public function removeUploadLogo()
    {
      if ($file = $this->getAbsolutePathLogo()) {
        if(file_exists($file)){
            unlink($file);
        }
      }
    }
    
    public function getWebPathLogo()
    {
        return null === $this->logo ? null : $this->getUploadDir().'/'.$this->logo;
    }
    
    public function getAbsolutePathLogo()
    {
        return null === $this->logo ? null : $this->getUploadRootDir().'/'.$this->logo;
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
     * @return Local
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Local
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Local
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set web
     *
     * @param string $web
     * @return Local
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     *
     * @return string 
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return Local
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return Local
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set instagram
     *
     * @param string $instagram
     * @return Local
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return string 
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     * @return Local
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string 
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Local
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
     * @return Local
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
     * Set position
     *
     * @param integer $position
     * @return Local
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

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Local
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add galerias
     *
     * @param \InteractiveValley\GaleriasBundle\Entity\Galeria $galerias
     * @return Local
     */
    public function addGaleria(\InteractiveValley\GaleriasBundle\Entity\Galeria $galerias)
    {
        $this->galerias[] = $galerias;

        return $this;
    }

    /**
     * Remove galerias
     *
     * @param \InteractiveValley\GaleriasBundle\Entity\Galeria $galerias
     */
    public function removeGaleria(\InteractiveValley\GaleriasBundle\Entity\Galeria $galerias)
    {
        $this->galerias->removeElement($galerias);
    }

    /**
     * Get galerias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGalerias()
    {
        return $this->galerias;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Local
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Local
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set top
     *
     * @param string $top
     * @return Local
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top
     *
     * @return string 
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set left
     *
     * @param string $left
     * @return Local
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * Get left
     *
     * @return string 
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set horarios
     *
     * @param string $horarios
     * @return Local
     */
    public function setHorarios($horarios)
    {
        $this->horarios = $horarios;

        return $this;
    }

    /**
     * Get horarios
     *
     * @return string 
     */
    public function getHorarios()
    {
        return $this->horarios;
    }

    /**
     * Set tooltip
     *
     * @param string $tooltip
     * @return Local
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    /**
     * Get tooltip
     *
     * @return string 
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }
}
