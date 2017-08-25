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

    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    
    class dalClientes
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion de los datos
             * correspondientes a la entidad clientes.
             */
            private $Accion = '';
            private $cntrlVar = 0;
            private $idCliente = NULL;
            private $curp = '';
            private $Paterno = '';
            private $Materno = '';
            private $Nombre = '';
            private $fNacimiento = '';
            private $telFijo = '';
            private $telCel = '';
            private $Status = 0;
            private $cntView = 0;

            public function __construct()
                {
                    /*
                     * Este constructor obtiene y valida los datos ingresados por medio de la
                     * URL por parte del usuario.
                     */
                    $this->cntrlVar = 0;
                            
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['accion'])){$this->Accion = $_GET['accion'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['id'])){$this->idCliente = $_GET['id'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['curp'])){$this->curp = $_GET['curp'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['paterno'])){$this->Paterno = $_GET['paterno'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['materno'])){$this->Materno = $_GET['materno'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['nombre'])){$this->Nombre = $_GET['nombre'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['fnacimiento']))
                        {
                            date_default_timezone_set("America/Mexico_City");
                            $this->fNacimiento = date("Y/m/d",strtotime($_GET['fnacimiento']));
                            }
                    else{$this->cntrlVar+=1;}
                    if(isset($_GET['telfijo'])){$this->telFijo = $_GET['telfijo'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['telcel'])){$this->telCel = $_GET['telcel'];}else{$this->cntrlVar+=1;}
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
                                                           

                            if($this->idCliente != NULL)
                                {
                                    //EDICION DE REGISTRO                                    
                                    $consulta = 'UPDATE catClientes SET Paterno=\''.$this->Paterno.'\', Materno=\''.$this->Materno.'\', Nombre=\''.$this->Nombre.'\', curp=\''.$this->curp.'\', telFijo=\''.$this->telFijo.'\', telCel=\''.$this->telCel.'\', Status=\''.$this->Status.'\', fNacimiento=\''.$this->fNacimiento.'\' where idCliente='.$this->idCliente; //Se establece el modelo de consulta de datos.
                                    $dsUsuario = $objConexion->conectar($consulta); //Se ejecuta la consulta.                                        
                                    }
                            else
                                {
                                    //CREACION DE REGISTRO.                                    
                                    $consulta = 'INSERT INTO catClientes (Paterno, Materno, Nombre, curp, telFijo, telCel, fNacimiento) VALUES ('.'\''.$this->Paterno.'\',\''.$this->Materno.'\', \''.$this->Nombre.'\', \''.$this->curp.'\', \''.$this->telFijo.'\', \''.$this->telCel.'\', \''.$this->fNacimiento.'\')'; //Se establece el modelo de consulta de datos.
                                    $dsUsuario = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                    
                                    }    
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/clientes/busClientes.php");                                                    
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
                    $consulta= 'UPDATE catClientes SET Status=1 where idCliente='.$this->idCliente; //Se establece el modelo de consulta de datos.
                    $dsUsuario = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/clientes/busClientes.php");
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
            
    $objDALCliente = new dalClientes();
            
    if($objDALCliente->getAccion() == "CoER")
        {
            //CoER: CREACION o EDICION DE REGISTRO.
            $objDALCliente->almacenarParametros();
            }
    else
        {
            if($objDALCliente->getAccion() == "EdRS")
                {
                    //EdRS:ELIMINACION de REGISTRO EN SISTEMA.
                    $objDALCliente->eliminarParametros();
                    }
            }            
?>