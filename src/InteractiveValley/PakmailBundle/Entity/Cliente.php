<?php

namespace InteractiveValley\PakmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Cliente
 *
 * @ORM\Table(name="clientes")
 * @ORM\Entity(repositoryClass="InteractiveValley\PakmailBundle\Repository\ClienteRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("email")
 */
class Cliente implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Ingresa el nombre del usuario")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     * @Assert\Email(message="El email {{value}} no es correcto")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo;

    /**
     * @var \Booolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive = true;
    
    /**
     * @var \Empresa
     * @todo Empresa del cliente
     *
     * @ORM\ManyToOne(targetEntity="Empresa", inversedBy="clientes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
     * })
     */
    private $empresa;
    
    /**
     * @var \Perfil
     *
     * @ORM\OneToMany(targetEntity="Perfil",mappedBy="cliente",cascade={"remove"})
     * @ORM\OrderBy({"nombre" = "ASC"})
     */
    private $perfiles;
    
    
    /**
     * @var \Envio
     *
     * @ORM\OneToMany(targetEntity="Envio",mappedBy="cliente",cascade={"remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $envios;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;
    
    const TIPO_EMPRESA    =   1;
    const TIPO_USUARIO    =   2;
    
    public function __toString(){
        return $this->getNombre();
    }
    
    public function getStringCompleto(){
        return $this->getNombre();
    }
    
    public function getStringTipoGrupo(){
        $arreglo = self::getArrayTipoGrupo();
        return $arreglo[$this->getGrupo()];
    }
    
    static function getArrayTipoGrupo(){
        $arreglo=array(
            self::TIPO_EMPRESA=>'Empresa',
            self::TIPO_USUARIO=>'Usuario',
        );
        return $arreglo;
    }
    
    static function getPreferedTipoGrupo(){
        return array(self::TIPO_USUARIO);
    }

    
    public function __construct()
    {
        // may not be needed, see section on salt below
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->isActive = true;
        $this->tipo = Cliente::TIPO_USUARIO;
        $this->perfiles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->envios = new \Doctrine\Common\Collections\ArrayCollection();
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
    
    public function eraseCredentials()
    {
    }
    
    public function getRoles() {
        return array('ROLE_CLIENTE', 'ROLE_API');
    }
    
    /**
     * Get username
     *
     * @return string | email
     */
    public function getUsername()
    {
        return $this->email;
    }


    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->nombre,
            $this->email
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->nombre,
            $this->email
        ) = unserialize($serialized);
    }

    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        return $this->salt;
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
     * @return Cliente
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
     * Set email
     *
     * @param string $email
     *
     * @return Cliente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Cliente
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return Cliente
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return Cliente
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Cliente
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Cliente
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
     * @return Cliente
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
     * Set empresa
     *
     * @param \InteractiveValley\PakmailBundle\Entity\Empresa $empresa
     *
     * @return Cliente
     */
    public function setEmpresa(\InteractiveValley\PakmailBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \InteractiveValley\PakmailBundle\Entity\Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Add perfile
     *
     * @param \InteractiveValley\PakmailBundle\Entity\Perfil $perfile
     *
     * @return Cliente
     */
    public function addPerfile(\InteractiveValley\PakmailBundle\Entity\Perfil $perfile)
    {
        $this->perfiles[] = $perfile;

        return $this;
    }

    /**
     * Remove perfile
     *
     * @param \InteractiveValley\PakmailBundle\Entity\Perfil $perfile
     */
    public function removePerfile(\InteractiveValley\PakmailBundle\Entity\Perfil $perfile)
    {
        $this->perfiles->removeElement($perfile);
    }

    /**
     * Get perfiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerfiles()
    {
        return $this->perfiles;
    }

    /**
     * Add envio
     *
     * @param \InteractiveValley\PakmailBundle\Entity\Envio $envio
     *
     * @return Cliente
     */
    public function addEnvio(\InteractiveValley\PakmailBundle\Entity\Envio $envio)
    {
        $this->envios[] = $envio;

        return $this;
    }

    /**
     * Remove envio
     *
     * @param \InteractiveValley\PakmailBundle\Entity\Envio $envio
     */
    public function removeEnvio(\InteractiveValley\PakmailBundle\Entity\Envio $envio)
    {
        $this->envios->removeElement($envio);
    }

    /**
     * Get envios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnvios()
    {
        return $this->envios;
    }
}
