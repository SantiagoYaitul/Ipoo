<?php

class empresa
{
    private $idempresa;
    private $enombre;
    private $edireccion;

    public function __construct()
    {
        $this->idempresa = 0;
        $this->enombre = "";
        $this->edireccion = "";
    }

    //Observadores

    public function getIdEmpresa()
    {
        return $this->idempresa;
    }

    public function getNombre()
    {
        return $this->enombre;
    }

    public function getDireccion()
    {
        return $this->edireccion;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeOp;
    }

    //Modificadores

    public function setIdEmpresa($idempresa)
    {
        $this->idempresa = $idempresa;
    }

    public function setNombre($enombre)
    {
        $this->enombre = $enombre;
    }

    public function setDireccion($edireccion)
    {
        $this->edireccion = $edireccion;
    }

    public function setMensajeOperacion($mensajeOp)
    {
        $this->mensajeOp = $mensajeOp;
    }

    //Funciones

    public function __toString()
    {
        return "Id Empresa: " . $this->getIdEmpresa() . "\n" .
            "Nombre: " . $this->getNombre() . "\n" .
            "Direccion: " . $this->getDireccion() . "\n";
    }

    public function Cargar($idempresa, $enombre, $edireccion)
    {
        $this->setIdEmpresa($idempresa);
        $this->setNombre($enombre);
        $this->setDireccion($edireccion);
    }

    public function Buscar($id)
    {
        $bd = new BaseDatos;
        $resp = false;
        $consulta = "SELECT * FROM empresa WHERE idempresa = " . $id;

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($consulta)) {
                if ($row2 = $bd->Registro()) {
                    $this->setIdEmpresa($id);
                    $this->setNombre($row2['enombre']);
                    $this->setDireccion($row2['edireccion']);
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
        $bd = new BaseDatos();
        $consultaLista = "SELECT * FROM empresa ";
        $arregloEmpresa = null;
        if ($condicion != "") {
            $consultaLista = $consultaLista . ' where ' . $condicion;
        }

        $consultaLista .= " order by idempresa";
        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($consultaLista)) {
                $arregloEmpresa = array();
                while ($row2 = $bd->Registro()) {
                    $idempresa = $row2['idempresa'];
                    $enombre = $row2['enombre'];
                    $edireccion = $row2['edireccion'];

                    $empresa = new empresa();
                    $empresa->Cargar($idempresa, $enombre, $edireccion);
                    array_push($arregloEmpresa, $empresa);
                }
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $arregloEmpresa;
    }

    public function Insertar()
    {
        $bd = new BaseDatos();
        $resp = false;
        if ($this->getIdEmpresa() == null) {
            $consultaInsertar = "INSERT INTO empresa(enombre, edireccion)
                            VALUES('" . $this->getNombre() . ", '" . $this->getDireccion() . "')";
        } else {
            $consultaInsertar = "INSERT INTO empresa(idempresa, enombre, edireccion)
                                VALUES('" . $this->getIdEmpresa() . "', '" . $this->getNombre() . "','" . $this->getDireccion() . "')";
        }

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($consultaInsertar)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($bd->getError());
            }
        } else {
            $this->setMensajeOperacion($bd->getError());
        }
        return $resp;
    }

    public function Modificar($idAntiguo = "")
    {
        $resp = false;
        $bd = new BaseDatos();
        if ($idAntiguo == null) {
            $queryModifica = "UPDATE empresa 
            SET enombre = '" . $this->getNombre() .
                "', edireccion = '" . $this->getDireccion() .
                "' WHERE idempresa = " . $this->getIdempresa();
        } else {
            $queryModifica = "UPDATE empresa 
            SET idempresa = " . $this->getIdempresa() .
                ", enombre = '" . $this->getNombre() .
                "', edireccion = '" . $this->getDireccion() .
                "' WHERE idempresa = " . $idAntiguo;
        }
        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($queryModifica)) {
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
            $consultaBorrar = "DELETE FROM empresa WHERE idempresa = " . $this->getIdEmpresa();
            if ($bd->Ejecutar($consultaBorrar)) {
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
