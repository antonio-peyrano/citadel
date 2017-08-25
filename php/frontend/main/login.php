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
    header('Content-Type: text/html; charset=ISO-8859-1'); //Forzar la codificacion a ISO-8859-1.
    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/main/login.class.php"); //Se carga el archivo de clase.

    $objLogin = new login();
    
    $HTMLBody =     '<html>
                        <head>
                        </head>
                        <body>'
                            .$objLogin->UILogin().
                        '</body>
                    </html>';
    
    echo $HTMLBody;    
?>