<?php
    require_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/mailsupport/class.phpmailer.php");
    require_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/mailsupport/class.phpmailer.php");
    require_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/mailsupport/class.smtp.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.
    
    class mail
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el manejo de
             * correos electronicos en el sistema.
             */
            private $Correo = "";
            private $Pregunta = "";
            private $Respuesta = "";
            private $Usuario = "";
            private $Clave = "";
            private $srvCorreo = "soporte.peyrano@gmail.com";
            private $srvClave = "abadiaroja";
                        
            public function getParamRecordarClave($Correo, $Pregunta, $Respuesta)
                {
                    /*
                     * Esta funcion obtiene los parametros correspondientes
                     * a la funcionalidad de recordatorio de clave por correo
                     * electronico.
                     */
                    $this->Correo = $Correo;
                    $this->Pregunta = $Pregunta;
                    $this->Respuesta = $Respuesta;
                    }
                    
            public function getUsuario()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de usuario.
                     */
                    return $this->Usuario;
                    }

            public function getClave()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de clave.
                     */
                    return $this->Usuario;
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
                    $consulta= 'SELECT *FROM catUsuarios WHERE Correo=\''.$this->Correo.'\''.' AND Pregunta =\''.$this->Pregunta.'\' AND Respuesta=\''.$this->Respuesta.'\''; //Se establece el modelo de consulta de datos.
                    $dsUsuarios = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    
                    $RegUsuarios = @mysqli_fetch_array($dsUsuarios,MYSQLI_ASSOC);
                    
                    if($RegUsuarios)
                        {
                            //Solo si existe un registro con el correo solicitado.
                            $this->Usuario = $RegUsuarios['Usuario'];
                            $this->Clave = $RegUsuarios['Clave'];
                            }                    
                    } 

            public function msgEnvio($Correo)
                {
                    /*
                     * Esta funcion genera el codigo HTML que corresponde al mensaje a visualizar
                     * en pantalla cuando se genero el envio de correo electronico al usuario.
                     */                        
                    global $SitioWeb;
                    
                    $DIVHeader = 'Recordatorio enviado con exito';
                    $DIVBody = 'Los datos de acceso a su cuenta han sido enviados a '. $Correo;
                    $DIVBody.= 'En breve recibira en su bandeja el mensaje, por favor revise su bandeja ';
                    $DIVBody.= 'de correo no deseado en caso de no observalo en la principal.';
                    $DIVBody.= '<br> De click en el enlace para regresar a la ventana de login.';
                                             
                    $HTML = '   <div id="recordarCorreo" class="notificacion">
                                    <div id="cabecera" class="cabecera-notificacion">'
                                        .'<img align="middle" src="./img/notificaciones/error.png" width="32" height="32"/> '.$DIVHeader.
                                    '</div>'
                                    .$DIVBody.
                            '       <a href="'.$SitioWeb.'" target="_self"><b>Regresar</b></a>
                                </div>';
                    
                    return $HTML;                    
                    }

            public function msgErrorEnvio()
                {
                    /*
                     * Esta funcion genera el codigo HTML que corresponde al mensaje a visualizar
                     * en pantalla cuando se genero un error en el envio de correo electronico al usuario.
                     */                        
                    global $SitioWeb;

                    $DIVHeader = 'Error con envio de correo';
                    $DIVBody = 'Ocurrio un error con el procesamiento de envio de correo, ';
                    $DIVBody.= 'por favor intente mas tarde';
                    $DIVBody.= '<br> De click en el enlace para regresar a la ventana de login.';
                                                
                    $HTML = '   <div id="recordarCorreo" class="notificacion">
                                    <div id="cabecera" class="cabecera-notificacion">'
                                        .'<img align="middle" src="./img/notificaciones/error.png" width="32" height="32"/> '.$DIVHeader.
                                    '</div>'
                                .$DIVBody.
                                '       <a href="'.$SitioWeb.'" target="_self"><b>Regresar</b></a>
                                </div>';
                        
                    return $HTML;
                    }

            public function msgErrorValidacion($Correo)
                {
                    /*
                     * Esta funcion genera el codigo HTML que corresponde al mensaje a visualizar
                     * en pantalla cuando se genero un error en el envio de correo electronico al usuario.
                     */
                    global $SitioWeb;

                    $DIVHeader = 'Error de validacion de usuario';
                    $DIVBody = 'No existen datos de usuario asociados a la cuenta de correo <b>'.$Correo.'</b> ';
                    $DIVBody.= 'o posiblemente la respuesta a la pregunta de control no es correcta.';
                    $DIVBody.= '<br> De click en el enlace para regresar a la ventana de login.';
                                        
                    $HTML = '   <div id="recordarCorreo" class="notificacion">
                                    <div id="cabecera" class="cabecera-notificacion">'
                                        .'<img align="middle" src="./img/notificaciones/error.png" width="32" height="32"/> '.$DIVHeader.
                                    '</div>'
                                .$DIVBody.
                                '       <a href="'.$SitioWeb.'" target="_self"><b>Regresar</b></a>
                                </div>';
                        
                    return $HTML;
                    }
                                        
            public function enviarCorreo()
                    {
                        /*
                         * Esta funcion genera los parametros para el envio del correo de recordatorio
                         * de clave para el usuario solicitante.
                         */
                        
                        $objCodificador= new codificador();
                    
                        $mail = new PHPMailer(); //Se declara la instancia de objeto de manejo de correo.
                        $mail->IsSMTP();
                        $mail->CharSet = 'UTF-8';
                        $mail->SMTPAuth = true;
                        //$mail->SMTPSecure = "ssl";
                        $mail->Host = "smtp.gmail.com"; //servidor smtp
                        $mail->Port = 587; //puerto smtp de gmail
                        $mail->Username = $this->srvCorreo;
                        $mail->Password = $this->srvClave;
                        $mail->FromName = 'Soporte tecnico a usuario';
                        $mail->From = $this->srvCorreo;//email de remitente desde donde se envia el correo.
                        $mail->AddAddress($this->Correo, $this->Usuario);//destinatario que va a recibir el correo
                        //$mail->AddCC('x', 'Sxd');//envia una copia del correo a la direccion especificada
                        $mail->Subject = 'Recuperar clave de usuario';
                        $mail->AltBody = 'Estimado '.$this->Usuario;//cuerpo con texto plano
                    
                        $mail->MsgHTML('Estimado '.$this->Usuario.'<br/>'.'Su clave en el sistema es: '.$objCodificador->decrypt($this->Clave, 'ouroboros').'<br/> Tenga un buen dia <br/> Soporte tecnico a usuario');//cuerpo con html
                    
                        if(!$mail->Send())
                            {
                                //finalmente enviamos el email.
                                echo $this->msgErrorEnvio($mail->ErrorInfo); //En caso de ocurrir un error.
                                }
                        else
                            {
                                echo $this->msgEnvio($this->Correo); //En caso de efectuarse el envio.
                                }
                        }                                
            }
?>