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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/entidades/entidades.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opEntidades
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de entidades.
             */
            private $cntView = 0;
            private $idEntidad = 0;
            private $imgTitleURL = './img/menu/entidades.png';
            private $Title = 'Entidades';
                        
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idEntidad = $_GET['id'];}
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
                     * Esta funcion retorna el valor obtenido del ID de entidad.
                     */
                    return $this->idEntidad;
                    }

            public function cargarEntidades()
                {
                    /*
                     * Esta funcion establece la carga del conjunto de registros de entidades.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idTEntidad, tEntidad FROM catTEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }
                                        
            public function drawCBTEntidad()
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * tipos de entidad.
                     */
                    $HTML = '<tr><td class="td-panel" width="100px">Tipo de Entidad: </td><td><select name= "idTEntidad" id= "idTEntidad" value= "-1">
                                <option value=-1>Seleccione</option>';
                    
                    $subconsulta = $this->cargarEntidades();
                    $RegUsuarios = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                    
                    while($RegUsuarios)
                        {
                            $HTML .= '<option value='.$RegUsuarios['idTEntidad'].'>'.$RegUsuarios['tEntidad'].'</option>';
                            $RegUsuarios = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                    
                    $HTML .= '</select></td></tr>';
                    return $HTML;
                    }
                                        
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */                    
                    
                    $objEntidades = new entidades();                    
                    
                    $RegCliente = $objEntidades->getRegistro($this->getID());
                                                                                                   
                    if(!empty($RegCliente['idEntidad']))
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
                                                    <tr><td>idEntidad: </td><td><input id="idEntidad" type="text" value="'.$RegCliente['idEntidad'].'"></td></tr>
                                                    <tr><td>Status: </td><td><input id="Status" type="text" value="'.$RegCliente['Status'].'"></td></tr>
                                                </table>
                                            </div>
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                    .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>
                                                        <tr><td class="td-panel" width="100px">Entidad:</td><td><input type= "text" id= "Entidad" required= "required" '.$habCampos.' value= "'.$RegCliente['Entidad'].'"></td></tr>'
                                                        .$this->drawCBTEntidad().
                                                    '</table>                                   
                                                </div>                                                    
                                                <div id="pie" class="pie-operativo">'.$objEntidades->controlBotones("32", "32", $this->getView()).'</div>                                                                                                                                                                                   
                                            </div>
                                        </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpUsuarios = new opEntidades();
    

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