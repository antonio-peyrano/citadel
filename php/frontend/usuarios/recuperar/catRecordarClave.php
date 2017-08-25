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
    class recordarclave
        {
            /*
             * Esta clase contiene los atributos y procedimientos necesarios para
             * generar la interfaz de usuario correspondiente al recordatorio de
             * clave de usuario por medio de correo electronico.
             */
            private $Correo = '';
            private $Pregunta = '';
            private $Respuesta = '';
            
            public function __construct()            
                {
                    //Declaracion de constructor de clase (Vacio)
                    }
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion genera el codigo HTML que corresponde a la interfaz
                     * grafica del recordatorio de clave usuario.
                     */
                    $DIVHeader = 'Recordatorio de Clave';
                    $DIVBody = '<div id="tag" class="tag">Introduzca los siguientes datos para procesar la solicitud de recuperacion';
                    $DIVBody.= 'de su clave de usuario. El sistema distingue mayusculas y minusculas.</div><br><br>';
                    $DIVBody.= '<center><table>';
                    $DIVBody.= '<tr><td class="td-panel">Correo </td><td><input id="Correo" type="text" value=""></td><td rowspan= "3"><img id="btnBusPregunta" src="./img/grids/view.png" onmouseover="bigImg(this)" onmouseout="normalImg(this)" width="32" height="32" title="Buscar cuenta"/></td></tr>';
                    $DIVBody.= '<tr><td class="td-panel">Pregunta </td><td><div id="divPregunta"><input id="Pregunta" type="text" value=""></div></td></tr>';
                    $DIVBody.= '<tr><td class="td-panel">Respuesta </td><td><input id="Respuesta" type="text" value=""></td></tr>';
                    $DIVBody.= '</table></center>';
                                         
                    $HTML = '   <div id="recordarCorreo" class="operativo">
                                    <div id="cabecera" class="cabecera-operativo">'
                                        .'<img align="middle" src="./img/notificaciones/error.png" width="32" height="32"/> '.$DIVHeader.
                                    '</div>
                                    <div id="cuerpo" class="cuerpo-operativo">'
                                        .$DIVBody.
                                    '</div>
                                    <center><img id="btnEnviarRecordatorio" src="./img/menu/enviar.png" onmouseover="bigImg(this)" onmouseout="normalImg(this)" width="32" height="32" title="Enviar recordatorio"/></center>
                                </div>';
                    
                    return $HTML;                    
                    }                    
            }

    $objRecordarClave = new recordarclave();
    echo $objRecordarClave->drawUI();            
?>