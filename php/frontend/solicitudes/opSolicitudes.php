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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/solicitudes/solicitudes.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/main/usrctrl.class.php"); //Se carga la referencia de clase para control de accesos.
        
    class opSolicitudes
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de solicitudes.
             */
            private $cntView = 0;
            private $idSolicitud = 0;
            //private $idUsuario = 0;
            private $fRegistro = '';
            private $ClaveCod = ''; 
            private $imgTitleURL = './img/menu/solicitudes.png';
            private $Title = 'Solicitud';
            
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    $objSolicitudes = new solicitudes();                            
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idSolicitud = $_GET['id'];}
                    //$objSolicitudes->setIDUsuario(); //Obteniendo el ID del usuario con sesion activa.
                    //$objSolicitudes->setFechaRegistro(); //Se genera la fecha de registro.
                    }

            public function saltosLineaRev($str)
                {
                    /*
                     * Esta funcion toma los tag <br> dentro de la cadena recuperada y
                     * los convierte a saltos de linea.
                     */
                    return str_replace(array("<br>"), "\n", $str);
                    }

            function calcularFolio($Registro)
                {
                    /*
                     * Esta funcion establece el calculo de la clave de la solicitud a razon
                     * de los elementos existentes.
                     */
                    global $username, $password, $servername, $dbname, $idObjEst, $idObjOpe, $Clave, $periodo;
                    
                    $objAux = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    
                    $consulta = "SELECT *FROM opSolicitudes";
                    $dataset = $objAux -> conectar($consulta);
                    $RowCount = mysqli_num_rows($dataset);

                    $now = time(); //Se obtiene la referencia del tiempo actual del servidor.
                    date_default_timezone_set("America/Mexico_City"); //Se establece el perfil del uso horario.
                    $fecha = date("Y/m/d H:i:s",$now); //Se obtiene la referencia compuesta de fecha.
                    //$hora = date("H:i:s",$now); //Se obtiene la referencia compuesta de hora.
                    
                    $parseFecha = explode("/",$fecha);
                    $parseFecha = implode("",$parseFecha); 
                    
                    $parseFecha = explode(":",$parseFecha);
                    $parseFecha = implode("",$parseFecha);

                    $parseFecha = explode(" ",$parseFecha);
                    $parseFecha = implode("",$parseFecha);                    

                    if($Clave=="")
                        {
                            //Si se trata de un nuevo registro, se genera una clave artificial nueva.
                            $Clave = 'SAU'.'-'.$parseFecha."-".($RowCount + 1);
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
                                                         
            public function getfRegistro($Registro)
                {
                    /*
                     * Esta funcion retorna el valor obtenido de fecha de registro.
                     */
                    $objSolicitudes = new solicitudes();
                    
                    if(!empty($Registro['fRegistro']))
                        {
                            return '<td><input type= "text" class= "inputform" id= "fRegistro" readonly required= "required" value= "'.$Registro['fRegistro'].'"></td>';
                            }
                    else
                        {
                            return '<td><input type= "text" class= "inputform" id= "fRegistro" readonly required= "required" value= "'.$objSolicitudes->getfRegistro().'"></td>';
                            }
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
                     * Esta funcion retorna el valor obtenido del ID de Solicitud.
                     */
                    return $this->idSolicitud;
                    }
                                                                                                  
            public function drawCBTipoFactor($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * tipo de factor.
                     */
                     $HTML = '<tr><td class="td-panel" width="100px">Status: </td><td><select name= "Status" id= "Status" value= "-1"'.$habilitador.'>
                                    <option value=-1>Seleccione</option>';
                         
                     if($Registro['Status'] == '0')
                        {
                            $HTML .= '<option value=0 selected>Registrada</option>';
                            $HTML .= '<option value=1>En proceso</option>';
                            $HTML .= '<option value=2>Procesada</option>';
                            }
                    else
                        {
                            if($Registro['Status'] == '1')
                                {
                                    $HTML .= '<option value=0>Registrada</option>';
                                    $HTML .= '<option value=1 selected>En proceso</option>';
                                    $HTML .= '<option value=2>Procesada</option>';                                    
                                    }
                            else
                                {
                                    if($Registro['Status'] == '2')
                                        {
                                            $HTML .= '<option value=0>Registrada</option>';
                                            $HTML .= '<option value=1>En proceso</option>';
                                            $HTML .= '<option value=2 selected>Procesada</option>';                                            
                                            }
                                    }
                            }
                    
                        $HTML .= '</select></td></tr>';
                        return $HTML;
                    }

            public function drawTRStatus($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML de la interfaz a partir del perfil con el
                     * que cuenta el usuario.
                     */
                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('citadel');
                            session_start();
                            }
                    if($_SESSION['nivel'] == 'Administrador')
                        {
                            return $this->drawCBTipoFactor($Registro, $habilitador);
                            }
                    else
                        {
                            $HTML = '<tr style="display:none"><td class="td-panel" width="100px">Status: </td><td><input id="Status" type="text" value="'.$Registro['Status'].'"></td></tr>';
                            return $HTML;
                            }        
                    }
                                        
            public function captchaDraw()
                {
                    /*
                     * Esta funcion genera el codigo HTML que corresponde al
                     * captcha que se utiliza para evitar el spam.
                     */
                    $HTML = '   <div id="cnt-captcha" class="cnt-captcha">
                                    <br>
                                    <div id= "captcha-controll" class= "captcha-controll">
                                        <img id="captcha" align="middle" src="./php/frontend/utilidades/captcha/comp/captcha.php?'.rand().'" alt="CAPTCHA Image" width="150px" height="60px"/>
                                    </div>                                
                                    <div id="captcha-refresh" class= "captcha-controll">
                                        <img id="rfrCaptcha" align="middle" src="./img/grids/refresh.png" width="35px" height="35px" title="Actualizar imagen"/>
                                    </div>
                                    <br>
                                    <br>
                                    <div id="captcha-text" style="display:block" align="center">
                                        <input type="text" id="captcha_code" name="captcha_code" size="10" maxlength="6" />
                                    </div>
                                </div>                                
                                ';
                    
                    return $HTML;
                    }                    
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */                    
                    
                    $objSolicitudes = new solicitudes();                    
                    
                    $RegSolicitud = $objSolicitudes->getRegistro($this->getID());
                    $idUsuario = '';
                                        
                    if(!empty($RegSolicitud['idSolicitud']))
                        {
                            //CASO: VISUALIZACION DE REGISTRO PARA SU EDICION O BORRADO.
                            if($this->getView() == 1)
                                {
                                    //VISUALIZAR.
                                    $habCampos = 'disabled= "disabled"';
                                    $idUsuario = '<tr><td>idUsuario: </td><td><input id="idUsuario" type="text" value="'.$RegSolicitud['idUsuario'].'"></td></tr>';                                    
                                    }
                            else
                                {
                                    //CREACION O EDICION.
                                    $habCampos = '';
                                    $idUsuario = '<tr><td>idUsuario: </td><td><input id="idUsuario" type="text" value="'.$RegSolicitud['idUsuario'].'"></td></tr>';
                                    }                                                                
                            }
                    else
                        {
                            //CASO: CREACION DE NUEVO REGISTRO.
                            $habCampos = '';
                            $idUsuario = '<tr><td>idUsuario: </td><td><input id="idUsuario" type="text" value="'.$objSolicitudes->getIDUsuario().'"></td></tr>';
                            }                                               

                    $HTMLBody = '   <div id="statsSolicitud" style="display:none">
                                        <table>
                                            <tr><td>idSolicitud: </td><td><input id="idSolicitud" type="text" value="'.$RegSolicitud['idSolicitud'].'"></td></tr>'.
                                            $idUsuario                                                
                                            
                                        .'</table>
                                    </div>
                                    <div id="infoRegistro" class="operativo">
                                        <div id="cabecera" class="cabecera-operativo">'
                                            .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                        <div id="cuerpo" class="cuerpo-operativo">
                                            <table>
                                                <tr><td class="td-panel" width="100px">Folio:</td>'.$this->calcularFolio($RegSolicitud).'<td class="td-panel" width="100px">Fecha de Registro:</td>'.$this->getfRegistro($RegSolicitud).'</tr>
                                                <tr><td class="td-panel" width="100px">Asunto:</td><td colspan= "3"><input type= "text" class= "inputform" id= "Asunto" required= "required" '.$habCampos.' value= "'.$RegSolicitud['Asunto'].'"></td></tr>
                                                <tr><th colspan= "4" class="td-panel" width="100px">Detalle</th></tr>
                                                <tr><td colspan= "4" class= "dgRowsnormTR"><center><textarea name="Detalle" id="Detalle" cols="80" rows="8"'.$habCampos.'>'.$this->saltosLineaRev($RegSolicitud['Detalle']).'</textarea></center></td></tr>'
                                                .$this->drawTRStatus($RegSolicitud, $habCampos).
                                                '<tr><td class="td-panel" width="100px" colspan= "4"><center>Codigo de Verificacion'.$this->captchaDraw().'</center></td></tr>                                                                                        
                                            </table>
                                        </div>                                                    
                                        <div id="pie" class="pie-operativo">'.$objSolicitudes->controlBotones("32", "32", $this->getView(), $RegSolicitud['idUsuario']).'</div>                                                                                                                                                                                   
                                    </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpSolicitud = new opSolicitudes();
    

    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                            $objOpSolicitud->drawUI().
                        '</center>
                    </body>
                </html>';
                    
    echo $HTML;        
?>