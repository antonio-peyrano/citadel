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
    require_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/mailsupport/class.phpmailer.php");
    require_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/mailsupport/class.phpmailer.php");
    require_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/mailsupport/class.smtp.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/captcha.class.php");//Se carga la referencia a la clase de manejo de captcha.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    
    class dalSolicitudes
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion de los datos
             * correspondientes a la entidad solicitudes.
             */
            private $srvCorreo = "soporte.peyrano@gmail.com";
            private $srvClave = "abadiaroja";
            
            private $RACNombre = "";  //Nombre del Responsable de Atencion a Cliente.
            private $RACMail = ""; //Correo del Responsable de Atencion a Cliente.
            private $RACEntidad = ""; //Entidad a la que esta adscrito el RAC.
            private $Cliente = ""; //Nombre del Cliente.
            private $Correo = ""; //Correo del Cliente.
            
            private $Accion = '';
            private $cntrlVar = 0;
            private $idSolicitud = NULL;
            private $idUsuario = NULL;
            private $idEntidad = NULL;
            private $Folio = '';
            private $Asunto = '';
            private $Detalle = '';
            private $fRegistro = '';
            private $Status = 0;
            private $cntView = 0;
            private $captchaFail = '';
            private $captcha = '';
            
            public function __construct()
                {
                    /*
                     * Este constructor obtiene y valida los datos ingresados por medio de la
                     * URL por parte del usuario.
                     */
                    $this->cntrlVar = 0;
                    
                    if(isset($_GET['captcha'])){$this->captcha = $_GET['captcha'];}                
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['accion'])){$this->Accion = $_GET['accion'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['id'])){$this->idSolicitud = $_GET['id'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['idusuario'])){$this->idUsuario = $_GET['idusuario'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['identidad'])){$this->idEntidad = $_GET['identidad'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['folio'])){$this->Folio = $_GET['folio'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['asunto'])){$this->Asunto = $_GET['asunto'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['detalle'])){$this->Detalle = $_GET['detalle'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['fregistro']))
                        {
                            date_default_timezone_set("America/Mexico_City");
                            $this->fRegistro = date("Y/m/d H:i:s",strtotime($_GET['fregistro']));
                            }
                    else{$this->cntrlVar+=1;}
                    if(isset($_GET['status'])){$this->Status = $_GET['status'];}else{$this->cntrlVar+=1;}            
                    }

            public function buscarRAC()
                {
                    /*
                     * Esta funcion ejecuta la busqueda del usuario en el sistema
                     * apartir de los datos proporcionados en la interfaz de solicitud.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idEmpleado, CONCAT(Nombre,\' \',Paterno,\' \',Materno) AS Nombre, Correo FROM catEmpleados WHERE idEntidad=\''.$this->idEntidad.'\''.' AND esRAC = \'1\''; //Se establece el modelo de consulta de datos.
                    $dsEmpleados = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                        
                    $RegEmpleados = @mysqli_fetch_array($dsEmpleados,MYSQLI_ASSOC);
                        
                    if($RegEmpleados)
                        {
                            //Solo si existe un registro con el correo solicitado.
                            $this->RACNombre = $RegEmpleados['Nombre'];
                            $this->RACMail = $RegEmpleados['Correo'];
                            }
                    }
            
            public function getSTRStatus($Status)
                {
                    //Esta funcion retorna la cadena correspondiente al codigo de Status.
                    if($Status == '0'){return "Registrada";}
                    if($Status == '1'){return "Canalizada";}
                    if($Status == '2'){return "En Proceso";}
                    if($Status == '3'){return "Procesada";}
                    if($Status == '4'){return "Cancelada";}
                    }
            
            public function getSTREntidad($idEntidad)
                {
                    //Esta funcion retorna el nombre de la entidad para el ID proporcionado.
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idEntidad, Entidad FROM catEntidades WHERE idEntidad=\''.$idEntidad.'\''; //Se establece el modelo de consulta de datos.
                    $dsEntidad = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    
                    $RegEntidad = @mysqli_fetch_array($dsEntidad,MYSQLI_ASSOC);
                    
                    if($RegEntidad)
                        {
                            //Solo si existe un registro con el correo solicitado.
                            $this->RACEntidad = $RegEntidad['Entidad'];
                            }
                    }
                    
            public function enviarCorreo($Status)
                {
                    /*
                     * Esta funcion genera los parametros para el envio del correo de recordatorio
                     * de clave para el usuario solicitante.
                     */                        
                    $this->buscarRAC();
                    $this->buscarUsuario();
                    
                    $mail = new PHPMailer(); //Se declara la instancia de objeto de manejo de correo.
                    $mail->IsSMTP();
                    $mail->CharSet = 'UTF-8';
                    $mail->SMTPAuth = true;
                    //$mail->SMTPSecure = "ssl";
                    $mail->Host = "smtp.gmail.com"; //servidor smtp
                    $mail->Port = 587; //puerto smtp de gmail
                    $mail->Username = $this->srvCorreo;
                    $mail->Password = $this->srvClave;
                    $mail->FromName = 'Soporte Tecnico a Usuario';
                    $mail->From = $this->srvCorreo;//email de remitente desde donde se envia el correo.
                    $mail->Subject = 'Estado de la Solicitud con FOLIO '.$this->Folio;
                    $mail->AltBody = 'Estimado '.$this->Cliente;//cuerpo con texto plano
                    
                    $this->getSTREntidad($this->idEntidad);
                    
                    if($this->getSTRStatus($Status) == 'Registrada')
                        {
                            $mail->AddAddress($this->Correo, $this->Cliente); //destinatario que va a recibir el correo
                            $HTMLMsg =  'Se le informa que la solicitud con No. de Folio: '. $this->Folio.'<br/>'.
                                        'se encuentra en estado: '. $this->getSTRStatus($this->Status).'. <br/> Siendo la entidad: '.$this->RACEntidad.
                                        ' la que gestionara y validara su solicitud.<br/>'.
                                        '<br/>Atentamente<br/> El soporte tecnico de Citadel';
                            }
                    else
                        {
                            if(($this->getSTRStatus($Status) == 'Canalizada')||($this->getSTRStatus($Status) == 'En Proceso'))
                                {
                                    $mail->AddAddress($this->Correo, $this->Cliente);//envia una copia del correo a la direccion especificada
                                    $mail->AddCC($this->RACMail, $this->RACNombre); //destinatario que va a recibir el correo
                                    $HTMLMsg =  'Se le informa que la solicitud con No. de Folio: '. $this->Folio.'<br/>'.
                                                'se encuentra en estado: '. $this->getSTRStatus($this->Status).'. <br/> Siendo gestionada en este momento por la entidad: '.$this->RACEntidad.
                                                ', para en breve darle un resultado al respecto.<br/>'.
                                                '<br/>Atentamente<br/> El soporte tecnico de Citadel';
                                    }
                            else
                                {
                                    if($this->getSTRStatus($Status) == 'Procesada')
                                        {
                                            $mail->AddAddress($this->Correo, $this->Cliente);//envia una copia del correo a la direccion especificada
                                            $mail->AddCC($this->RACMail, $this->RACNombre); //destinatario que va a recibir el correo
                                            $HTMLMsg =  'Se le informa que la solicitud con No. de Folio: '. $this->Folio.'<br/>'.
                                                        'ha sido '. $this->getSTRStatus($this->Status).' por la entidad: '.$this->RACEntidad.
                                                        '<br/>Atentamente<br/> El soporte tecnico de Citadel';
                                            }
                                    else
                                        {
                                            $mail->AddAddress($this->Correo, $this->Cliente);//envia una copia del correo a la direccion especificada
                                            $mail->AddCC($this->RACMail, $this->RACNombre); //destinatario que va a recibir el correo
                                            $HTMLMsg =  'Se le informa que la solicitud con No. de Folio: '. $this->Folio.'<br/>'.
                                                        'ha sido '. $this->getSTRStatus($this->Status).' por la entidad: '.$this->RACEntidad.
                                                        '; debido a detalles observados en su naturaleza.'.
                                                        '<br/>Atentamente<br/> El soporte tecnico de Citadel';
                                            }                                            
                                    }
                            }
                                                                    
                    $mail->MsgHTML($HTMLMsg);//cuerpo con html                        
                    $mail->Send();
                    }
                    
            public function buscarUsuario()
                {
                    /*
                     * Esta funcion ejecuta la busqueda del usuario en el sistema
                     * apartir de los datos proporcionados por el formulario de
                     * recordatorio.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta= 'SELECT idUsuario, Usuario, Correo FROM catUsuarios WHERE idUsuario=\''.$this->idUsuario.'\''; //Se establece el modelo de consulta de datos.
                    $dsUsuarios = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                        
                    $RegUsuarios = @mysqli_fetch_array($dsUsuarios,MYSQLI_ASSOC);
                        
                    if($RegUsuarios)
                        {
                            //Solo si existe un registro con el correo solicitado.
                            $this->Cliente = $RegUsuarios['Usuario'];
                            $this->Correo = $RegUsuarios['Correo'];
                            }
                    } 
                    
            public function almacenarParametros()
                {
                    /*
                     * Esta funcion almacena los parametros proporcionados via URL
                     * en la entidad de la base de datos.
                     */
                    if($this->cntrlVar == 0)
                        {
                            //SIN ERRORES EN EL PASO DE VALORES POR URL.
                            global $username, $password, $servername, $dbname;
                    
                            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                            $this->captchaFail='';
                            
                            //VALIDACION DEL BLOQUE CAPTCHA.
                            session_name('citadel');
                            session_start();
                            $objCaptcha = new captcha();
                            
                            if ($this->captcha != $objCaptcha->getCaptchaCode())
                                {
                                    /*
                                     * Cuando el valor del captcha enviado no corresponde
                                     * a alguno dentro del esquema de validaciones.
                                     */
                                    $this->captchaFail = 'El captcha no corresponde a la imagen visualizada';
                                    }

                            if($this->captchaFail == '')
                                {
                                    /*
                                     * captchaFail es un atributo de doble control. Si su valor apunta a una cadena vacia,
                                     * indica que pudo haber venido de un formulario donde no se requirio el control de spam
                                     * o en su defecto, la validacion contra spam fue satisfactoria.
                                     */                                    
                                    if($this->idSolicitud != NULL)
                                        {
                                            //EDICION DE REGISTRO                                    
                                            $consulta = 'UPDATE opSolicitudes SET Folio=\''.$this->Folio.'\', Asunto=\''.$this->Asunto.'\', Detalle=\''.$this->Detalle.'\', idUsuario=\''.$this->idUsuario.'\', idEntidad=\''.$this->idEntidad.'\', Status=\''.$this->Status.'\' WHERE idSolicitud='.$this->idSolicitud; //Se establece el modelo de consulta de datos.
                                            $dsSolicitud = $objConexion->conectar($consulta); //Se ejecuta la consulta.                                        
                                            }
                                    else
                                        {
                                            //CREACION DE REGISTRO.                                    
                                            $consulta = 'INSERT INTO opSolicitudes (Folio, Asunto, Detalle, idUsuario, idEntidad, fRegistro) VALUES ('.'\''.$this->Folio.'\',\''.$this->Asunto.'\', \''.$this->Detalle.'\', \''.$this->idUsuario.'\', \''.$this->idEntidad.'\', \''.$this->fRegistro.'\')'; //Se establece el modelo de consulta de datos.
                                            $dsSolicitud = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                    
                                            }      
                                            
                                    $this->enviarCorreo($this->Status);        
                                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/solicitudes/busSolicitudes.php");
                                    }
                            else
                                {
                                    /*
                                     * En caso de ocurrir el error de validacion con el captcha,
                                     * se procesa la solicitud como una invocacion desde las funciones
                                     * de invitado.
                                     */
                                    $_GET['captcha_fail'] = $this->captchaFail;
                                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/solicitudes/opSolicitudes.php");                                    
                                    }
                            }
                    else
                        {
                            //FALLO DE LA VALIDACION DE PARAMETROS POR URL.                            
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/ERROR405.php");
                            }
                    }

            public function eliminarParametros()
                {
                    /*
                     * Esta funcion ejecuta un borrado logico sobre el registro indicado
                     * por el usuario en su interaccion.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta= 'UPDATE opSolicitudes SET Status=1 where idSolicitud='.$this->idSolicitud; //Se establece el modelo de consulta de datos.
                    $dsSolicitud = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/solicitudes/busSolicitudes.php");
                    }
                    
            public function getAccion()
                {
                    /*
                     * Esta funcion retorna el valor obtenido por medio de la URL
                     * en respuesta a la accion del usuario.
                     */
                        return $this->Accion;
                    }                                       
            }
            
    $objDALSolicitudes = new dalSolicitudes();
            
    if($objDALSolicitudes->getAccion() == "CoER")
        {
            //CoER: CREACION o EDICION DE REGISTRO.
            $objDALSolicitudes->almacenarParametros();
            }
    else
        {
            if($objDALSolicitudes->getAccion() == "EdRS")
                {
                    //EdRS:ELIMINACION de REGISTRO EN SISTEMA.
                    $objDALSolicitudes->eliminarParametros();
                    }
            }            
?>