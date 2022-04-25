<?php
class ResponsableV
{
    private $nombre;
    private $apellido;
    private $numeroEmpleado;
    private $licencia;

    /**
     * Constructor
     * @param string $nombre
     * @param string $apellido
     * @param int $numeroEmpleado
     * @param int $licencia
     */
    public function __construct($nombreResponsable, $apellidoResponsable, $numEmpleado, $numLicencia)
    {
        $this->nombre = $nombreResponsable;
        $this->apellido = $apellidoResponsable;
        $this->numeroEmpleado = $numEmpleado;
        $this->licencia = $numLicencia;
    }

    // Getters
    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getNumeroEmpleado()
    {
        return $this->numeroEmpleado;
    }

    public function getlicencia()
    {
        return $this->licencia;
    }

    // Setters
    public function setNombre($nom)
    {
        return $this->nombre = $nom;
    }

    public function setApellido($ape){
        return $this->apellido = $ape;
    }

    public function setNumeroEmpleado($numeroEmpleado)
    {
        return $this->numeroEmpleado = $numeroEmpleado;
    }

    public function setlicencia($numLicencia)
    {
        return $this->licencia = $numLicencia;
    }

    // Funciones magicas
    public function __toString()
    {
        return "Nombre: " . $this->getNombre() .
            "\nApellido: " . $this->getApellido() .
            "\nNumero Empleado: " . $this->getNumeroEmpleado() .
            "\nNumero Licencia: " . $this->getlicencia();
    }

    public function __destruct()
    {
        return "Se destruyo la instancia de ResponsableV " . $this->getNombre() . "\n";
    }
}
