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
    class noAutorizado
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la creacion
             * de la interfaz de mensaje a usuario sobre su no autorizacion para
             * ingresar al modulo.
             */
            private $MsgTitulo = '';
            private $MsgCuerpo = '';
            
            public function __construct()
                {
                    /*
                     * Declaracion de constructor para inicializar los elementos
                     * del mensaje a mostrar para usuario;
                     */
                    $this->MsgTitulo = "<b>Er403: MODULO NO AUTORIZADO";
                    $this->MsgCuerpo = "El modulo solicitado no se encuentra disponible en su listado de permisos. ";
                    $this->MsgCuerpo .= "Para mas informacion, por favor contacte al administrador del sistema.";
                    }
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML que sera utilizado para la infertaz
                     * del mensaje a mostrar.
                     */
                    $HTML = '   <div id="noAutorizado" class="notificacion">
                                    <div id="cabecera" class="cabecera-notificacion">'
                                        .'<img align="middle" src="./img/notificaciones/advertencia.png" width="32" height="32"/> '.$this->MsgTitulo.
                                    '</div>
                                    <div id="cuerpo" class="cuerpo-notificacion">'
                                        .$this->MsgCuerpo.
                            '       </div>
                                </div>';
                    
                    return $HTML;
                    }        
            }
    
    $objNoAutorizado = new noAutorizado();
    echo $objNoAutorizado->drawUI();            
?>