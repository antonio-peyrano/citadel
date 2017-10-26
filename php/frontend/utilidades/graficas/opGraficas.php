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

    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php");
    
    $Periodo = '';
    
    $now = time();
    $Periodo = date("Y",$now);
              
    echo '  <html>
                <head>
                    <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                    <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                </head>
                <body>
                    <center>
                        <table class= "queryTable">
                            <tr><th colspan= "2" class= "queryHeader">Seleccione los criterios de consulta</th></tr>
                            <tr><td class="dgRowsaltTR" width="100px">Ver datos graficados por: <br>
                                    <input type="radio" id="critProceso" name="criterios" value="Proceso">Proceso<br>
                                    <input type="radio" id="critEntidad" name="criterios" value="Entidad">Entidad<br>
                                </td>
                            <tr><td class="dgRowsaltTR" width="100px"><div id= "tagCriterio">
                            </td></tr>';
    echo '                  </td></div></tr>
                            <tr><td class= "dgRowsnormTR">Periodo<input type= "text" id= "Periodo" value= "'.$Periodo.'"></td></tr>    
                            <tr><td><div id="grafica_barras"></div></td></tr>
                            <tr><td class= "dgPagRow"><a href="#" onclick=""><img align= "right" src= "./img/busquedas.png" width= "25" height= "25" alt= "Consultar" id= "btnConsultar"/></a></td></tr>
                        </table>
                    </center>
                </body>
            </html>';
?>