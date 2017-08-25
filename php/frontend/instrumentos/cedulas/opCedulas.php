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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/instrumentos/cedulas/cedulas.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opCedulas
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de cedulas.
             */
            private $cntView = 0;
            private $idCedula = 0;
            private $imgTitleURL = './img/menu/cedula.png';
            private $Title = 'Cedulas';
                        
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idCedula = $_GET['id'];}
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
                     * Esta funcion retorna el valor obtenido del ID de cedula.
                     */
                    return $this->idCedula;
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

            public function getFecha($Registro)
                {
                    /*
                     * Esta funcion retorna el valor obtenido de fecha de registro.
                     */
                    $objCedulas = new cedulas();
                    
                    if(!empty($Registro['Fecha']))
                        {
                            return '<td><input type= "text" class= "inputform" id= "Fecha" readonly required= "required" value= "'.$Registro['Fecha'].'"></td>';
                            }
                    else
                        {
                            return '<td><input type= "text" class= "inputform" id= "Fecha" readonly required= "required" value= "'.$objCedulas->getFecha().'"></td>';
                            }
                    }
                                        
            public function calcularFolio($Registro)
                {
                    /*
                     * Esta funcion establece el calculo de la clave de la cedula a razon
                     * de los elementos existentes.
                     */
                    global $username, $password, $servername, $dbname;
                    $Clave = "";
                    $objAux = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    
                    $consulta = "SELECT *FROM opCedulas";
                    $dataset = $objAux -> conectar($consulta);
                    $RowCount = mysqli_num_rows($dataset);
                    
                    $now = time(); //Se obtiene la referencia del tiempo actual del servidor.
                    date_default_timezone_set("America/Mexico_City"); //Se establece el perfil del uso horario.
                    $fecha = date("Y/m/d H:i:s",$now); //Se obtiene la referencia compuesta de fecha.                    
                    
                    $parseFecha = explode("/",$fecha);
                    $parseFecha = implode("",$parseFecha);
                    
                    $parseFecha = explode(":",$parseFecha);
                    $parseFecha = implode("",$parseFecha);
                    
                    $parseFecha = explode(" ",$parseFecha);
                    $parseFecha = implode("",$parseFecha);
                    
                    if($Clave == "")
                        {
                            //Si se trata de un nuevo registro, se genera una clave artificial nueva.
                            $Clave = 'CED'.'-'.$parseFecha."-".($RowCount + 1);
                            }
                    
                    if(!empty($Registro['Folio']))
                        {
                            return '<td><input type= "text" class= "inputform" id= "Folio" required= "required" readonly value= "'.$Registro['Folio'].'"></td>';
                            }
                    else
                        {
                            return '<td><input type= "text" class= "inputform" id= "Folio" required= "required" readonly value= "'.$Clave.'"></td>';
                            }
                    }
                                        
            public function drawCBEntidad($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * entidad.
                     */
                    $HTML = '<tr><td class="td-panel" width="100px">Entidad: </td><td><select name= "idEntidad" id= "idEntidad" value= "-1"'.$habilitador.'>
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
                    
                    $objCedulas = new cedulas();                    
                    
                    $RegCedula = $objCedulas->getRegistro($this->getID());
                                                                                                   
                    if(!empty($RegCedula['idCedula']))
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
                                                    <tr><td>idCedula: </td><td><input id="idCedula" type="text" value="'.$RegCedula['idCedula'].'"></td></tr>
                                                    <tr><td>Status: </td><td><input id="Status" type="text" value="'.$RegCedula['Status'].'"></td></tr>
                                                </table>
                                            </div>
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                    .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>
                                                        <tr><td class="td-panel" width="100px">Folio:</td>'.$this->calcularFolio($RegCedula).'</tr>
                                                        <tr><td class="td-panel" width="100px">Fecha:</td>'.$this->getFecha($RegCedula).'</tr>
                                                        <tr><td class="td-panel" width="100px">Horizonte:</td><td><input type= "text" id= "Horizonte" required= "required" '.$habCampos.' value= "'.$RegCedula['Horizonte'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Descripcion:</td><td><input type= "text" id= "Descripcion" required= "required" '.$habCampos.' value= "'.$RegCedula['Descripcion'].'"></td></tr>'
                                                        .$this->drawCBEntidad($RegCedula, $habCampos).
                                                    '</table>                                   
                                                </div>                                                    
                                                <div id="pie" class="pie-operativo">'.$objCedulas->controlBotones("32", "32", $this->getView()).'</div>                                                                                                                                                                                   
                                            </div>
                                        </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpCedulas = new opCedulas();
    

    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                            $objOpCedulas->drawUI().
                        '</center>
                    </body>
                </html>';
                    
    echo $HTML;        
?>