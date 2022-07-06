<?php

class viaje
{

    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $vimporte;
    private $tipoAsiento;
    private $idayvuelta;
    private $pasajeros; 
    private $responsable; 
    private $empresa;

    public function __construct()
    {
        $this->idviaje;
        $this->vdestino;
        $this->vcantmaxpasajeros;
        $this->vimporte;
        $this->tipoAsiento; //primera clase, cama o semicama
        $this->idayvuelta; //si no
        $this->pasajeros;
        $this->responsable;
        $this->empresa;
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

    public function getEmpresa()
    {
        return $this->empresa;
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

    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    //Funciones

    public function __toString()
    {
        return "Id Viaje: " . $this->getIdViaje() . "\n" .
            "Destino: " . $this->getDestino() . "\n" .
            "Cantidad Maxima de Pasajeros: " . $this->getCantMaxPasajeros() . "\n" .
            "Empresa: " . $this->getEmpresa() . "\n" .
            "Importe: " . $this->getImporte() . "\n" .
            "Tipo Asiento: " . $this->getTipoAsiento() . "\n" .
            "Responsable: " . $this->getResponsable() . "\n" .
            "Pasajeros: " . $this->pasajerosAString() . "\n";
    }

    public function pasajerosAString()
    {
        $objPasajeros = new pasajero();

        $condicion = " where idviaje = " . $this->getIdViaje();
        $coleccionPasajeros = $objPasajeros->Listar($condicion);
        $retorno = "";

        foreach ($coleccionPasajeros as $pasajero) {
            $retorno .= $pasajero->__toString() . "\n-------------";
        }
        return $retorno;
    }

    public function Cargar($idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte, $tipoAsiento, $idayvuelta)
    {
        $this->idviaje = $idviaje;
        $this->vdestino = $vdestino;
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
        $this->empresa = $empresa;
        $this->responsable = $responsable;
        $this->vimporte = $vimporte;
        $this->tipoAsiento = $tipoAsiento;
        $this->idayvuelta = $idayvuelta;
    }

    public function Buscar($id, $condicion)
    {
        $bd = new BaseDatos();
        $empresa = new empresa();
        $responsable = new responsableV();
        $pasajero = new pasajero();
        $resp = false;
        $consulta = "SELECT * FROM viaje WHERE ";

        if ($condicion == null) 
        {
            $consulta = $consulta . 'idviaje = ' . $id;
        } else {
            $consulta = $consulta . $condicion;
        }

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($consulta)) 
            {
                if ($row2 = $bd->Registro()) 
                {
                    $this->setIdViaje($row2['idviaje']);
                    $this->setDestino($row2['vdestino']);
                    $this->setCantMaxPasajeros($row2['vcantmaxpasajeros']);
                    $empresa->Buscar(($row2['idempresa']));
                    $this->setEmpresa($empresa);
                    $responsable->Buscar(($row2['rnumeroempleado']));
                    $this->setResponsable($responsable);
                    $this->setImporte($row2['vimporte']);
                    $this->setTipoAsiento($row2['tipoAsiento']);
                    $this->setIdaYVuelta($row2['idayvuelta']);
                    $this->setPasajeros($pasajero->Listar("where idviaje = " . $this->getIdViaje()));
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
        $arregloViaje = null;
        $bd = new BaseDatos();
        $empresa = new empresa();
        $responsable = new responsableV();  
        $consultaListar = "SELECT * FROM viaje ";

        if ($condicion != "") 
        {
            $consultaListar = $consultaListar . ' where ' . $condicion;
        }
        $consultaListar .= " order by idviaje ";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($consultaListar)) 
            {
                $arregloViaje = [];

                while ($row2 = $bd->Registro()) 
                {
                    $idviaje = $row2['idviaje'];
                    $vdestino = $row2['vdestino'];
                    $vcantmaxpasajeros = $row2['vcantmaxpasajeros'];
                    $empresa->Buscar($row2['idempresa']);
                    $responsable->Buscar($row2['rnumeroempleado']);
                    $vimporte = $row2['vimporte'];
                    $tipoAsiento = $row2['tipoAsiento'];
                    $idayvuelta = $row2['idayvuelta'];

                    $viaje = new viaje();
                    $viaje->Cargar($idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte, $tipoAsiento, $idayvuelta);
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
        $empresa = $this->getEmpresa();
        $responsable = $this->getResponsable();
        $resp = false;

        $consultaInsertar = "INSERT INTO viaje(idviaje, vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta)
                        VALUES ('" . $this->getIdViaje() . "','" .
            $this->getDestino() . "','" .
            $this->getCantMaxPasajeros() .  "','" .
            $empresa->getIdEmpresa() .  "','" .
            $responsable->getNumeroEmpleado() .  "','" .
            $this->getImporte() .  "','" .
            $this->getTipoAsiento() .  "','" .
            $this->getIdaYVuelta() .  "')";

        if ($bd->Iniciar()) 
        {
            if ($bd->Ejecutar($consultaInsertar)) 
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

    public function Modificar($id)
    {
        $resp = false;
        $bd = new BaseDatos();
        $empresa = $this->getEmpresa();
        $responsable = $this->getResponsable();
        $consultaModifica = "UPDATE viaje 
            SET idviaje = '" . $this->getIdViaje() .
            "', vdestino = '" . $this->getDestino() .
            "', vcantmaxpasajeros = '" . $this->getCantMaxPasajeros() .
            "', idempresa = '" . $empresa->getIdEmpresa() .
            "', rnumeroempleado = '" . $responsable->getNumeroEmpleado() .
            "', vimporte = '" . $this->getImporte() .
            "', tipoAsiento = '" . $this->getTipoAsiento() .
            "', idayvuelta = '" . $this->getIdaYVuelta() .
            "' WHERE idviaje = " . $id;


        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($consultaModifica)) {
                $resp =  true;
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

        if ($bd->Iniciar()) {
            $consultaBorrarViaje = "DELETE FROM viaje WHERE idviaje = " . $this->getIdViaje();
            if ($bd->Ejecutar($consultaBorrarViaje)) {
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
