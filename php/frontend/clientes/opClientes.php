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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/clientes/clientes.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opClientes
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de clientes.
             */
            private $cntView = 0;
            private $idCliente = 0;
            private $imgTitleURL = './img/menu/clientes.png';
            private $Title = 'Clientes';
                        
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idCliente = $_GET['id'];}
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
                     * Esta funcion retorna el valor obtenido del ID de cliente.
                     */
                    return $this->idCliente;
                    }
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */                    
                    
                    $objClientes = new clientes();                    
                    
                    $RegCliente = $objClientes->getRegistro($this->getID());
                                                                                                   
                    if(!empty($RegCliente['idCliente']))
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
                                                    <tr><td>idCliente: </td><td><input id="idCliente" type="text" value="'.$RegCliente['idCliente'].'"></td></tr>
                                                    <tr><td>Status: </td><td><input id="Status" type="text" value="'.$RegCliente['Status'].'"></td></tr>
                                                </table>
                                            </div>
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                    .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>
                                                        <tr><td class="td-panel" width="100px">Apellido Paterno:</td><td><input type= "text" id= "Paterno" required= "required" '.$habCampos.' value= "'.$RegCliente['Paterno'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Apellido Materno:</td><td><input type= "text" required= "required" id= "Materno" '.$habCampos.' value= "'.$RegCliente['Materno'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Nombre: </td><td><input id= "Nombre" type= "text" '.$habCampos.' value= "'.$RegCliente['Nombre'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Nacimiento:</td><td><input id="fNacimiento" required= "required" type= "text" class= "datepicker" value= "'.$RegCliente['fNacimiento'].'" readonly></td></tr>
                                                        <tr><td class="td-panel" width="100px">CURP: </td><td><input id= "curp" type= "text" '.$habCampos.' value= "'.$RegCliente['curp'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Telefono: </td><td><input id= "telFijo" type= "text" '.$habCampos.' value= "'.$RegCliente['telFijo'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Celular: </td><td><input id= "telCel" type= "text" '.$habCampos.' value= "'.$RegCliente['telCel'].'"></td></tr>                                                            
                                                    </table>                                   
                                                </div>                                                    
                                                <div id="pie" class="pie-operativo">'.$objClientes->controlBotones("32", "32", $this->getView()).'</div>                                                                                                                                                                                   
                                            </div>
                                        </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpUsuarios = new opClientes();
    

    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                            $objOpUsuarios->drawUI().
                        '</center>
                    </body>
                </html>';
                    
    echo $HTML;        
?>