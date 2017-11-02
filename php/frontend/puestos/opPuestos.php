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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/puestos/puestos.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opPuesto
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de puestos.
             */
            private $cntView = 0;
            private $idRelEntPst = 0;
            private $imgTitleURL = './img/menu/puestos.png';
            private $Title = 'Puestos';
            
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idRelEntPst = $_GET['id'];}
                    }
                    
            public function getView()
                {
                    /*
                     * Esta funcion retorna el valor obtenido del modo de visualizacion
                     */
                    return $this->cntView;
                    }
                    
            public function getID()
                {
                    /*
                     * Esta funcion retorna el valor obtenido del ID de la relacion entidad-puesto.
                     */
                     return $this->idRelEntPst;
                    }

            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */
                        
                    $objPuestos = new puestos();
                        
                    $RegPuesto = $objPuestos->getRegistro($this->getID());
                        
                    if(!empty($RegPuesto['idRelEntPst']))
                        {
                            //CASO: VISUALIZACION DE REGISTRO PARA SU EDICION O BORRADO.
                            if($this->getView() == 1)
                                {
                                    //VISUALIZAR.
                                    $habCampos = 'disabled= "disabled"';
                                    }
                            else
                                {
                                    //EDICION.
                                    $habCampos = '';
                                    }
                            }
                    else
                        {
                            //CASO: CREACION DE NUEVO REGISTRO.
                            $habCampos = '';
                            }
                        
                    $HTMLBody = '   <div id="cntOperativo">
                                        <div id="statsPuesto" style="display:none">
                                            <table>
                                                <input type= "text" id= "idPuesto" value="'.$RegPuesto['idPuesto'].'">
                                                <input type= "text" id= "Status" value="'.$RegPuesto['Status'].'">    
                                            </table>
                                        </div>
                                        <div id="infoRegistro" class="operativo">
                                            <div id="cabecera" class="cabecera-operativo">'
                                                .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                            <div id="cuerpo" class="cuerpo-operativo">
                                                <table>
                                                    <tr><td class="td-panel" width="100px">Puesto:</td><td><input type= "text" required= "required" id= "Puesto" '.$habCampos.' value= "'.$RegPuesto['Puesto'].'"></td></tr>'
                                                        .$objPuestos->cargarEntidades($this->getID(), $RegPuesto['idPuesto'], $habCampos).
                                                '</table>
                                            </div>
                                            <div id="pie" class="pie-operativo">'.$objPuestos->controlBotones("32", "32", $this->getView()).'</div>
                                        </div>
                                    </div>';
                    return $HTMLBody;
                    }                    
            }

    $objOpPuestos = new opPuesto();
            
            
    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                        $objOpPuestos->drawUI().
                        '</center>
                    </body>
                </html>';
                        
    echo $HTML;
?>