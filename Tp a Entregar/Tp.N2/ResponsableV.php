<?php
class ResponsableV
{
    private $nombre;
    private $apellido;
    private $numeroEmpleado;
    private $numeroLicencia;

    /**
     * Constructor
     * @param string $nombre
     * @param string $apellido
     * @param int $numeroEmpleado
     * @param int $numeroLicencia
     */
    public function __construct($nom, $ape, $numE, $numL)
    {
        $this->nombre = $nom;
        $this->apellido = $ape;
        $this->numeroEmpleado = $numE;
        $this->numeroLicencia = $numL;
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

    public function getNumeroLicencia()
    {
        return $this->numeroLicencia;
    }

    // Setters
    public function setNombre($nom)
    {
        return $this->nombre = $nom;
    }

    public function setApellido($ape)
    {
        return $this->apellido = $ape;

    }

    public function setNumeroEmpleado($numE)
    {
        return $this->numeroEmpleado = $numE;

    }

    public function setNumeroLicencia($numL)
    {
        return $this->numeroLicencia = $numL;
    }

    // Funciones magicas
    public function __toString()
    {
        return "Nombre: " . $this->getNombre() . "\n" . 
                "Apellido: " . $this->getApellido() . "\n" . 
                "Numero Empleado: " . $this->getNumeroEmpleado() . "\n" . 
                "Numero Licencia: " . $this->getNumeroLicencia();
    }

    public function __destruct()
    {
        return "Se destruyo la instancia de ResponsableV " . $this->getNombre() . "\n";
    }
}
