<?php

namespace InteractiveValley\PakmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Envio
 *
 * @ORM\Table(name="envios")
 * @ORM\Entity(repositoryClass="InteractiveValley\PakmailBundle\Repository\EnvioRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Envio
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
     * @var integer
     *
     * @ORM\Column(name="perfil", type="integer")
     */
    private $perfil;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hasPerfil", type="boolean")
     */
    private $hasPerfil;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaSolicitud", type="datetime")
     */
    private $fechaSolicitud;

    /**
     * @var Cliente
     * @todo Cliente del envio
     *
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="envios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     * })
     */
    private $cliente;

    /**
     * @var InteractiveValley\BackendBundle\Entity\Usuario
     * @todo Usuario validador del envio
     *
     * @ORM\ManyToOne(targetEntity="InteractiveValley\BackendBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
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
     * Set origen
     *
     * @param string $origen
     *
     * @return Envio
     */
    public function setOrigen($origen)
    {
        $this->origen = $origen;

        return $this;
    }

    /**
     * Get origen
     *
     * @return string
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * Set destino
     *
     * @param string $destino
     *
     * @return Envio
     */
    public function setDestino($destino)
    {
        $this->destino = $destino;

        return $this;
    }

    /**
     * Get destino
     *
     * @return string
     */
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * Set remitente
     *
     * @param string $remitente
     *
     * @return Envio
     */
    public function setRemitente($remitente)
    {
        $this->remitente = $remitente;

        return $this;
    }

    /**
     * Get remitente
     *
     * @return string
     */
    public function getRemitente()
    {
        return $this->remitente;
    }

    /**
     * Set destinatario
     *
     * @param string $destinatario
     *
     * @return Envio
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;

        return $this;
    }

    /**
     * Get destinatario
     *
     * @return string
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set perfil
     *
     * @param integer $perfil
     *
     * @return Envio
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get perfil
     *
     * @return integer
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set hasPerfil
     *
     * @param boolean $hasPerfil
     *
     * @return Envio
     */
    public function setHasPerfil($hasPerfil)
    {
        $this->hasPerfil = $hasPerfil;

        return $this;
    }

    /**
     * Get hasPerfil
     *
     * @return boolean
     */
    public function getHasPerfil()
    {
        return $this->hasPerfil;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Envio
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set fechaSolicitud
     *
     * @param \DateTime $fechaSolicitud
     *
     * @return Envio
     */
    public function setFechaSolicitud($fechaSolicitud)
    {
        $this->fechaSolicitud = $fechaSolicitud;

        return $this;
    }

    /**
     * Get fechaSolicitud
     *
     * @return \DateTime
     */
    public function getFechaSolicitud()
    {
        return $this->fechaSolicitud;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Envio
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
     * @return Envio
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
     * Set cliente
     *
     * @param \InteractiveValley\PakmailBundle\Entity\Cliente $cliente
     *
     * @return Envio
     */
    public function setCliente(\InteractiveValley\PakmailBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \InteractiveValley\PakmailBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set usuario
     *
     * @param \InteractiveValley\BackendBundle\Entity\Usuario $usuario
     *
     * @return Envio
     */
    public function setUsuario(\InteractiveValley\BackendBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \InteractiveValley\BackendBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
