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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.

    class dalUsuarios
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion de los datos
             * correspondientes a la entidad usuarios.
             */
            private $Accion = '';
            private $cntrlVar = 0;
            private $idUsuario = NULL;
            private $Usuario = '';
            private $Clave = '';
            private $Correo = '';
            private $Pregunta = '';
            private $Respuesta = '';
            private $idNivel = 0;
            private $Mod = '';
            private $nonMod = '';
            private $Status = 0;
            
            private $Paterno = '';
            private $Materno = '';
            private $Nombre = '';
            private $curp = '';
            private $fNacimiento = '';
            private $telFijo = '';
            private $telCel = '';
            
            private $cntView = 0;
            private $captcha = '';
            private $captchaFail = '';
                                    
            public function __construct()
                {
                    /*
                     * Este constructor obtiene y valida los datos ingresados por medio de la
                     * URL por parte del usuario.
                     */
                    $this->cntrlVar = 0;
                                        
                    if(isset($_GET['captcha'])){$this->captcha = $_GET['captcha'];}
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idUsuario = $_GET['id'];}else{$this->cntrlVar+=1;}                            
                    if(isset($_GET['accion'])){$this->Accion = $_GET['accion'];}else{$this->cntrlVar+=1;}                            
                    if(isset($_GET['usuario'])){$this->Usuario = $_GET['usuario'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['clave'])){$this->Clave = $_GET['clave'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['correo'])){$this->Correo = $_GET['correo'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['pregunta'])){$this->Pregunta = $_GET['pregunta'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['respuesta'])){$this->Respuesta = $_GET['respuesta'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['idnivel'])){$this->idNivel = $_GET['idnivel'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['mod'])){$this->Mod = $_GET['mod'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['nonmod'])){$this->nonMod = $_GET['nonmod'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['status'])){$this->Status = $_GET['status'];}else{$this->cntrlVar+=1;}
                    
                    if($this->cntView == 9)
                        {
                            //CASO: SOLICITUD INVOCADA POR USUARIO INVITADO, SE ESTABLECEN PERMISOS DE LECTURA.
                            $this->idNivel = 3;
                            $this->nonMod = '-1B';
                            $this->Mod = '-1B';
                            $this->cntrlVar = $this->cntrlVar-3;
                            
                            if(isset($_GET['paterno'])){$this->Paterno = $_GET['paterno'];}else{$this->cntrlVar+=1;}
                            if(isset($_GET['materno'])){$this->Materno = $_GET['materno'];}else{$this->cntrlVar+=1;}
                            if(isset($_GET['nombre'])){$this->Nombre = $_GET['nombre'];}else{$this->cntrlVar+=1;}
                            if(isset($_GET['curp'])){$this->curp = $_GET['curp'];}else{$this->cntrlVar+=1;}
                            if(isset($_GET['fnacimiento']))
                                {
                                    date_default_timezone_set("America/Mexico_City");
                                    $this->fNacimiento = date("Y/m/d",strtotime($_GET['fnacimiento']));
                                    }
                            else{$this->cntrlVar+=1;}
                            if(isset($_GET['telfijo'])){$this->telFijo = $_GET['telfijo'];}else{$this->cntrlVar+=1;}
                            if(isset($_GET['telcel'])){$this->telCel = $_GET['telcel'];}else{$this->cntrlVar+=1;}
                            }
                    }

            function getExistencias($idUsuario, $idModulo)
                {
                    /*
                     * Esta funcion establece la busqueda para determinar si un registro ya existe en el sistema
                     * con las condiciones proporcionadas.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT *FROM opRelPerUsr WHERE idUsuario='.$idUsuario.' AND idModulo='.$idModulo; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                    
                    if(!$Registro)
                        {
                            /*
                             * En caso que el muestreo no arroje datos en la consulta.
                             */
                            return false;
                            }
                    
                    return true;
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
                            $objCodificador =new codificador();
                            
                            $Mod = explode('B',$this->Mod); //Aqui se convierte el vector en un arreglo con los id seleccionados.
                            $nonMod = explode('B',$this->nonMod); //Aqui se convierte el vector en un arreglo con los id no seleccionados.                            
                            $this->captchaFail='';
                            
                            if($this->cntView == 9)
                                {
                                    //CASO: SE INVOCO DESDE EL FORMULARIO DE CREACION DE USUARIO POR INVITADO.
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
                                    }
                                    
                            if($this->captchaFail == '')
                                {
                                    /*
                                     * captchaFail es un atributo de doble control. Si su valor apunta a una cadena vacia,
                                     * indica que pudo haber venido de un formulario donde no se requirio el control de spam
                                     * o en su defecto, la validacion contra spam fue satisfactoria.
                                     */
                                    
                                    if($this->idUsuario != NULL)
                                        {
                                            //EDICION DE REGISTRO
                                            $claveCod = $objCodificador->encrypt($this->Clave, "ouroboros");
                                            $consulta = 'UPDATE catUsuarios SET Usuario=\''.$this->Usuario.'\', Clave=\''.$claveCod.'\', Correo=\''.$this->Correo.'\', Pregunta=\''.$this->Pregunta.'\', Respuesta=\''.$this->Respuesta.'\', idNivel=\''.$this->idNivel.'\', Status=\''.$this->Status.'\' where idUsuario='.$this->idUsuario; //Se establece el modelo de consulta de datos.
                                            $dsUsuario = $objConexion->conectar($consulta); //Se ejecuta la consulta.
                                    
                                            //Se crean los elementos de la relacion.
                                            for($conteo=1; $conteo < count($Mod); $conteo++)
                                                {
                                                    if(!$this->getExistencias($this->idUsuario, $Mod[$conteo]))
                                                        {
                                                            /*
                                                             * En caso de no existir referencias previas, se crean en la entidad de las relaciones.
                                                             */
                                                            $consulta = 'INSERT INTO opRelPerUsr (idUsuario, idModulo) VALUES ('.'\''.$this->idUsuario.'\',\''.$Mod[$conteo].'\')'; //Se establece el modelo de consulta de datos.
                                                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                            }
                                                    else
                                                        {
                                                            /*
                                                             * En caso de existir referencias previas, considerando que la relaci�n fue eliminada previamente.
                                                             */
                                                            $consulta = 'UPDATE opRelPerUsr SET Status= 0 WHERE idUsuario='.$this->idUsuario.' AND idModulo='.$Mod[$conteo]; //Se establece el modelo de consulta de datos.
                                                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                            }
                                                    }
                                    
                                            //Se eliminan los elementos de la relacion si fueron desmarcados.
                                            for($conteo=1; $conteo < count($nonMod); $conteo++)
                                                {
                                                    if($this->getExistencias($this->idUsuario, $nonMod[$conteo]))
                                                        {
                                                            /*
                                                             * En caso de existir referencias previas, se eliminan en la entidad de las relaciones.
                                                             */                                                            
                                                            $consulta = 'UPDATE opRelPerUsr SET Status= 1 WHERE idUsuario='.$this->idUsuario.' AND idModulo='.$nonMod[$conteo]; //Se establece el modelo de consulta de datos.
                                                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                            }
                                                    }
                                            }
                                    else
                                        {
                                            //CREACION DE REGISTRO.
                                            $claveCod = $objCodificador->encrypt($this->Clave, "ouroboros");
                                            $consulta = 'INSERT INTO catUsuarios (Usuario, Clave, Correo, Pregunta, Respuesta, idNivel) VALUES ('.'\''.$this->Usuario.'\',\''.$claveCod.'\', \''.$this->Correo.'\', \''.$this->Pregunta.'\', \''.$this->Respuesta.'\', \''.$this->idNivel.'\')'; //Se establece el modelo de consulta de datos.
                                            $dsUsuario = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                            
                                            //BUSQUEDA DE USUARIO RECIEN CREADO PARA OBTENER SU ID.
                                            $consulta = 'SELECT *FROM catUsuarios WHERE Usuario=\''.$this->Usuario.'\' AND Clave=\''.$claveCod.'\''; //Se establece el modelo de consulta de datos.
                                            $dsUsuario = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                            $RegUsuario = @mysqli_fetch_array($dsUsuario,MYSQLI_ASSOC);
                                            
                                            if($this->cntView == 9)
                                                {
                                                    //Si el usuario se crea por medio de invitado, se establece los permisos minimos.
                                                    $consulta = 'INSERT INTO opRelPerUsr (idUsuario, idModulo) VALUES ('.'\''.$RegUsuario['idUsuario'].'\',\'4\')'; //Se establece el modelo de consulta de datos.
                                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                    $consulta = 'INSERT INTO opRelPerUsr (idUsuario, idModulo) VALUES ('.'\''.$RegUsuario['idUsuario'].'\',\'10\')'; //Se establece el modelo de consulta de datos.
                                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                    }
                                            else
                                                {
                                                    //Si el usuario se crea por medio del modulo principal del sistema, se cargan los permisos gestionados.
                                                    //Se crean los elementos de la relacion.
                                                    for($conteo=1; $conteo < count($Mod); $conteo++)
                                                        {
                                                            $consulta = 'INSERT INTO opRelPerUsr (idUsuario, idModulo) VALUES ('.'\''.$RegUsuario['idUsuario'].'\',\''.$Mod[$conteo].'\')'; //Se establece el modelo de consulta de datos.
                                                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                            }
                                                    }
                                                    
                                            if($this->cntView == 9)
                                                {
                                                    //Si se ha creado el usuario desde modo invitado, se crea el registro
                                                    //de ciudadano correspondiente.
                                                    $consulta = 'INSERT INTO catClientes (Paterno, Materno, Nombre, curp, fNacimiento, telFijo, telCel) VALUES ('.'\''.$this->Paterno.'\',\''.$this->Materno.'\', \''.$this->Nombre.'\', \''.$this->curp.'\', \''.$this->fNacimiento.'\', \''.$this->telFijo.'\', \''.$this->telCel.'\')'; //Se establece el modelo de consulta de datos.
                                                    $dsCliente = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                    
                                                    //BUSQUEDA DE CLIENTE RECIEN CREADO PARA OBTENER SU ID.
                                                    $consulta = 'SELECT *FROM catClientes WHERE Paterno=\''.$this->Paterno.'\' AND Materno=\''.$this->Materno.'\' AND Nombre=\''.$this->Nombre.'\' AND curp=\''.$this->curp.'\''; //Se establece el modelo de consulta de datos.
                                                    $dsCliente = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                    $RegCliente = @mysqli_fetch_array($dsCliente,MYSQLI_ASSOC);

                                                    //CREACION DE LA RELACION USUARIO / CLIENTE
                                                    $consulta = 'INSERT INTO opRelUsrCli (idUsuario, idCliente) VALUES ('.'\''.$RegUsuario['idUsuario'].'\',\''.$RegCliente['idCliente'].'\')'; //Se establece el modelo de consulta de datos.
                                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.

                                                    }                                                    
                                            }
                                            
                                    if($this->cntView == 9)
                                        {
                                            //El usuario accede como invitado para crear una cuenta.
                                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/main/login.php");
                                            }
                                    else
                                        {
                                            //Se considera la operacion ejecutada por un administrador.
                                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/usuarios/sysadmin/busUsuarios.php");
                                            }                                                                                
                                    }
                            else
                                {
                                    /*
                                     * En caso de ocurrir el error de validacion con el captcha,
                                     * se procesa la solicitud como una invocacion desde las funciones
                                     * de invitado.
                                     */
                                    $_GET['captcha_fail'] = $this->captchaFail;
                                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/usuarios/guest/opUsuarios.php");                                    
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
                    $consulta= 'UPDATE catUsuarios SET Status=1 where idUsuario='.$this->idUsuario; //Se establece el modelo de consulta de datos.
                    $dsUsuario = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/usuarios/sysadmin/busUsuarios.php");
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
            
    $objDALUsuario = new dalUsuarios();
    
    if($objDALUsuario->getAccion() == "CoER")
        {
            //CoER: CREACION o EDICION DE REGISTRO.
            $objDALUsuario->almacenarParametros();
            }    
    else
        {
            if($objDALUsuario->getAccion() == "EdRS")
                {
                    //EdRS:ELIMINACION de REGISTRO EN SISTEMA.         
                    $objDALUsuario->eliminarParametros();
                    }            
            }                 
    ?>