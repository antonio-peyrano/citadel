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
    class ERR405
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la creacion
             * de la interfaz de mensaje a usuario sobre el error de direccionamiento
             * en el directorio de recursos.
             */
            private $MsgTitulo = '';
            private $MsgCuerpo = '';
            
            public function __construct()
                {
                    /*
                     * Declaracion de constructor para inicializar los elementos
                     * del mensaje a mostrar para usuario;
                     */
                    $this->MsgTitulo = "<b>Er405: PARAMETROS INCOMPLETOS";
                    $this->MsgCuerpo = "El sistema no ha podido procesar su solicitud debido a que existen datos ";
                    $this->MsgCuerpo .= "cuyo valor no ha sido definido. Por favor contacte al administrador.";
                    }
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML que sera utilizado para la infertaz
                     * del mensaje a mostrar.
                     */
                    $HTML = '   <div id="error405" class="notificacion">
                                    <div id="cabecera" class="cabecera-notificacion">'
                                        .'<img align="middle" src="./img/notificaciones/error.png" width="32" height="32"/> '.$this->MsgTitulo.
                                    '</div>
                                    <div id="cuerpo" class="cuerpo-notificacion">'
                                        .$this->MsgCuerpo.
                            '       </div>
                                </div>';
                    
                    return $HTML;
                    }        
            }
    
    $objERR405 = new ERR405();
    echo $objERR405->drawUI();
?>