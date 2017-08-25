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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/colonias/colonias.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opColonias
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de colonias.
             */
            private $cntView = 0;
            private $idColonia = 0;
            private $imgTitleURL = './img/menu/colonias.png';
            private $Title = 'Colonias';
                        
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idColonia = $_GET['id'];}
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
                     * Esta funcion retorna el valor obtenido del ID de colonia.
                     */
                    return $this->idColonia;
                    }
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */                    
                    
                    $objColonias = new colonias();                    
                    
                    $RegColonia = $objColonias->getRegistro($this->getID());
                                                                                                   
                    if(!empty($RegColonia['idColonia']))
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
                            
                    $HTMLBody = '      <div id="cntOperativo">
                                            <div id="statsCliente" style="display:none">
                                                <table>
                                                    <tr><td>idColonia: </td><td><input id="idColonia" type="text" value="'.$RegColonia['idColonia'].'"></td></tr>
                                                    <tr><td>Status: </td><td><input id="Status" type="text" value="'.$RegColonia['Status'].'"></td></tr>
                                                </table>
                                            </div>
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                    .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>
                                                        <tr><td class="td-panel" width="100px">Colonia:</td><td><input type= "text" id= "Colonia" required= "required" '.$habCampos.' value= "'.$RegColonia['Colonia'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Codigo Postal:</td><td><input type= "text" required= "required" id= "cp" '.$habCampos.' value= "'.$RegColonia['cp'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Ciudad: </td><td><input id= "Ciudad" type= "text" '.$habCampos.' value= "'.$RegColonia['Ciudad'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Estado: </td><td><input id= "Estado" type= "text" '.$habCampos.' value= "'.$RegColonia['Estado'].'"></td></tr>
                                                    </table>                                   
                                                </div>                                                    
                                                <div id="pie" class="pie-operativo">'.$objColonias->controlBotones("32", "32", $this->getView()).'</div>
                                            </div>
                                        </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpColonias = new opColonias();
    

    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                            $objOpColonias->drawUI().
                        '</center>
                    </body>
                </html>';
                    
    echo $HTML;        
?>