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
    
    class dalEmpleados
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion de los datos
             * correspondientes a la entidad empleados.
             */
            private $Accion = '';
            private $cntrlVar = 0;
            private $idEmpleado = NULL;
            private $idColonia = NULL;
            private $idEntidad = NULL;
            private $idPuesto = NULL;
            private $Paterno = '';
            private $Materno = '';
            private $Nombre = '';
            private $Calle = '';
            private $nInt = '';
            private $nExt = '';
            private $curp = '';
            private $rfc = '';
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
                    if(isset($_GET['id'])){$this->idEmpleado = $_GET['id'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['identidad'])){$this->idEntidad = $_GET['identidad'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['idpuesto'])){$this->idPuesto = $_GET['idpuesto'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['idcolonia']))
                        {
                            $substr= explode("-",$_GET['idcolonia']);
                            $this->idColonia = $substr[0];
                            }
                    else
                        {
                            $this->cntrlVar+=1;
                            }
                    if(isset($_GET['curp'])){$this->curp = $_GET['curp'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['rfc'])){$this->rfc = $_GET['rfc'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['paterno'])){$this->Paterno = $_GET['paterno'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['materno'])){$this->Materno = $_GET['materno'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['nombre'])){$this->Nombre = $_GET['nombre'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['fnacimiento']))
                        {
                            date_default_timezone_set("America/Mexico_City");
                            $this->fNacimiento = date("Y/m/d",strtotime($_GET['fnacimiento']));
                            }
                    else{$this->cntrlVar+=1;}
                    if(isset($_GET['calle'])){$this->Calle = $_GET['calle'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['nint'])){$this->nInt = $_GET['nint'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['next'])){$this->nExt = $_GET['next'];}else{$this->cntrlVar+=1;}
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
                                                           

                            if($this->idEmpleado != NULL)
                                {
                                    //EDICION DE REGISTRO                                    
                                    $consulta = 'UPDATE catEmpleados SET Paterno=\''.$this->Paterno.'\', Materno=\''.$this->Materno.'\', Nombre=\''.$this->Nombre.'\', curp=\''.$this->curp.'\', rfc=\''.$this->rfc.'\', Calle=\''.$this->Calle.'\', nInt=\''.$this->nInt.'\', nExt=\''.$this->nExt.'\', idColonia=\''.$this->idColonia.'\', telFijo=\''.$this->telFijo.'\', telCel=\''.$this->telCel.'\', idEntidad=\''.$this->idEntidad.'\', idPuesto=\''.$this->idPuesto.'\', Status=\''.$this->Status.'\', fNacimiento=\''.$this->fNacimiento.'\' where idEmpleado='.$this->idEmpleado; //Se establece el modelo de consulta de datos.
                                    $dsUsuario = $objConexion->conectar($consulta); //Se ejecuta la consulta.                                        
                                    }
                            else
                                {
                                    //CREACION DE REGISTRO.                                    
                                    $consulta = 'INSERT INTO catEmpleados (Paterno, Materno, Nombre, curp, rfc, telFijo, telCel, fNacimiento, Calle, nInt, nExt, idColonia, idEntidad, idPuesto) VALUES ('.'\''.$this->Paterno.'\',\''.$this->Materno.'\', \''.$this->Nombre.'\', \''.$this->curp.'\', \''.$this->rfc.'\', \''.$this->telFijo.'\', \''.$this->telCel.'\', \''.$this->fNacimiento.'\', \''.$this->Calle.'\', \''.$this->nInt.'\', \''.$this->nExt.'\', \''.$this->idColonia.'\', \''.$this->idEntidad.'\', \''.$this->idPuesto.'\')'; //Se establece el modelo de consulta de datos.
                                    $dsUsuario = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                    
                                    }    
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/empleados/busEmpleados.php");                                                    
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
                    $consulta= 'UPDATE catEmpleados SET Status=1 where idEmpleado='.$this->idEmpleado; //Se establece el modelo de consulta de datos.
                    $dsUsuario = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/empleados/busEmpleados.php");
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
            
    $objDALEmpleado = new dalEmpleados();
            
    if($objDALEmpleado->getAccion() == "CoER")
        {
            //CoER: CREACION o EDICION DE REGISTRO.
            $objDALEmpleado->almacenarParametros();
            }
    else
        {
            if($objDALEmpleado->getAccion() == "EdRS")
                {
                    //EdRS:ELIMINACION de REGISTRO EN SISTEMA.
                    $objDALEmpleado->eliminarParametros();
                    }
            }            
?>