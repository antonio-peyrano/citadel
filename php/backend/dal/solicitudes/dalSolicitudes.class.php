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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/captcha.class.php");//Se carga la referencia a la clase de manejo de captcha.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    
    class dalSolicitudes
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion de los datos
             * correspondientes a la entidad solicitudes.
             */
            private $Accion = '';
            private $cntrlVar = 0;
            private $idSolicitud = NULL;
            private $idUsuario = NULL;
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
                                    $this->captchaFail = 'El texto no corresponde a la imagen visualizada';
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
                                            $consulta = 'UPDATE opSolicitudes SET Folio=\''.$this->Folio.'\', Asunto=\''.$this->Asunto.'\', Detalle=\''.$this->Detalle.'\', idUsuario=\''.$this->idUsuario.'\', Status=\''.$this->Status.'\' WHERE idSolicitud='.$this->idSolicitud; //Se establece el modelo de consulta de datos.
                                            $dsSolicitud = $objConexion->conectar($consulta); //Se ejecuta la consulta.                                        
                                            }
                                    else
                                        {
                                            //CREACION DE REGISTRO.                                    
                                            $consulta = 'INSERT INTO opSolicitudes (Folio, Asunto, Detalle, idUsuario, fRegistro) VALUES ('.'\''.$this->Folio.'\',\''.$this->Asunto.'\', \''.$this->Detalle.'\', \''.$this->idUsuario.'\', \''.$this->fRegistro.'\')'; //Se establece el modelo de consulta de datos.
                                            $dsSolicitud = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                    
                                            }                                                
                                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/solicitudes/busSolicitudes.php");
                                    }
                            else
                                {
                                    /*
                                     * En caso de ocurrir el error de validacion con el captcha,
                                     * se procesa la solicitud como una invocacion desde las funciones
                                     * de invitado.
                                     */                                    
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