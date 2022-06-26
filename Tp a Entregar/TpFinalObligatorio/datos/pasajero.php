<?php

class pasajero{

    private $pnombre;
    private $papellido;
    private $rdocumento;
    private $ptelefono;
    private $idviaje;

    public function __construct()
    {
        $this->pnombre = "";
        $this->papellido = "";
        $this->rdocumento = "";
        $this->ptelefono = 0;
        $this->idviaje = null;
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

    public function getIdViaje()
    {
        return $this->idviaje;
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

    public function setIdViaje($idviaje)
    {
        $this->idviaje = $idviaje;
    }

    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    //Funciones

    public function __toString()
    {
        return "Nombre: " . $this->getNombre() . "\n" .
        "Apellido: " . $this->getApellido() . "\n" .
        "Documento; " . $this->getDocumento() . "\n" .
        "Telefono: " . $this->getTelefono() . "\n" .
        "IdViaje: " . $this->getIdViaje() . "\n";
    }

    public function Cargar($pnombre, $papellido, $rdocumento, $ptelefono, $idviaje)
    {
        $this->pnombre = $pnombre;
        $this->papellido = $papellido;
        $this->rdocumento = $rdocumento;
        $this->ptelefono = $ptelefono;
        $this->idviaje = $idviaje;
    }

    public function Buscar($dni)
    {
        $bd = new BaseDatos();
        $consulta = "SELECT * FROM pasajero WHERE rdocumento = " . $dni;   
        $resp = false;

        if($bd->iniciar())
        { if($bd->Ejecutar($consulta))
            {if($row2 = $bd->Registro())
                {
                $this->setDocumento($dni);
                $this->setNombre($row2['pnombre']);
                $this->setApellido($row2['papellido']);
                $this->setDocumento($row2['rdocumento']);
                $this->setTelefono($row2['ptelefono']);
                $this->setIdViaje($row2['idviaje']);
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

        $consulta = "SELECT FROM pasajero ";

        if($condicion!=""){
            $consulta=$consulta . ' where ' .$condicion;
        }

        if($bd->Iniciar())
        { if($bd->Ejecutar($consulta))
            {$arreglo = array();
                while($row2=$bd->Registro())
                {
                    $dni = $row2['rdocumento'];
                    $nombre = $row2['pnombre'];
                    $apellido = $row2['papellido'];
                    $telefono = $row2['ptelefono'];
                    $idviaje = $row2['idviaje'];

                    $pasajero = new pasajero();
                    $pasajero->Cargar($nombre,$apellido,$dni,$telefono,$idviaje);
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
        $resp = false;
        if($bd->Iniciar()){
        $consultaInsertar = "INSERT INTO pasajero(rdocumento, pnombre, papellido, ptelefono, idviaje) 
                        VALUES (" . $this->getDocumento() . ",'" . 
            $this->getNombre() . "','" .
            $this->getApellido() . "','" .
            $this->getTelefono() . "','" .
            $this->getIdViaje() . "')";
        
        if($bd->Ejecutar($consultaInsertar)){
            {
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

    public function Modificar()
    {
        $resp = false;
        $bd = new BaseDatos();
        $consultaModifica = "UPDATE pasajero 
        SET pnombre = '" . $this->getNombre() .
        "', papellido = '" . $this->getApellido() .
        "', ptelefono = '" . $this->getTelefono() .
        "' WHERE rdocumento = " . $this->getDocumento();
        if($bd->Iniciar())
        { if($bd->Ejecutar($consultaModifica))
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

    public function Eliminar()
    {
        $resp = false;
        $bd = new BaseDatos();
        if($bd->Iniciar())
        {
            $consultaElimina = "DELETE FROM persona WHERE rdocumento = " . $this->getDocumento();
         if($bd->Ejecutar($consultaElimina))
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