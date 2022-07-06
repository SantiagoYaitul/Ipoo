<?php
include_once '../datos/BaseDatos.php';
include_once '../datos/empresa.php';
include_once '../datos/viaje.php';
include_once '../datos/responsableV.php';
include_once '../datos/pasajero.php';

// TODO ADD functions to add pasajero y responsable.
// Each have different constraints. Make it so it's only 
// possible to create if constraints are true.

// Funcion visual de menu
function menu()
{
    echo "\n" .
        "+================================+\n" .
        "         MENU PRINCIPAL         \n" .
        "================================\n" .
        " 1. Agregar una empresa         \n" .
        " 2. Modificar una empresa       \n" .
        " 3. Eliminar una empresa        \n" .
        " 4. Ver empresas                \n" .
        "================================\n" .
        " 5. Agregar un viaje            \n" .
        " 6. Modificar un viaje          \n" .
        " 7. Eliminar un viaje           \n" .
        " 8. Ver viaje                   \n" .
        "================================\n" .
        " 9. Ingresar responsable        \n" .
        " 10. Modificar responsable      \n" .
        " 11. Eliminar un responsable    \n" .
        " 12. Ver responsables           \n" .
        "================================\n" .
        " 13. Ingresar pasajero          \n" .
        " 14. Modificar pasajero         \n" .
        " 15. Eliminar un pasajero       \n" .
        " 16. Ver pasajeros              \n" .
        "================================\n" .
        " 0. Salir                       \n" .
        "+================================+\n\n";
}

function ingresarEmpresa()
{
    $empresa = new empresa();

    $id = readline(" Id de la empresa: ");
    $nombre = readline("Nombre de la empresa: ");
    $dir = readline("Direccion de la empresa: ");

    if ($id == null) {
        $id = 1;
        while ($empresa->Buscar($id)) {
            $id++;
        }
    }
        if (!$empresa->Buscar($id)) {
            $empresa->Cargar($id, $nombre, $dir);

            $respuesta = $empresa->Insertar();
            if ($respuesta == true) {
                echo "\n\t   La empresa fue ingresada en la BD.\n" .
                    "\t========================================\n";
            } else {
                echo $empresa->getMensajeOperacion();
            }
        } else {
            echo "\nYa existe una empresa con ese ID.\n";
        }
}

function modificarEmpresa()
{
    $empresa = new empresa();

    $id = readline("Ingrese el id de la empresa a modificar: ");
    $respuesta = $empresa->Buscar($id);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $nombre = readline("Nombre de la empresa: ");
        $direccion = readline("Direccion de la empresa: ");

        $empresa->setNombre($nombre);
        $empresa->setDireccion($direccion);
        $respuesta = $empresa->Modificar();

        if ($respuesta) {

            echo "\n Los datos de la empresa fueron modificados correctamente. \n" .
                "\t==============================================\n";
        } else {
            echo "\n No se pudo modificar la empresa";
        }
    } else {
        echo "No se pudo encontrar la empresa con id = " . $id . "\n";
    }
}

function eliminarEmpresa()
{

    $empresa = new empresa();
    $viaje = new viaje();

    $id = readline("Ingrese el id de la empresa a eliminar: ");
    $respuesta = $empresa->Buscar($id);

    if ($respuesta) {
        $respuesta = $empresa->Eliminar();
        if ($respuesta) {
            echo "\n\t   La empresa fue eliminada de la BD.\n" .
                "\t=========================================\n";
            if ($viaje->Buscar(null, "idempresa = " . $id)) {
                $eliminarEmpresa = readline("La empresa esta a cargo de un viaje. Quiere eliminar el viaje junto a la empresa? (s/n) ");
                if ($eliminarEmpresa == "s" && $viaje->getPasajeros() == []) {
                    $viaje->Eliminar();
                    $empresaEncontrada = false;
                } else {
                    $empresaEncontrada = true;
                }
            } else {
                $empresaEncontrada = false;
            }

            if (!$empresaEncontrada) {
                $respuesta = $empresa->Eliminar();
                if ($respuesta) {
                    echo "\n\t   La empresa fue eliminada de la BD.\n" .
                        "\t=========================================\n";
                } else {
                    echo "\nNo se pudo eliminar la empresa.\n";
                }
            } else {
                echo "\nNo se pudo eliminar la empresa.\n";
                echo "\nNo se puede eliminar un responsable a cargo de un viaje sin eliminar el viaje.\n";
            }
        } else {
            echo "No se pudo encontrar la empresa con id = " . $id . ".\n";
        }
    }
}


function mostrarEmpresa()
{
    $empresa = new empresa();

    $resp = readline("Mostrar todas las empresas? (s/n) ");

    if ($resp == 's') {
        $colEmpresas = $empresa->Listar("");

        echo "-------------------------------------------------------\n";
        foreach ($colEmpresas as $empresa) {

            echo  $empresa;
            echo "-------------------------------------------------------\n";
        }
    } else {
        $id = readline("Ingrese el id de la empresa: ");
        if (is_int($id)) {
            $respuesta = $empresa->Buscar($id);
            if ($respuesta) {
                echo  $empresa;
            } else {
                echo "No se pudo encontrar la empresa.";
            }
        } else {
            echo "ID ingresado no es valido.\n";
        }
    }
}


function ingresarViaje()
{

    $viaje = new viaje();
    $empresa = new empresa();
    $responsable = new responsableV();

    $id = readline(" Id del viaje: ");
    $destino = readline("Destino del viaje: ");
    $cantmax = readline("Cantidad maxima de pasajeros: ");
    $idempresa = readline("ID de la empresa a cargo: ");
    $nempleado = readline("Numero de empleado responsable: ");
    $importe = readline("Importe: ");
    $tipoAsiento = readline("Tipo de asiento (Primera clase o no, semicama o cama): ");
    $idayvuelta = readline("Ida y vuelta? ");

    if ($id == null) {
        $id = 1;
        while ($viaje->Buscar($id, null)) {
            $id++;
        }
    }

    if (!$viaje->Buscar($id, "")) {
        if (!$viaje->Buscar(null,  "vdestino = '" . $destino . "'")) {
            if ($empresa->Buscar($idempresa) && $responsable->Buscar($nempleado)) {
                $viaje->Cargar($id, $destino, $cantmax, $empresa, $responsable, $importe, $tipoAsiento, $idayvuelta);

                $respuesta = $viaje->Insertar();
                if ($respuesta == true) {
                    echo "\n\t   El viaje fue ingresado en la BD.\n" .
                        "\t======================================\n";
                } else {
                    echo $viaje->getMensajeOperacion();
                }
            } else {

                echo "\nNo existe la empresa o responsable a cargo.\n";
            }
        } else {
            echo "\nExiste un viaje al destino.\n";
        }
    } else {
        echo "\nYa existe un viaje con ese ID.\n";
    }
}


function modificarViaje()
{
    $viaje = new viaje();
    $empresa = new empresa();
    $responsable = new responsableV();

    $id = readline("Ingrese el id del viaje a modificar: ");

    $respuesta = $viaje->Buscar($id, null);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $destino = readline("Destino del viaje: ");
        $cantmax = readline("Cantidad maxima de pasajeros: ");
        $idempresa = readline("ID de la empresa a cargo: ");
        $nempleado = readline("Numero de empleado responsable: ");
        $importe = readline("Importe: ");
        $tipoAsiento = readline("Tipo de asiento (Primera clase o no, semicama o cama): ");
        $idayvuelta = readline("Ida y vuelta? ");
        if (!$viaje->Buscar(null, "vdestino = '" . $destino . "'")) {
            if ($empresa->Buscar($idempresa) && $responsable->Buscar($nempleado)) {
                $viaje->setDestino($destino);
                $viaje->setCantMaxPasajeros($cantmax);
                $viaje->setEmpresa($empresa);
                $viaje->setResponsable($responsable);
                $viaje->setImporte($importe);
                $viaje->setTipoAsiento($tipoAsiento);
                $viaje->setIdaYVuelta($idayvuelta);

                $respuesta = $viaje->Modificar($id);
                if ($respuesta) {
                    echo "\n\t   El viaje fue modificado correctamente.\n" .
                        "\t============================================\n";
                } else {
                    echo "\n No se pudo modificar el viaje. \n";
                }
            } else {
                echo "\n No existe la empresa o responsable.\n";
            }
        } else {
            echo "\nExiste un viaje al destino.\n";
        }
    } else {
        echo "No se pudo encontrar el viaje con id = " . $id . ".\n";
    }
}

function eliminarViaje()
{
    $viaje = new viaje();

    $id = readline("Ingrese el id del viaje a eliminar: ");
    $resp = $viaje->Buscar($id, null);
    if($viaje->getPasajeros() == [])
    {

        if ($resp) 
        {
        $resp = $viaje->Eliminar();
            if ($resp) 
            {
            echo "\n\t   El viaje fue eliminado de la BD.\n".
                "\t=========================================\n";
            } else {
            echo "\nNo se pudo eliminar el viaje.\n";
            }
        } else {
        echo "No se pudo encontrar el viaje con id = " . $id . ".\n";
        }
    } else {
        echo "El viaje seleccionado contiene pasajeros en la base de datos, antes de eliminar el viaje debe eliminar a los pasajeros";
    }
}
function mostrarViaje()
{
    $viaje = new viaje();

    $resp = readline("Mostrar todos los viajes? (s/n) ");

    if ($resp == 's') {
        $colViajes = $viaje->Listar("");
        foreach ($colViajes as $viaje) {

            echo $viaje;
            echo "\n-------------------------------------------------------\n";
        }
    } else {
        $id = readline("Ingrese el id del viaje: ");
        if (is_int($id)) {
            $respuesta = $viaje->Buscar($id, null);
            if ($respuesta) {
                echo $viaje;
            } else {
                echo "No se pudo encontrar el viaje.";
            }
        } else {
            echo "ID ingresado no es valido.\n";
        }
    }
}


function ingresarResponsable()
{
    $responsable = new responsableV();

    $id = readline(" Numero de empleado: ");
    $numLic = readline("Numero de licencia: ");
    $nombre = readline("Nombre del responsable: ");
    $apellido = readline("Apellido del responsable: ");

    if ($id == null) {
        $id = 1;
        while ($responsable->Buscar($id)) {
            $id++;
        }
    }

    if (!$responsable->Buscar($id)) {
        $responsable->Cargar($id, $numLic, $nombre, $apellido);

        $respuesta = $responsable->Insertar();
        if ($respuesta) {
            echo "\n\t   El responsable fue ingresada en la BD.\n" .
                "\t========================================\n";
        } else {
            echo $responsable->getMensajeOperacion();
        }
    } else {
        echo "\nYa existe un responsable con ese ID.\n";
    }
}


function modificarResponsable()
{
    $responsable = new responsableV();

    $numE = readline("Ingrese el numero de empleado del responsable a modificar: ");
    $respuesta = $responsable->Buscar($numE);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $numLic = readline("Numero de licencia: ");
        $nombre = readline("Nombre del responsable: ");
        $apellido = readline("Apellido del responsable: ");

        $responsable->setLicencia($numLic);
        $responsable->setNombre($nombre);
        $responsable->setApellido($apellido);


        $respuesta = $responsable->Modificar($numE);
        if ($respuesta) {
            echo "\n\t   El responsable fue modificada correctamente.\n" .
                "\t==============================================\n";
        } else {
            echo "\nNo se pudo modificar el responsable.\n";
        }
    } else {
        echo "No se pudo encontrar el responsable con numero de empleado: " . $numE . "\n";
    }
}

function eliminarResponsable()
{
    $responsable = new responsableV();
    $viaje = new viaje();
    $eliminarViaje = "n";

    $numE = readline("Ingrese el numero de empleado del responsable a eliminar: ");
    $respuesta = $responsable->Buscar($numE);

    if ($respuesta) {
        if ($viaje->Buscar(null, "rnumeroempleado = " . $numE)) {
            $eliminarViaje = readline("El responsable esta a cargo de un viaje. Quiere eliminar el viaje junto al responsable? (s/n) ");
            if ($eliminarViaje == "s" && $viaje->getPasajeros() == []) {
                $viaje->Eliminar();
                $viajeEncontrado = false;
                
            } else {
                $viajeEncontrado = true;
            }
        } else {
            $viajeEncontrado = false;
        }

        if (!$viajeEncontrado) {
            $respuesta = $responsable->Eliminar();
            if ($respuesta) {
                echo "\n\t   El responsable fue eliminado de la BD.\n" .
                    "\t=========================================\n";
            } else {
                echo "\nNo se pudo eliminar el responsable.\n";
            }
        } else {
            echo "\nNo se pudo eliminar el responsable.\n";
            echo "\nNo se puede eliminar un responsable a cargo sin eliminar el viaje.\n";
        }
    } else {
        echo "No se pudo encontrar el responsable con numero de empleado: " . $numE . ".\n";
    }
}

function mostrarResponsable()
{
    $responsable = new responsableV();

    $resp = readline("Mostrar todos los responsables? (s/n) ");

    if ($resp == 's') {
        $colResponsables = $responsable->Listar("");

        echo "-------------------------------------------------------";
        foreach ($colResponsables as $responsable) {

            echo $responsable;
            echo "-------------------------------------------------------";
        }
    } else {
        $numE = readline("Ingrese el numero de empleado: ");
        if (is_numeric($numE)) {
            $respuesta = $responsable->Buscar($numE);
            if ($respuesta) {
                echo $responsable;
            } else {
                echo "No se pudo encontrar el responsable.";
            }
        } else {
            echo "El valor ingresado no es valido.\n";
        }
    }
}

// Pasajeros
function ingresarPasajero()
{
    $pasajero = new pasajero();
    $viaje = new viaje();

    $dni = readline("Documento de pasajero: ");
    $nombre = readline("Nombre: ");
    $apellido = readline("Apellido: ");
    $telefono = readline("Telefono: ");
    $idviaje = readline("Id del viaje: ");

    if ($viaje->Buscar($idviaje, null)) {
        if (!$pasajero->Buscar($dni)) {
            $pasajero->Cargar($nombre, $apellido, $dni, $telefono, $viaje);

            $respuesta = $pasajero->Insertar();
            if ($respuesta == true) {
                echo "\n\t   La pasajero fue ingresado en la BD.\n" .
                    "\t========================================\n";
            } else {
                echo $pasajero->getMensajeOperacion();
            }
        } else {
            echo "\nYa existe un pasajero con ese documento.\n";
        }
    } else {
        echo "El viaje no existe.\n";
    }
}

function modificarPasajero()
{
    $pasajero = new pasajero();
    $viaje = new viaje();

    $dni = readline("Ingrese el documento del pasajero a modificar: ");
    if (is_numeric($dni)) {
        $respuesta = $pasajero->Buscar($dni);
        if ($respuesta) {
            echo "Ingrese los nuevos datos.\n";
            $nuevodni = readline("Documento de pasajero: ");
            $nombre = readline("Nombre: ");
            $apellido = readline("Apellido: ");
            $telefono = readline("Telefono: ");
            $idviaje = readline("Id del viaje: ");

            if ($viaje->Buscar($idviaje, null)) {
                if ($nuevodni != null) {
                    if (!$pasajero->Buscar($nuevodni)) {
                        $pasajero->Cargar($nombre, $apellido, $nuevodni, $telefono, $viaje);
                    } else {
                        echo "Ya existe un pasajero con ese documento.\n";
                    }
                } else {
                    $pasajero->setNombre($nombre);
                    $pasajero->setApellido($apellido);
                    $pasajero->setTelefono($telefono);
                    $pasajero->setViaje($viaje);
                }

                $respuesta = $pasajero->Modificar($dni);
                if ($respuesta) {
                    echo "\n\t   El pasajero fue modificado correctamente.\n" .
                        "\t==============================================\n";
                } else {
                    echo "\nNo se pudo modificar el pasajero.\n";
                }
            } else {
                echo "El viaje no existe.\n";
                echo "\nNo se pudo modificar el pasajero.\n";
            }
        } else {
            echo "No se pudo encontrar el pasajero de documento: " . $dni . ".\n";
        }
    } else {
        echo "El documento ingresado es incorrecto.\n";
    }
}

function eliminarPasajero()
{
    $pasajero = new pasajero();

    $dni = readline("Ingrese el documento del pasajero a eliminar: ");

    if (is_numeric($dni)) {
        $respuesta = $pasajero->Buscar($dni);

        if ($respuesta) {
            $respuesta = $pasajero->Eliminar();
            if ($respuesta) {
                echo "\n\t   El pasajero fue eliminado de la BD.\n" .
                    "\t=========================================\n";
            } else {
                echo "\nNo se pudo eliminar el pasajero.\n";
            }
        } else {
            echo "No se pudo encontrar el pasajero el documento: " . $dni . ".\n";
        }
    } else {
        echo "El documento ingresado es incorrecto.\n";
    }
}

function mostrarPasajero()
{
    $pasajero = new pasajero();

    $resp = readline("Mostrar todos los pasajeros? (s/n) ");

    if ($resp == 's') {
        $colPasajeros = $pasajero->Listar("");

        echo "-------------------------------------------------------";
        foreach ($colPasajeros as $pasajero) {

            echo $pasajero;
            echo "-------------------------------------------------------";
        }
    } else {
        $numE = readline("Ingrese documento del pasajero: ");
        if (is_numeric($numE)) {
            $respuesta = $pasajero->Buscar($numE);
            if ($respuesta) {
                echo $pasajero;
            } else {
                echo "No se pudo encontrar el pasajero.";
            }
        } else {
            echo "El documento ingresado no es valido.\n";
        }
    }
}

// Switch de opciones para guiar al usuario con cada funcion.
function opciones()
{
    menu();
    $resp = false;

    $opcion = readline("Elija una opcion: ");
    switch ($opcion) {
        case 0:
            $resp = false;
            break;
        case 1:
            ingresarEmpresa();
            break;
        case 2:
            modificarEmpresa();
            break;
        case 3:
            eliminarEmpresa();
            break;
        case 4:
            mostrarEmpresa();
            break;
        case 5:
            ingresarViaje();
            break;
        case 6:
            modificarViaje();
            break;
        case 7:
            eliminarViaje();
            break;
        case 8:
            mostrarViaje();
            break;
        case 9:
            ingresarResponsable();
            break;
        case 10:
            modificarResponsable();
            break;
        case 11:
            eliminarResponsable();
            break;
        case 12:
            mostrarResponsable();
            break;
        case 13:
            ingresarPasajero();
            break;
        case 14:
            modificarPasajero();
            break;
        case 15:
            eliminarPasajero();
            break;
        case 16:
            mostrarPasajero();
            break;
        default:
            echo "Opcion incorrecta. Tiene que ser un numero entre 1 y 16.";
            $resp = true;
    }

    echo "\n";
    if ($opcion != 0 && ($opcion > 0 && $opcion <= 16)) {
        $resp = 's' == readline("Realizar otra operacion? (s/n) ");
    }

    return $resp;
}

// While loop para seleccionar otra opcion si el usuario lo desea.
$resp = true;
while ($resp) {
    $resp = opciones();
}

echo "===========================" .
    "\n\tSaliendo...\n" .
    "===========================";
