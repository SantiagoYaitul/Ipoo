<?php

class pasajero
{

    private $pnombre;
    private $papellido;
    private $rdocumento;
    private $ptelefono;
    private $viaje;

    public function __construct()
    {
        $this->pnombre = "";
        $this->papellido = "";
        $this->rdocumento = "";
        $this->ptelefono = 0;
        $this->viaje;
    }

    //OBSERVADORES

    public function getNombre()
    {
        return $this->pnombre;
    }

    public function getApellido()
    {
        return $this->papellido;
    }

    public function getDocumento()
    {
        return $this->rdocumento;
    }

    public function getTelefono()
    {
        return $this->ptelefono;
    }

    public function getViaje()
    {
        return $this->viaje;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }

    //MODIFICADORES

    public function setNombre($pnombre)
    {
        $this->pnombre = $pnombre;
    }

    public function setApellido($papellido)
    {
        $this->papellido = $papellido;
    }

    public function setDocumento($rdocumento)
    {
        $this->rdocumento = $rdocumento;
    }

    public function setTelefono($ptelefono)
    {
        $this->ptelefono = $ptelefono;
    }

    public function setViaje($viaje)
    {
        $this->viaje = $viaje;
    }

    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    //Funciones

    public function __toString()
    {
        return "\nNombre: " . $this->getNombre() . "\n" .
            "Apellido: " . $this->getApellido() . "\n" .
            "Documento; " . $this->getDocumento() . "\n" .
            "Telefono: " . $this->getTelefono() . "\n" .
            "viaje: " . $this->getViaje() . "\n";
    }

    public function Cargar($pnombre, $papellido, $rdocumento, $ptelefono, $viaje)
    {
        $this->pnombre = $pnombre;
        $this->papellido = $papellido;
        $this->rdocumento = $rdocumento;
        $this->ptelefono = $ptelefono;
        $this->viaje = $viaje;
    }

    public function Buscar($dni)
    {
        $bd = new BaseDatos();
        $viaje = new viaje();
        $consulta = "SELECT * FROM pasajero WHERE rdocumento = " . $dni;
        $result = false;

        if ($bd->iniciar()) 
        {
            if ($bd->Ejecutar($consulta)) 
            {
                if ($row2 = $bd->Registro()) 
                {
                    $this->setDocumento($dni);
                    $this->setNombre($row2['pnombre']);
                    $this->setApellido($row2['papellido']);
                    $this->setDocumento($row2['rdocumento']);
                    $this->setTelefono($row2['ptelefono']);
                    $this->setViaje($viaje->Buscar($row2['idviaje'], null));
                    $result = true;
                }
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }

        return $result;
    }

    public function Listar($condicion)
    {
        $arreglo = null;
        $bd = new BaseDatos();
        
        $consulta = "SELECT * FROM pasajero " . $condicion . ";";

        if ($bd->Iniciar()) 
        {
            if ($bd->Ejecutar($consulta)) 
            {
                $arreglo = array();
                while ($row2 = $bd->Registro()) {
                    $dni = $row2['rdocumento'];
                    $nombre = $row2['pnombre'];
                    $apellido = $row2['papellido'];
                    $telefono = $row2['ptelefono'];
                    $viaje = $row2['idviaje'];

                    $pasajero = new pasajero();
                    $pasajero->Cargar($nombre, $apellido, $dni, $telefono, $viaje);
                    array_push($arreglo, $pasajero);
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
        $viaje = $this->getViaje();
        $result = false;
        if ($bd->Iniciar()) 
        {
            $consultaInsertar = "INSERT INTO pasajero(rdocumento, pnombre, papellido, ptelefono, idviaje) VALUES
          ('" . $this->getDocumento() . "','" .
                $this->getNombre() . "','" .
                $this->getApellido() . "','" .
                $this->getTelefono() . "','" .
                $viaje->getIdViaje() . "')";

            if ($bd->Ejecutar($consultaInsertar)) 
            { 
                    $result = true;
                
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }

        return $result;
    }

    public function Modificar($dniAntiguo = "", $condicion = "")
    {
        $result = false;
        $bd = new BaseDatos();
        if ($dniAntiguo == null) 
        {
            $queryModifica = "UPDATE pasajero 
            SET pnombre = '" . $this->getNombre() .
                "', papellido = '" . $this->getApellido() .
                "', ptelefono = '" . $this->getTelefono() .
                "' WHERE rdocumento = " . $this->getDocumento();
        } else {
            $queryModifica = "UPDATE pasajero 
            SET rdocumento = " .  $this->getDocumento() .
                ", pnombre = '" . $this->getNombre() .
                "', papellido = '" . $this->getApellido() .
                "', ptelefono = '" . $this->getTelefono() .
                "' WHERE rdocumento = " . $dniAntiguo;
        }

        if ($condicion != null) 
        {
            $queryModifica = $condicion;
        }

        if ($bd->Iniciar()) 
        {
            if ($bd->Ejecutar($queryModifica)) 
            {
                $result =  true;
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $result;
    }

    public function Eliminar()
    {
        $result = false;
        $bd = new BaseDatos();
        if ($bd->Iniciar()) 
        {
            $consultaElimina = "DELETE FROM pasajero WHERE rdocumento = " . $this->getDocumento();
            if ($bd->Ejecutar($consultaElimina)) 
            {
                $result = true;
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $result;
    }
}
