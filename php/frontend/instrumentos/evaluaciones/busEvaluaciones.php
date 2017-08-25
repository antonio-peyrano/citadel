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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/instrumentos/evaluaciones/evaluaciones.class.php"); //Se carga la referencia de la clase para manejo de la entidad usuarios.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/main/usrctrl.class.php"); //Se carga la referencia de clase para control de accesos.
    
    class busEvaluaciones
        {
            private $Sufijo = "eva_";
            private $imgTitleURL = './img/menu/evaluaciones.png';
            private $Title = 'Evaluaciones';
                        
            public function __construct()
                {
                    //Declaracion de constructor de clase (VACIO)
                    }

            public function getTitulo()
                {
                    //Esta funcion obtiene la referencia almacenada en el atributo titulo.
                    return $this->Title;
                    }

            public function getImg()
                {
                    //Esta funcion obtiene la referencia almacenada en el atributo imagen de titulo.
                    return $this->imgTitleURL;
                    }
                                        
            public function cargarCedulas()
                {
                    /*
                     * Esta funcion establece la carga del conjunto de registros de cedulas.
                     */
                    global $username, $password, $servername, $dbname;

                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idCedula, Folio, Descripcion FROM opCedulas WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }

            public function calcularFolio()
                {
                    /*
                     * Esta funcion establece el calculo de la clave de la evaluacion a razon
                     * de los elementos existentes.
                     */
                    global $username, $password, $servername, $dbname;
                    $Clave = "";
                    $objAux = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    
                    $consulta = "SELECT *FROM opEvaluaciones";
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
                            $Clave = 'EVA'.'-'.$parseFecha."-".($RowCount + 1);
                            }
                    
                    return $Clave;
                    }

            public function getFecha()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de fecha de registro.
                     */
                    $objEvaluaciones = new evaluaciones();
                    
                    return $objEvaluaciones->getFecha();
                    }
                                        
            public function drawCBCedulas()
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * cedulas.
                     */
                    $HTML = '<tr><td class="td-panel" width="100px">Cedula: </td><td><select name= "eva_busidcedula" id= "eva_busidcedula" value= "-1">
                                <option value=-1>Seleccione</option>';
                                                            
                    $subconsulta = $this->cargarCedulas();
                    $RegCedulas = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                    
                    while($RegCedulas)
                        {
                            $HTML .= '<option value='.$RegCedulas['idCedula'].'>'.$RegCedulas['Descripcion'].'</option>';
                            $RegCedulas = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                    
                    $HTML .= '</select></td><td><img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/encuesta.png" width= "32px" height= "32px" alt= "Evaluar" id="'.$this->Sufijo.'Evaluar" title= "Evaluar"/></td></tr>';
                    return $HTML;
                    }
                                        
            public function drawUI()
                {
                    $HTML = '
                                <table>'.
                                    $this->drawCBCedulas().                                    
                                '</table>';
                    
                    return $HTML;
                    }                    
            }
                

    $objUsrCtrl = new usrctrl();
            
    if($objUsrCtrl->getCredenciales())
        {
            /*
             * Se valida que el usuario tenga sus credenciales cargadas
             * previo login en el sistema.
             */
            $idUsuario = $objUsrCtrl->getidUsuario($_SESSION['usuario'], $_SESSION['clave']);
            $Modulo = 'Evaluaciones';
            
            if($objUsrCtrl->validarCredenciales($idUsuario, $Modulo)!='')
                {
                    /*
                     * Se valida que las credenciales autoricen la ejecucion del
                     * modulo solicitado.
                     */
                    $objBusEvaluaciones = new busEvaluaciones();
                    $objEvaluaciones = new evaluaciones();
                    
                    echo '  <html>
                                <center>
                                    <div id="infoControl" style="display:none">
                                        Pagina: <input type="text" id="pagina" value="1">
                                        idEvaluacion: <input type="text" id="idEvaluacion" value="">
                                        idCedula: <input type="text" id="idCedula" value="0"></br>
                                        idUsuario: <input type="text" id="idUsuario" value="'.$objEvaluaciones->getIDUsuario().'"></br>
                                        Folio: <div id="divFolio"><input type="text" id="Folio" value="'.$objBusEvaluaciones->calcularFolio().'"></div></br>
                                        Fecha: <div id="divFecha"> <input type="text" id="Fecha" value="'.$objBusEvaluaciones->getFecha().'"></div></br>
                                        Status: <input type="text" id="Status" value="0"></br>
                                    </div>
                                    <div id="infoRegistro" class="operativo">
                                        <div id="cabecera" class="cabecera-operativo">
                                            <img align="middle" src="'.$objBusEvaluaciones->getImg().'" width="32" height="32"/> '.$objBusEvaluaciones->getTitulo().' </div>
                                        <div id="cuerpo" class="cuerpo-operativo">'.$objBusEvaluaciones->drawUI().'<br>';

                    echo '                  <div id= "busRes">';
                                                include_once("catEvaluaciones.php");
                    echo '                  </div>
                                        </div>                                                    
                                        <div id="pie" class="pie-operativo">'.'</div>
                                    </div>
                                </center>
                            </html>';
                    }
            else 
                {
                    /*
                     * En caso que no cuente con credenciales validas, el sistema impedira
                     * la brecha de seguridad.
                     */
                    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/noAutorizado.php");
                    }
            }
    else
        {
            /*
             * En caso que no cuente con credenciales validas, el sistema impedira
             * la brecha de seguridad.
             */
            include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/noAutorizado.php");
            }                        
?>