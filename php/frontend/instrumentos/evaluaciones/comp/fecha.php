<?php
/*
 * Citadel v1.0.0 Software para atencion ciudadana.
 * PHP v5
 * Autor: Prof. Jesus Antonio Peyrano Luna <antonio.peyrano@live.com.mx>
 * Nota aclaratoria: Este programa se distribuye bajo los terminos y disposiciones
 * definidos en la GPL v3.0, debidamente incluidos en el repositorio original.
 * Cualquier copia y/o redistribucion del presente, debe hacerse con una copia
 * adjunta de la licencia en todo momento.
 * Licencia: http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/instrumentos/evaluaciones/evaluaciones.class.php"); //Se carga la referencia de la clase para manejo de la entidad usuarios.
include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/main/usrctrl.class.php"); //Se carga la referencia de clase para control de accesos.

    function getFecha()
        {
            /*
             * Esta funcion retorna el valor obtenido de fecha de registro.
             */
            $objEvaluaciones = new evaluaciones();

            return $objEvaluaciones->getFecha();
            }

    echo '<input type="text" id="Fecha" value="'.getFecha().'"/>';            
?>