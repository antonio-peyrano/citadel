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
        
    if(isset($_GET['identidad']))
        {
            //Este archivo sirve como pasarela para la generacion de
            //la imagen que corresponde a la grafica.
            $idEntidad = $_GET['identidad'];
            echo '<img src="./php/backend/dal/utilidades/dalGraficaCircular.class.php\?identidad='.$idEntidad.'"/>';
            }
?>