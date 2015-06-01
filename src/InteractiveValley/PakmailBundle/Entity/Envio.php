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
     * @var DireccionFiscal
     * @todo Direccion fiscal del envio
     *
     * @ORM\ManyToOne(targetEntity="DireccionFiscal",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="direccion_fiscal_id", referencedColumnName="id")
     * })
     */
    private $direccionFiscal;
    
    /**
     * @var DireccionRemision
     * @todo Direccion del remitente del envio
     *
     * @ORM\ManyToOne(targetEntity="DireccionRemision",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="direccion_remitente_id", referencedColumnName="id")
     * })
     */
    private $direccionRemitente;
    
    /**
     * @var DireccionDestino
     * @todo Direccion destino del envio
     *
     * @ORM\ManyToOne(targetEntity="DireccionDestino",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="direccion_destino_id", referencedColumnName="id")
     * })
     */
    private $direccionDestino;
    
    /**
     * @var string
     *
     * @ORM\Column(name="referencia", type="text")
     */
    private $referencia;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=150)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="kilogramos", type="decimal", scale=2)
     */
    private $kilogramos;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", scale=2)
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="numGuia", type="string", length=255)
     */
    private $numGuia;

    /**
     * @var string
     *
     * @ORM\Column(name="folio", type="string", length=255)
     */
    private $folio;

    /**
     * @var boolean
     *
     * @ORM\Column(name="asegurarEnvio", type="boolean")
     */
    private $asegurarEnvio;

    /**
     * @var string
     *
     * @ORM\Column(name="montoSeguro", type="decimal", scale=2)
     */
    private $montoSeguro;

    /**
     * @var string
     *
     * @ORM\Column(name="importeSeguro", type="decimal", scale=2)
     */
    private $importeSeguro;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text")
     */
    private $observaciones;
    

    /**
     * @var integer
     *
     * @ORM\Column(name="perfil", type="integer",nullable=true)
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
    
    const STATUS_EN_PROCESO =   1;
    const STATUS_ACEPTADA   =   2;
    const STATUS_RECHAZADA  =   3;

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
          $this->fechaSolicitud = new \DateTime();
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
     ** @ORM\PrePersist
     */
    public function setHasPerfilValue()
    {
        if(!$this->getHasPerfil())
        {
          $this->hasPerfil = false;
        }
    }
    
    /**
     ** @ORM\PrePersist
     */
    public function setStatusValue()
    {
        if(!$this->getStatus())
        {
          $this->status = self::STATUS_EN_PROCESO;
        }
    }

    public function getStringStatus(){
        $arreglo=array(
            self::STATUS_EN_PROCESO=>'En proceso',
            self::STATUS_ACEPTADA=>'Aceptada',
            self::STATUS_RECHAZADA=>'Rechazada',
        );
        return $arreglo[$this->getStatus()];
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
     * Set referencia
     *
     * @param string $referencia
     *
     * @return Envio
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Get referencia
     *
     * @return string
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Envio
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set kilogramos
     *
     * @param string $kilogramos
     *
     * @return Envio
     */
    public function setKilogramos($kilogramos)
    {
        $this->kilogramos = $kilogramos;

        return $this;
    }

    /**
     * Get kilogramos
     *
     * @return string
     */
    public function getKilogramos()
    {
        return $this->kilogramos;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return Envio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set numGuia
     *
     * @param string $numGuia
     *
     * @return Envio
     */
    public function setNumGuia($numGuia)
    {
        $this->numGuia = $numGuia;

        return $this;
    }

    /**
     * Get numGuia
     *
     * @return string
     */
    public function getNumGuia()
    {
        return $this->numGuia;
    }

    /**
     * Set folio
     *
     * @param string $folio
     *
     * @return Envio
     */
    public function setFolio($folio)
    {
        $this->folio = $folio;

        return $this;
    }

    /**
     * Get folio
     *
     * @return string
     */
    public function getFolio()
    {
        return $this->folio;
    }

    /**
     * Set asegurarEnvio
     *
     * @param boolean $asegurarEnvio
     *
     * @return Envio
     */
    public function setAsegurarEnvio($asegurarEnvio)
    {
        $this->asegurarEnvio = $asegurarEnvio;

        return $this;
    }

    /**
     * Get asegurarEnvio
     *
     * @return boolean
     */
    public function getAsegurarEnvio()
    {
        return $this->asegurarEnvio;
    }

    /**
     * Set montoSeguro
     *
     * @param string $montoSeguro
     *
     * @return Envio
     */
    public function setMontoSeguro($montoSeguro)
    {
        $this->montoSeguro = $montoSeguro;

        return $this;
    }

    /**
     * Get montoSeguro
     *
     * @return string
     */
    public function getMontoSeguro()
    {
        return $this->montoSeguro;
    }

    /**
     * Set importeSeguro
     *
     * @param string $importeSeguro
     *
     * @return Envio
     */
    public function setImporteSeguro($importeSeguro)
    {
        $this->importeSeguro = $importeSeguro;

        return $this;
    }

    /**
     * Get importeSeguro
     *
     * @return string
     */
    public function getImporteSeguro()
    {
        return $this->importeSeguro;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Envio
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
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
     * Set direccionFiscal
     *
     * @param \InteractiveValley\PakmailBundle\Entity\DireccionFiscal $direccionFiscal
     *
     * @return Envio
     */
    public function setDireccionFiscal(\InteractiveValley\PakmailBundle\Entity\DireccionFiscal $direccionFiscal = null)
    {
        $this->direccionFiscal = $direccionFiscal;

        return $this;
    }

    /**
     * Get direccionFiscal
     *
     * @return \InteractiveValley\PakmailBundle\Entity\DireccionFiscal
     */
    public function getDireccionFiscal()
    {
        return $this->direccionFiscal;
    }

    /**
     * Set direccionRemitente
     *
     * @param \InteractiveValley\PakmailBundle\Entity\DireccionRemision $direccionRemitente
     *
     * @return Envio
     */
    public function setDireccionRemitente(\InteractiveValley\PakmailBundle\Entity\DireccionRemision $direccionRemitente = null)
    {
        $this->direccionRemitente = $direccionRemitente;

        return $this;
    }

    /**
     * Get direccionRemitente
     *
     * @return \InteractiveValley\PakmailBundle\Entity\DireccionRemision
     */
    public function getDireccionRemitente()
    {
        return $this->direccionRemitente;
    }

    /**
     * Set direccionDestino
     *
     * @param \InteractiveValley\PakmailBundle\Entity\DireccionDestino $direccionDestino
     *
     * @return Envio
     */
    public function setDireccionDestino(\InteractiveValley\PakmailBundle\Entity\DireccionDestino $direccionDestino = null)
    {
        $this->direccionDestino = $direccionDestino;

        return $this;
    }

    /**
     * Get direccionDestino
     *
     * @return \InteractiveValley\PakmailBundle\Entity\DireccionDestino
     */
    public function getDireccionDestino()
    {
        return $this->direccionDestino;
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
