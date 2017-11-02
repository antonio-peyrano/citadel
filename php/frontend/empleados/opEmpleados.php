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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/empleados/empleados.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opEmpleados
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de empleados.
             */
            private $cntView = 0;
            private $idEmpleado = 0;
            private $imgTitleURL = './img/menu/empleados.png';
            private $Title = 'Empleados';
                        
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idEmpleado = $_GET['id'];}
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
                     * Esta funcion retorna el valor obtenido del ID de empleado.
                     */
                    return $this->idEmpleado;
                    }

            public function cargarColonia($idColonia)
                {
                    /*
                     * Esta funcion establece la carga del registro de colonia.
                     */
                     global $username, $password, $servername, $dbname;
                        
                     $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                     $consulta = 'SELECT CONCAT(idColonia,\'-\', Colonia) AS Colonia FROM catColonias WHERE Status=0 AND idColonia='.$idColonia; //Se establece el modelo de consulta de datos.
                     $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                     $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                     return $Registro['Colonia'];
                    }
                    
            public function cargarEntidades()
                {
                    /*
                     * Esta funcion establece la carga del conjunto de registros de entidades.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }
                    
            public function drawCBEntidad($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * entidad.
                     */
                    $HTML = '<tr><td class="td-panel" width="100px" colspan="3">Entidad: <select class="inputform" name= "idEntidad" id= "idEntidad" value= "-1"'.$habilitador.'>
                                <option value=-1>Seleccione</option>';
                        
                    $subconsulta = $this->cargarEntidades();
                    $RegCedulas = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                        
                    if($Registro['idEntidad'] == '-2')
                        {
                            $HTML .= '<option value=-2 selected>Global</option>';
                            }
                    else
                        {
                            $HTML .= '<option value=-2>Global</option>';
                            }
                        
                    while($RegCedulas)
                        {
                            if($Registro['idEntidad'] == $RegCedulas['idEntidad'])
                                {
                                    //Si el item fue previamente marcado, se selecciona en el codigo.
                                    $HTML .= '<option value='.$RegCedulas['idEntidad'].' selected>'.$RegCedulas['Entidad'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se escribe la secuencia base de codigo.
                                    $HTML .= '<option value='.$RegCedulas['idEntidad'].'>'.$RegCedulas['Entidad'].'</option>';
                                    }
                            $RegCedulas = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                        
                    $HTML .= '</select></td></tr>';
                    return $HTML;
                    }
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */                    
                    
                    $objEmpleados = new empleados();                    
                    
                    $RegEmpleado = $objEmpleados->getRegistro($this->getID());
                                                                                                   
                    if(!empty($RegEmpleado['idEmpleado']))
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
                                                    <tr><td>idEmpleado: </td><td><input id="idEmpleado" type="text" value="'.$RegEmpleado['idEmpleado'].'"></td></tr>
                                                    <tr><td>Status: </td><td><input id="Status" type="text" value="'.$RegEmpleado['Status'].'"></td></tr>
                                                </table>
                                            </div>
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                    .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>
                                                        <tr><td class="td-panel" width="100px">Apellido Paterno: <input class="inputform" type= "text" id= "Paterno" required= "required" '.$habCampos.' value= "'.$RegEmpleado['Paterno'].'"></td>
                                                            <td class="td-panel" width="100px">Apellido Materno: <input class="inputform" type= "text" required= "required" id= "Materno" '.$habCampos.' value= "'.$RegEmpleado['Materno'].'"></td>                                                        
                                                            <td class="td-panel" width="100px">Nombre: <input class="inputform" id= "Nombre" type= "text" '.$habCampos.' value= "'.$RegEmpleado['Nombre'].'"></td>
                                                        </tr>
                                                        <tr><td class="td-panel" width="100px">Nacimiento: <input id="fNacimiento" required= "required" type= "text" class= "datepicker" value= "'.$RegEmpleado['fNacimiento'].'" readonly></td>
                                                            <td class="td-panel" width="100px" colspan="2">Calle: <input class="inputform" size="30" id= "Calle" type= "text" '.$habCampos.' value= "'.$RegEmpleado['Calle'].'"></td>
                                                        </tr>
                                                        <tr><td class="td-panel" width="100px">No Exterior: <input class="inputform" size="4" id= "nExt" type= "text" '.$habCampos.' value= "'.$RegEmpleado['nExt'].'"></td>
                                                            <td class="td-panel" width="100px">No Interior: <input class="inputform" size="4" id= "nInt" type= "text" '.$habCampos.' value= "'.$RegEmpleado['nInt'].'"></td>
                                                        </tr>
                                                        <tr><td class="td-panel" width="100px" colspan="3">Colonia: <input size="50" class="inputform" id="emp_cbidColonia" type= "text" '.$habCampos.' value= "'.$this->cargarColonia($RegEmpleado['idColonia']).'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">CURP: <input class="inputform" id= "curp" type= "text" '.$habCampos.' value= "'.$RegEmpleado['curp'].'"></td>
                                                            <td class="td-panel" width="100px">RFC: <input class="inputform" id= "rfc" type= "text" '.$habCampos.' value= "'.$RegEmpleado['rfc'].'"></td>
                                                        </tr>
                                                        <tr><td class="td-panel" width="100px">Telefono: <input class="inputform" id= "telFijo" type= "text" '.$habCampos.' value= "'.$RegEmpleado['telFijo'].'"></td>
                                                            <td class="td-panel" width="100px">Celular:  <input class="inputform" id= "telCel" type= "text" '.$habCampos.' value= "'.$RegEmpleado['telCel'].'"></td>
                                                        </tr>'
                                                            .$this->drawCBEntidad($RegEmpleado, $habCampos).       
                                                    '</table>                                   
                                                </div>                                                    
                                                <div id="pie" class="pie-operativo">'.$objEmpleados->controlBotones("32", "32", $this->getView()).'</div>                                                                                                                                                                                   
                                            </div>
                                        </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpEmpleados = new opEmpleados();
    

    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                            $objOpEmpleados->drawUI().
                        '</center>
                    </body>
                </html>';
                    
    echo $HTML;        
?>