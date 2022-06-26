<?php

class viaje{
   
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $idempresa;
    private $rnumeroempleado;
    private $vimporte;
    private $tipoAsiento;
    private $idayvuelta;
    private $pasajeros; //array[objeto]
    private $responsable; //objeto

    public function __construct()
    {
        $this->idviaje = 0;
        $this->vdestino = "";
        $this->vcantmaxpasajeros = 0;
        $this->idempresa = 0;
        $this->rnumeroempleado = 0;
        $this->vimporte = 0;
        $this->tipoAsiento = ""; //primara clase, cama o semicama
        $this->idayvuelta = ""; //si no
        $this->pasajeros = [];
        $this->responsable;
    }

    //Observadores

    public function getIdViaje()
    {
        return $this->idviaje;
    }

    public function getDestino()
    {
        return $this->vdestino;
    }

    public function getCantMaxPasajeros()
    {
        return $this->vcantmaxpasajeros;
    }

    public function getIdEmpresa()
    {
        return $this->idempresa;
    }

    public function getNumeroEmpleado()
    {
        return $this->rnumeroempleado;
    }

    public function getImporte()
    {
        return $this->vimporte;
    }

    public function getTipoAsiento()
    {
        return $this->tipoAsiento;
    }

    public function getIdaYVuelta()
    {
        return $this->idayvuelta;
    }

    public function getPasajeros()
    {
        return $this->pasajeros;
    }

    public function getResponsable()
    {
        return $this->responsable;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeOp;
    }

    //Modificadores

    public function setIdViaje($idviaje)
    {
        $this->idviaje = $idviaje;
    }

    public function setDestino($vdestino)
    {
        $this->vdestino = $vdestino;
    }

    public function setCantMaxPasajeros($vcantmaxpasajeros)
    {
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }

    public function setIdEmpresa($idempresa)
    {
        $this->idempresa = $idempresa;
    }

    public function setNumeroEmpleado($rnumeroempleado)
    {
        $this->rnumeroempleado = $rnumeroempleado;
    }

    public function setImporte($vimporte)
    {
        $this->vimporte = $vimporte;
    }

    public function setTipoAsiento($tipoasiento)
    {
        $this->tipoAsiento = $tipoasiento;
    }

    public function setIdaYVuelta($idayvuelta)
    {
        $this->idayvuelta = $idayvuelta;
    }

    public function setPasajeros($pasajeros)
    {
        $this->pasajeros = $pasajeros;
    }

    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    public function setMensajeOperacion($mensajeOp)
    {
        $this->mensajeOp = $mensajeOp;
    }

    //Funciones

    public function __toString()
    {
        return "Id Viaje: " . $this->getIdViaje() . "\n" .
        "Destino: " . $this->getDestino() . "\n" .
        "Cantidad Maxima de Pasajeros: " . $this->getCantMaxPasajeros() . "\n" .
        "Id Empresa: " . $this->getIdEmpresa() . "\n" .
        "Numero Empleado: " . $this->getNumeroEmpleado() . "\n" .
        "Importe: " . $this->getImporte() . "\n" .
        "Tipo Asiento: " . $this->getTipoAsiento() . "\n" .
        "Responsable: " . $this->getResponsable() . "\n" .
        "Pasajeros: " . $this->pasajerosAString() . "\n";
    }

    public function pasajerosAString()
    {
        $objPasajeros = new pasajero();

        $condicion = "idviaje = " . $this->getIdViaje();
        $coleccionPasajeros = $objPasajeros->Listar($condicion);
        $retorno = "";

        foreach ($coleccionPasajeros as $pasajero)
        {
            $retorno .= $pasajero->__toString() . "\n-------------"; 
        }
        return $retorno;
    }

    public function Cargar($idviaje, $vdestino, $vcantmaxpasajeros, $idempresa, $rnumeroempleado, $vimporte, $tipoAsiento, $idayvuelta)
    {
        $this->idviaje = $idviaje;
        $this->vdestino = $vdestino;
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
        $this->idempresa = $idempresa;
        $this->rnumeroempleado = $rnumeroempleado;
        $this->vimporte = $vimporte;
        $this->tipoAsiento = $tipoAsiento;
        $this->idayvuelta = $idayvuelta;
        $this->EncontrarEmpleado();
    }

    public function EncontrarEmpleado()
    {
        $responsable = new responsableV();
        $numeroEmpleado = $this->getNumeroEmpleado();

        $responsable->Buscar($numeroEmpleado);
        $this->setResponsable($responsable);
    }

    public function Buscar($id)
    {
        $bd = new BaseDatos();
        $resp = false;
        $consulta = "SELECT * FROM viaje WHERE idviaje = " . $id;

        if($bd->Iniciar())
        {
            if($row2 = $bd->Registro())
            {
                $this->setIdViaje($id);
                $this->setDestino($row2['vdestino']);
                $this->setCantMaxPasajeros($row2['vcantmaxpasajeros']);
                $this->setIdEmpresa($row2['idempresa']);
                $this->setNumeroEmpleado($row2['rnumeroempleado']);
                $this->setImporte($row2['vimporte']);
                $this->setTipoAsiento($row2['tipoAsiento']);
                $this->setIdaYVuelta($row2['idayvuelta']);
                $resp = true;
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
        $arregloViaje = null;
        $bd = new BaseDatos();
        $consultaListar = "SELECT * FROM viaje ";
        if($condicion != "")
        {
            $consultaListar = $consultaListar . ' where ' . $condicion;
        }
            $consultaListar .= " order by idviaje ";
        if($bd->Iniciar())
        {
            if($bd->Ejecutar($consultaListar))
            {
                $arregloViaje = array();
                while($row2 = $bd->Registro())
                {
                    $idviaje = $row2['idviaje'];
                    $vdestino = $row2['vdestino'];
                    $vcantmaxpasajeros = $row2['vcantmaxpasajeros'];
                    $idempresa = $row2['idempresa'];
                    $rnumeroempleado = $row2['rnumeroempleado'];
                    $vimporte = $row2['vimporte'];
                    $tipoAsiento = $row2['tipoAsiento'];
                    $idayvuelta = $row2['idayvuelta'];

                    $viaje = new viaje();
                    $viaje->Cargar($idviaje, $vdestino, $vcantmaxpasajeros, $idempresa, $rnumeroempleado, $vimporte, $tipoAsiento, $idayvuelta);
                    array_push($arregloViaje, $viaje);
                }
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $arregloViaje;
    }

    public function Insertar()
    {
        $bd = new BaseDatos();
        $resp = false;

        if($this->getIdViaje() == null)
        {
            $consultaInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta) 
            VALUES ('" . $this->getDestino() . "','" .
        $this->getCantMaxPasajeros() .  "','" .
        $this->getIdEmpresa() .  "','" .
        $this->getNumeroEmpleado() .  "','" .
        $this->getImporte() .  "','" .
        $this->getTipoAsiento() .  "','" .
        $this->getIdaYVuelta() .  "')";
        } else {
            $consultaInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta)
            VALUES ('" . $this->getDestino() . "','" .
        $this->getCantMaxPasajeros() .  "','" .
        $this->getIdEmpresa() .  "','" .
        $this->getNumeroEmpleado() .  "','" .
        $this->getImporte() .  "','" .
        $this->getTipoAsiento() .  "','" .
        $this->getIdaYVuelta() .  "')";
        }
        if($bd->Iniciar())
        {
            if($bd->Ejecutar($consultaInsertar))
            {
                $resp = true;
            }else {
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
        $consultaModificar = "UPDATE viaje SET vdestino = '" . $this->getDestino() .
        "', vcantmaxpasajeros = '" . $this->getCantMaxPasajeros() .
        "', idempresa = '" . $this->getIdEmpresa() .
        "', rnumeroempleado = '" . $this->getNumeroEmpleado() .
        "', vimporte = '" . $this->getImporte() .
        "', tipoAsiento = '" . $this->getTipoAsiento() .
        "', idayvuelta = '" . $this->getIdaYVuelta() .
        "' WHERE idviaje = " . $this->getIdViaje();
        if($bd->Iniciar())
        {
            if($bd->Ejecutar($consultaModificar))
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
        $bd = new BaseDatos();
        $resp = false;

        if($bd->Iniciar())
        {
            $consultaBorrarPasajeros = "DELETE FROM pasajero WHERE idviaje = " . $this->getIdViaje();
            $consultaBorrarViaje = "DELETE FROM viaje WHERE idviaje = " . $this->getIdViaje();
            if($bd->Ejecutar($consultaBorrarPasajeros) && $bd->Ejecutar($consultaBorrarViaje))
            {
                $resp = true;
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        }else{
            $this->setMensajeOperacion($bd->getError());
        }
        return $resp;
    }
}