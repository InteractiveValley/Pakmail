<?php

namespace InteractiveValley\PakmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use InteractiveValley\PakmailBundle\Entity\DireccionFiscal;
use InteractiveValley\PakmailBundle\Entity\DireccionDestino;
use InteractiveValley\PakmailBundle\Entity\DireccionRemision;

/**
 * Perfil
 *
 * @ORM\Table(name="perfiles_envio")
 * @ORM\Entity(repositoryClass="InteractiveValley\PakmailBundle\Repository\PerfilRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Perfil
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
     * @Assert\NotBlank(message="Ingresa un nombre del perfil")
     */
    private $nombre;
    
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
     * @Assert\NotBlank(message="Ingresar una referencia")
     */
    private $referencia;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=150)
     * @Assert\NotBlank(message="Ingresar un tipo")
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", scale=2)
     * @Assert\NotBlank(message="Ingresar un precio")
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="numGuia", type="string", length=255)
     * @Assert\NotBlank(message="Ingresar numero de guia")
     */
    private $numGuia;

    /**
     * @var string
     *
     * @ORM\Column(name="folio", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="montoSeguro", type="decimal", scale=2, nullable=true)
     */
    private $montoSeguro;

    /**
     * @var string
     *
     * @ORM\Column(name="importeSeguro", type="decimal", scale=2, nullable=true)
     */
    private $importeSeguro;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     */
    private $observaciones;
	
    /**
     * @var string
     *
     * @ORM\Column(name="medidas_peso", type="string", length=100)
     * @Assert\NotBlank(message="Ingresar peso en kilogramos")
     */
    private $medidaPeso;
    
    /**
     * @var string
     *
     * @ORM\Column(name="medidas_largo", type="string", length=100)
     * @Assert\NotBlank(message="Ingresar el largo")
     */
    private $medidaLargo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="medidas_ancho", type="string", length=100)
     * @Assert\NotBlank(message="Ingresar el ancho")
     */
    private $medidaAncho;
    
    /**
     * @var string
     *
     * @ORM\Column(name="medida_alto", type="string", length=100)
     * @Assert\NotBlank(message="Ingresar el alto")
     */
    private $medidaAlto;
    
    /**
     * @var string
     *
     * @ORM\Column(name="generar_gastos_aduana", type="boolean", nullable=true)
     */
    private $generarGastosAduana;
    
    /**
     * @var string
     *
     * @ORM\Column(name="valor_declarado", type="string", length=255, nullable=true)
     */
    private $valorDeclarado;
    
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

    /**
     * @var Cliente
     * @todo Cliente del perfil
     *
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="perfiles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     * })
     */
    private $cliente;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;


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
     * @Assert\True(message="Ingresa un monto de seguro valido") 
     */
    public function isMontoSeguroValid(){
        if($this->asegurarEnvio){
            return $this->getMontoSeguro()>0;
        }
        return true;
    }
    
    /**
     * @Assert\True(message="Ingresa un importe de seguro valido") 
     */
    public function isMontoImporteValid(){
        if($this->asegurarEnvio){
            return $this->getImporteSeguro()>0;
        }
        return true;
    }
    
    /**
     * @Assert\True(message="Ingresa un valor declarado valido") 
     */
    public function isValorDeclaradoValid(){
        if($this->generarGastosAduana){
            return $this->getValorDeclarado()>0;
        }
        return true;
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
     * @return Perfil
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
     * Set referencia
     *
     * @param string $referencia
     *
     * @return Perfil
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
     * @return Perfil
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
     * Set precio
     *
     * @param string $precio
     *
     * @return Perfil
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
     * @return Perfil
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
     * @return Perfil
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
     * @return Perfil
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
     * @return Perfil
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
     * @return Perfil
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
     * @return Perfil
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
     * Set medidaPeso
     *
     * @param string $medidaPeso
     *
     * @return Perfil
     */
    public function setMedidaPeso($medidaPeso)
    {
        $this->medidaPeso = $medidaPeso;

        return $this;
    }

    /**
     * Get medidaPeso
     *
     * @return string
     */
    public function getMedidaPeso()
    {
        return $this->medidaPeso;
    }

    /**
     * Set medidaLargo
     *
     * @param string $medidaLargo
     *
     * @return Perfil
     */
    public function setMedidaLargo($medidaLargo)
    {
        $this->medidaLargo = $medidaLargo;

        return $this;
    }

    /**
     * Get medidaLargo
     *
     * @return string
     */
    public function getMedidaLargo()
    {
        return $this->medidaLargo;
    }

    /**
     * Set medidaAncho
     *
     * @param string $medidaAncho
     *
     * @return Perfil
     */
    public function setMedidaAncho($medidaAncho)
    {
        $this->medidaAncho = $medidaAncho;

        return $this;
    }

    /**
     * Get medidaAncho
     *
     * @return string
     */
    public function getMedidaAncho()
    {
        return $this->medidaAncho;
    }

    /**
     * Set medidaAlto
     *
     * @param string $medidaAlto
     *
     * @return Perfil
     */
    public function setMedidaAlto($medidaAlto)
    {
        $this->medidaAlto = $medidaAlto;

        return $this;
    }

    /**
     * Get medidaAlto
     *
     * @return string
     */
    public function getMedidaAlto()
    {
        return $this->medidaAlto;
    }

    /**
     * Set generarGastosAduana
     *
     * @param boolean $generarGastosAduana
     *
     * @return Perfil
     */
    public function setGenerarGastosAduana($generarGastosAduana)
    {
        $this->generarGastosAduana = $generarGastosAduana;

        return $this;
    }

    /**
     * Get generarGastosAduana
     *
     * @return boolean
     */
    public function getGenerarGastosAduana()
    {
        return $this->generarGastosAduana;
    }

    /**
     * Set valorDeclarado
     *
     * @param string $valorDeclarado
     *
     * @return Perfil
     */
    public function setValorDeclarado($valorDeclarado)
    {
        $this->valorDeclarado = $valorDeclarado;

        return $this;
    }

    /**
     * Get valorDeclarado
     *
     * @return string
     */
    public function getValorDeclarado()
    {
        return $this->valorDeclarado;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Perfil
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
     * @return Perfil
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Perfil
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
     * Set direccionFiscal
     *
     * @param \InteractiveValley\PakmailBundle\Entity\DireccionFiscal $direccionFiscal
     *
     * @return Perfil
     */
    public function setDireccionFiscal(\InteractiveValley\PakmailBundle\Entity\DireccionFiscal $direccionFiscal = null)
    {
        $this->direccionFiscal = $direccionFiscal;

        return $this;
    }
    
    /**
     * Set direccionFiscal
     *
     * @param \InteractiveValley\PakmailBundle\Entity\DireccionFiscal $direccionFiscal
     *
     * @return Perfil
     */
    public function setDireccionFiscalToModel(\InteractiveValley\PakmailBundle\Entity\DireccionFiscal $direccionFiscal = null)
    {
        $this->direccionFiscal = new DireccionFiscal();
        
        $this->direccionFiscal->setCalle($direccionFiscal->getCalle());
        $this->direccionFiscal->setNumInterior($direccionFiscal->getNumInterior());
        $this->direccionFiscal->setNumExterior($direccionFiscal->getNumExterior());
        $this->direccionFiscal->setPais($direccionFiscal->getPais());
        $this->direccionFiscal->setEstado($direccionFiscal->getEstado());
        $this->direccionFiscal->setDelegacion($direccionFiscal->getDelegacion());
        $this->direccionFiscal->setPoblacion($direccionFiscal->getPoblacion());
        $this->direccionFiscal->setCp($direccionFiscal->getCp());
        $this->direccionFiscal->setTelefono($direccionFiscal->getTelefono());
        $this->direccionFiscal->setCelular($direccionFiscal->getCelular());
        $this->direccionFiscal->setEmail($direccionFiscal->getEmail());

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
     * @return Perfil
     */
    public function setDireccionRemitente(\InteractiveValley\PakmailBundle\Entity\DireccionRemision $direccionRemitente = null)
    {
        $this->direccionRemitente = $direccionRemitente;

        return $this;
    }
    
    /**
     * Set direccionRemitente
     *
     * @param \InteractiveValley\PakmailBundle\Entity\DireccionRemision $direccionRemitente
     *
     * @return Perfil
     */
    public function setDireccionRemitenteToModel(\InteractiveValley\PakmailBundle\Entity\DireccionRemision $direccionRemitente = null)
    {
        $this->direccionRemitente = new DireccionRemision();
        
        $this->direccionRemitente->setCalle($direccionRemitente->getCalle());
        $this->direccionRemitente->setNumInterior($direccionRemitente->getNumInterior());
        $this->direccionRemitente->setNumExterior($direccionRemitente->getNumExterior());
        $this->direccionRemitente->setPais($direccionRemitente->getPais());
        $this->direccionRemitente->setEstado($direccionRemitente->getEstado());
        $this->direccionRemitente->setDelegacion($direccionRemitente->getDelegacion());
        $this->direccionRemitente->setPoblacion($direccionRemitente->getPoblacion());
        $this->direccionRemitente->setCp($direccionRemitente->getCp());
        $this->direccionRemitente->setTelefono($direccionRemitente->getTelefono());
        $this->direccionRemitente->setCelular($direccionRemitente->getCelular());
        $this->direccionRemitente->setEmail($direccionRemitente->getEmail());

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
     * @return Perfil
     */
    public function setDireccionDestino(\InteractiveValley\PakmailBundle\Entity\DireccionDestino $direccionDestino = null)
    {
        $this->direccionDestino = $direccionDestino;

        return $this;
    }
    
    /**
     * Set direccionDestino
     *
     * @param \InteractiveValley\PakmailBundle\Entity\DireccionDestino $direccionDestino
     *
     * @return Perfil
     */
    public function setDireccionDestinoToModel(\InteractiveValley\PakmailBundle\Entity\DireccionDestino $direccionDestino = null)
    {
        $this->direccionDestino = new DireccionDestino();
        
        $this->direccionDestino->setCalle($direccionDestino->getCalle());
        $this->direccionDestino->setNumInterior($direccionDestino->getNumInterior());
        $this->direccionDestino->setNumExterior($direccionDestino->getNumExterior());
        $this->direccionDestino->setPais($direccionDestino->getPais());
        $this->direccionDestino->setEstado($direccionDestino->getEstado());
        $this->direccionDestino->setDelegacion($direccionDestino->getDelegacion());
        $this->direccionDestino->setPoblacion($direccionDestino->getPoblacion());
        $this->direccionDestino->setCp($direccionDestino->getCp());
        $this->direccionDestino->setTelefono($direccionDestino->getTelefono());
        $this->direccionDestino->setCelular($direccionDestino->getCelular());
        $this->direccionDestino->setEmail($direccionDestino->getEmail());
        
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
     * @return Perfil
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
}
