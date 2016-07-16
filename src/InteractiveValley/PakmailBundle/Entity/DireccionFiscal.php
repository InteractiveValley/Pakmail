<?php

namespace InteractiveValley\PakmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DireccionFiscal
 *
 * @ORM\Table(name="direcciones_fiscales")
 * @ORM\Entity(repositoryClass="InteractiveValley\PakmailBundle\Repository\DireccionFiscalRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DireccionFiscal {

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
     * @ORM\Column(name="calle", type="string", length=255)
     * @Assert\NotBlank(message="Ingresa el nombre la de calle")
     */
    private $calle;

    /**
     * @var string
     *
     * @ORM\Column(name="numExterior", type="string", length=100)
     * @Assert\NotBlank(message="Ingresa el número exterior")
     */
    private $numExterior;

    /**
     * @var string
     *
     * @ORM\Column(name="numInterior", type="string", length=100)
     * @Assert\NotBlank(message="Ingresa el número interior")
     */
    private $numInterior;

    /**
     * @var string
     *
     * @ORM\Column(name="poblacion", type="string", length=255)
     * @Assert\NotBlank(message="Ingresa la poblacion o colonia")
     */
    private $poblacion;

    /**
     * @var string
     *
     * @ORM\Column(name="delegacion", type="string", length=255)
     * @Assert\NotBlank(message="Ingresa tu delegacion o municipio")
     */
    private $delegacion;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=255)
     * @Assert\NotBlank(message="Ingresa el estado")
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="pais", type="string", length=255)
     * @Assert\NotBlank(message="Ingresa tu país")
     */
    private $pais;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=10)
     * @Assert\NotBlank(message="Ingresa el código postal")
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255)
     * @Assert\NotBlank(message="Ingresa un teléfono")
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=255, nullable=true)
     */
    private $celular;

    /**
     * @var string
     *
     * @ORM\Column(name="denominacion", type="string", length=150, nullable=true)
     */
    private $denominacion;

    /**
     * @var string
     *
     * @ORM\Column(name="rfc", type="string", length=12, nullable=true)
     */
    private $rfc;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_comercial", type="string", length=150, nullable=true)
     */
    private $nombreComercial;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message="Ingresa un correo")
     * @Assert\Email(message="Ingresa un email correcto")
     */
    private $email;

    public function __toString() {
        return sprintf('s% s% s% s% s%', $this->calle, $this->numExterior, $this->numInterior, $this->poblacion
                , $this->cp);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set calle
     *
     * @param string $calle
     *
     * @return DireccionFiscal
     */
    public function setCalle($calle) {
        $this->calle = $calle;

        return $this;
    }

    /**
     * Get calle
     *
     * @return string
     */
    public function getCalle() {
        return $this->calle;
    }

    /**
     * Set numExterior
     *
     * @param string $numExterior
     *
     * @return DireccionFiscal
     */
    public function setNumExterior($numExterior) {
        $this->numExterior = $numExterior;

        return $this;
    }

    /**
     * Get numExterior
     *
     * @return string
     */
    public function getNumExterior() {
        return $this->numExterior;
    }

    /**
     * Set numInterior
     *
     * @param string $numInterior
     *
     * @return DireccionFiscal
     */
    public function setNumInterior($numInterior) {
        $this->numInterior = $numInterior;

        return $this;
    }

    /**
     * Get numInterior
     *
     * @return string
     */
    public function getNumInterior() {
        return $this->numInterior;
    }

    /**
     * Set colonia
     *
     * @param string $colonia
     *
     * @return DireccionFiscal
     */
    public function setColonia($colonia) {
        $this->colonia = $colonia;

        return $this;
    }

    /**
     * Get colonia
     *
     * @return string
     */
    public function getColonia() {
        return $this->colonia;
    }

    /**
     * Set poblacion
     *
     * @param string $poblacion
     *
     * @return DireccionFiscal
     */
    public function setPoblacion($poblacion) {
        $this->poblacion = $poblacion;

        return $this;
    }

    /**
     * Get poblacion
     *
     * @return string
     */
    public function getPoblacion() {
        return $this->poblacion;
    }

    /**
     * Set delegacion
     *
     * @param string $delegacion
     *
     * @return DireccionFiscal
     */
    public function setDelegacion($delegacion) {
        $this->delegacion = $delegacion;

        return $this;
    }

    /**
     * Get delegacion
     *
     * @return string
     */
    public function getDelegacion() {
        return $this->delegacion;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return DireccionFiscal
     */
    public function setEstado($estado) {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * Set pais
     *
     * @param string $pais
     *
     * @return DireccionFiscal
     */
    public function setPais($pais) {
        $this->pais = ucwords(strtolower($pais));

        return $this;
    }

    /**
     * Get pais
     *
     * @return string
     */
    public function getPais() {
        return $this->pais;
    }

    /**
     * Set cp
     *
     * @param string $cp
     *
     * @return DireccionFiscal
     */
    public function setCp($cp) {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string
     */
    public function getCp() {
        return $this->cp;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return DireccionFiscal
     */
    public function setTelefono($telefono) {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono() {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     *
     * @return DireccionFiscal
     */
    public function setCelular($celular) {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular() {
        return $this->celular;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return DireccionFiscal
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set denominacion
     *
     * @param string $denominacion
     *
     * @return DireccionFiscal
     */
    public function setDenominacion($denominacion) {
        $this->denominacion = $denominacion;
        return $this;
    }

    /**
     * Get denominacion
     *
     * @return string
     */
    public function getDenominacion() {
        return $this->denominacion;
    }

    /**
     * Set rfc
     *
     * @param string $rfc
     *
     * @return DireccionFiscal
     */
    public function setRfc($rfc) {
        $this->rfc = $rfc;
        return $this;
    }

    /**
     * Get rfc
     *
     * @return string
     */
    public function getRfc() {
        return $this->rfc;
    }

    /**
     * Set nombreComercial
     *
     * @param string $nombreComercial
     *
     * @return DireccionFiscal
     */
    public function setNombreComercial($nombreComercial) {
        $this->nombreComercial = $nombreComercial;
        return $this;
    }

    /**
     * Get nombreComercial
     *
     * @return string
     */
    public function getNombreComercial() {
        return $this->nombreComercial;
    }

}
