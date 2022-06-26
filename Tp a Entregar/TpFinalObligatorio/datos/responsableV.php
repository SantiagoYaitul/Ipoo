<?php

class responsableV
{
    private $rnumeroEmpleado;
    private $rlicencia;
    private $rnombre;
    private $rapellido;

    public function __construct()
    {
        $this->rnumeroEmpleado = null;
        $this->rlicencia = null;
        $this->rnombre = "";
        $this->rapellido =  "";
    }

    //Observadores

    public function getNumeroEmpleado()
    {
        return $this->rnumeroEmpleado;
    }

    public function getLicencia()
    {
        return $this->rlicencia;
    }
    public function getNombre()
    {
        return $this->rnombre;
    }

    public function getApellido()
    {
        return $this->rapellido;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeOp;
    }

    //Modificadores

    public function setNumeroEmpleado($rnumeroEmpleado)
    {
        return $this->rnumeroEmpleado = $rnumeroEmpleado;
    }

    public function setLicencia($rlicencia)
    {
        return $this->rlicencia = $rlicencia;
    }

    public function setNombre($rnombre)
    {
        return $this->rnombre = $rnombre;
    }

    public function setApellido($rapellido)
    {
        return $this->rapellido = $rapellido;
    }

    public function setMensajeOperacion($mensaje)
    {
        $this->mensajeOp = $mensaje;
    }


    //Funciones 

    public function __toString()
    {
        return "\n\tNumero Empleado: " . $this->getNumeroEmpleado() .
            "\n\tNumero Licencia: " . $this->getlicencia() .
            "\n\tNombre: " . $this->getNombre() .
            "\n\tApellido: " . $this->getApellido() . "\n";
    }

    public function Cargar($numEmpleado, $numlicencia, $nombreResponsable, $apellidoResponsable)
    {
        $this->rnumeroEmpleado = $numEmpleado;
        $this->rlicencia = $numlicencia;
        $this->rnombre = $nombreResponsable;
        $this->rapellido = $apellidoResponsable;
    }

    public function Buscar($numEmpleado)
    {
        $bd = new BaseDatos();
        $consultaR = "SELECT * FROM responsable WHERE rnumeroempleado = " . $numEmpleado;
        $resp = false;

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($consultaR)) {
                if ($row2 = $bd->Registro()) {
                    $this->setNumeroEmpleado($numEmpleado);
                    $this->setNombre($row2['rnombre']);
                    $this->setApellido($row2['rapellido']);
                    $this->setLicencia($row2['rnumerolicencia']);
                    $resp = true;
                }
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $resp;
    }

    public function Listar($condicion = "")
    {
        $arreglo = null;
        $bd = new BaseDatos();
        $consultaLista = "SELECT * FROM responsable ";
        if ($condicion != "") {
            $consultaLista = $consultaLista . ' where ' . $condicion;
        }

        $consultaLista .= 'order by rnumeroEmpleado';

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($consultaLista)) {
                $arreglo = array();
                while ($row2 = $bd->Registro()) {

                    $numEmpleado = $row2['rnumeroempleado'];
                    $nombre = $row2['rnombre'];
                    $apellido = $row2['rapellido'];
                    $numLicencia = $row2['rnumerolicencia'];

                    $responsable = new ResponsableV();
                    $responsable->Cargar($numEmpleado, $numLicencia, $nombre, $apellido);
                    array_push($arrResponsables, $responsable);
                }
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }

        return $arreglo;
    }

    public function Insertar()
    {
        $bd = new BaseDatos();
        $resp = false;
        if($this->getNumeroDocumento())
        {
            $consultaInsertar = "INSERT INTO responsable(rnumerolicencia, rnombre, rapellido)
                                VALUES (" . $this->getLicencia() . "','" . $this->getNombre() . "','" . 
                                $this->getApellido() . "')";
        } else {
            $consultaInsertar = "INSERT INTO responsable (rnumeroempleado, rnumerolicencia, rnombre, rapellido)
                                VALUES (" . $this->getNumeroEmpleado() . "','" .
                $this->getLicencia() . "','" .
                $this->getNombre() . "','" .
                $this->getApellido() . "')";
        }
        if($bd->Iniciar())
        {
            if($bd->Ejecutar($consultaInsertar))
            {
                $resp = true;
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $resp;
    }

    public function Modificar()
    {
        $resp = false;
        $bd = new BaseDatos();
        $consultaModifica =  "UPDATE responsable 
        SET rnombre = '" . $this->getNombre() .
        "', rapellido = '" . $this->getApellido() .
        "', rnumerolicencia = '" . $this->getLicencia() .
        "' WHERE rnumeroempleado = " . $this->getNumeroEmpleado();
    }

    public function Eliminar()
    {
        $bd = new BaseDatos();
        $resp = false;

        $consultaBorrar = "DELETE FROM persona WHERE rnumeroempleado " . $this->getNumeroEmpleado();
        if($bd->Iniciar())
        {
            if($bd->Ejecutar($consultaBorrar))
            {
                $resp = true;
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $resp;
    }
}
