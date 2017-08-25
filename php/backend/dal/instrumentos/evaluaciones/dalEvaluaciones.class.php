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
    
    class dalEvaluaciones
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion de los datos
             * correspondientes a la entidad Evaluaciones.
             */
            private $Accion = '';
            private $cntrlVar = 0;
            private $idEvaluacion = NULL;
            private $idCedula = NULL;
            private $idUsuario = NULL;
            private $Folio = '';
            private $Fecha = '';
            private $idFactor = '';
            private $idEscala = '';
            private $tmpFactores = '';
            private $tmpEscalas = '';           
            private $Status = 0;
            private $cntView = 0;

            public function __construct()
                {
                    /*
                     * Este constructor obtiene y valida los datos ingresados por medio de la
                     * URL por parte del usuario.
                     */
                    $this->cntrlVar = 0;
                            
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['accion'])){$this->Accion = $_GET['accion'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['id'])){$this->idEvaluacion = $_GET['id'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['idcedula'])){$this->idCedula = $_GET['idcedula'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['idusuario'])){$this->idUsuario = $_GET['idusuario'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['status'])){$this->Status = $_GET['status'];}
                                        
                    if($this->cntView=='8')
                        {
                            //Llamada a procedimiento para creacion de evaluacion.
                            if(isset($_GET['folio'])){$this->Folio = $_GET['folio'];}else{$this->cntrlVar+=1;}
                            if(isset($_GET['fecha']))
                                {
                                    date_default_timezone_set("America/Mexico_City");
                                    $this->Fecha = date("Y/m/d",strtotime($_GET['fecha']));
                                    }
                            else{$this->cntrlVar+=1;}
                            }
                    else
                        {
                            //Llamada a procedimiento desde una evaluacion. 
                            if(isset($_GET['idfactor']))
                                {
                                    $this->idFactor = $_GET['idfactor'];
                                    $this->tmpFactores = explode('%',$this->idFactor);
                                    }
                            else{$this->cntrlVar+=1;}
                            
                            if(isset($_GET['idescala']))
                                {
                                    $this->idEscala = $_GET['idescala'];
                                    $this->tmpEscalas = explode('%',$this->idEscala);
                                    }                
                            else{$this->cntrlVar+=1;}                                                        
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
                                                           
                            if($this->idEvaluacion != NULL)
                                {
                                    //EDICION DE REGISTRO                                    
                                    $consulta = 'UPDATE opEvaluaciones SET Folio=\''.$this->Folio.'\', idUsuario=\''.$this->idUsuario.'\', Fecha=\''.$this->Fecha.'\', idCedula=\''.$this->idCedula.'\', Status=\''.$this->Status.'\' WHERE idEvaluacion='.$this->idEvaluacion; //Se establece el modelo de consulta de datos.
                                    $dsCedula = $objConexion->conectar($consulta); //Se ejecuta la consulta.                                        
                                    }
                            else
                                {
                                    //CREACION DE REGISTRO.                                    
                                    $consulta = 'INSERT INTO opEvaluaciones (Folio, Fecha, idUsuario, idCedula) VALUES ('.'\''.$this->Folio.'\',\''.$this->Fecha.'\',\''.$this->idUsuario.'\',\''.$this->idCedula.'\')'; //Se establece el modelo de consulta de datos.
                                    $dsCedula = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                    
                                    }
 
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/instrumentos/evaluaciones/busEvaluaciones.php");                                                    
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
                    $consulta= 'UPDATE opEvaluaciones SET Status=1 where idEvaluacion='.$this->idEvaluacion; //Se establece el modelo de consulta de datos.
                    $dsCedula = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/instrumentos/evaluaciones/busEvaluaciones.php");
                    }
                    
            public function getAccion()
                {
                    /*
                     * Esta funcion retorna el valor obtenido por medio de la URL
                     * en respuesta a la accion del usuario.
                     */
                        return $this->Accion;
                    }                  

            public function getView()
                {
                    /*
                     * Esta funcion retorna el valor obtenido por medio de la URL
                     * en respuesta a la accion del usuario.
                     */
                    return $this->cntView;
                    }                                         
                                        
            public function existencias($idFactor, $idEvaluacion)
                {
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    
                    $consulta = "SELECT *FROM opResParEva WHERE idEvaluacion=".$idEvaluacion." AND idFactor=".$idFactor;
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    
                    if($dataset)
                        {
                            return mysqli_num_rows($dataset);
                            }
                    else
                        {
                            return 0;
                            }
                    }
                                        
            public function guardarCompleta()
                {
                    /*
                     * Esta funcion ejecuta el procedimiento de almacenamiento de la evaluacion
                     * terminada por el usuario.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    if($this->cntrlVar == 0)
                        {
                            /*
                             * Si la obtencion de datos por medio del URL no arrojo errores de valor nulo,
                             * se procede a la ejecucion del bloque de almacenamiento de datos.
                             */
                            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    
                            for($conteo = 1; $conteo < count($this->tmpFactores); $conteo++)
                                {
                                    /*
                                     * Se ejecuta el proceso de almacenamiento de datos, validando
                                     * que no existan referencias previas.
                                     */
                                    if($this->existencias($this->tmpFactores[$conteo], $this->idEvaluacion) == 0)
                                        {
                                            $consulta = 'INSERT INTO opResParEva (idFactor, idEscala, idEvaluacion) VALUES ('.'\''.$this->tmpFactores[$conteo].'\',\''.$this->tmpEscalas[$conteo].'\',\''.$this->idEvaluacion.'\')'; //Se establece el modelo de consulta de datos.
                                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                            }
                                    else
                                        {
                                            $consulta = 'UPDATE opResParEva SET idFactor=\''.$this->tmpFactores[$conteo].'\', idEscala=\''.$this->tmpEscalas[$conteo].'\', idEvaluacion=\''.$this->idEvaluacion.'\', Status=\''.$this->Status.'\' WHERE idEvaluacion='.$this->idEvaluacion.' AND idFactor='.$this->tmpFactores[$conteo]; //Se establece el modelo de consulta de datos.
                                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                            }
                                    }
                                         
                            $consulta = 'UPDATE opEvaluaciones SET Status=3 WHERE idEvaluacion='.$this->idEvaluacion; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/instrumentos/evaluaciones/busEvaluaciones.php");
                            }
                    else
                        {
                            //FALLO DE LA VALIDACION DE PARAMETROS POR URL.                            
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/ERROR405.php");
                            }                    
                    }

            public function guardarParcial()
                {
                    /*
                     * Esta funcion ejecuta el procedimiento de almacenamiento de la evaluacion
                     * terminada por el usuario.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    if($this->cntrlVar == 0)
                        {
                            /*
                             * Si la obtencion de datos por medio del URL no arrojo errores de valor nulo,
                             * se procede a la ejecucion del bloque de almacenamiento de datos.
                             */
                            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    
                            for($conteo = 1; $conteo < count($this->tmpFactores); $conteo++)
                                {
                                    /*
                                     * Se ejecuta el proceso de almacenamiento de datos, validando
                                     * que no existan referencias previas.
                                     */
                                    if($this->existencias($this->tmpFactores[$conteo], $this->idEvaluacion) == 0)
                                        {
                                            $consulta = 'INSERT INTO opResParEva (idFactor, idEscala, idEvaluacion) VALUES ('.'\''.$this->tmpFactores[$conteo].'\',\''.$this->tmpEscalas[$conteo].'\',\''.$this->idEvaluacion.'\')'; //Se establece el modelo de consulta de datos.
                                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                            }
                                    else
                                        {
                                            $consulta = 'UPDATE opResParEva SET idFactor=\''.$this->tmpFactores[$conteo].'\', idEscala=\''.$this->tmpEscalas[$conteo].'\', idEvaluacion=\''.$this->idEvaluacion.'\', Status=\''.$this->Status.'\' WHERE idEvaluacion='.$this->idEvaluacion.' AND idFactor='.$this->tmpFactores[$conteo]; //Se establece el modelo de consulta de datos.
                                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                            }
                                    }                                         
                             
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/instrumentos/evaluaciones/busEvaluaciones.php");
                            }
                    else
                        {
                            //FALLO DE LA VALIDACION DE PARAMETROS POR URL.                            
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/ERROR405.php");
                            }
                    }                    
            }
            
    $objDALEvaluaciones = new dalEvaluaciones();
            
    if($objDALEvaluaciones->getAccion() == "CoER")
        {
            //CoER: CREACION o EDICION DE REGISTRO.
            if($objDALEvaluaciones->getView()=="8")
                {
                    //Llamada de procedimiento desde la creacion del formato de
                    //evaluacion.
                    $objDALEvaluaciones->almacenarParametros();
                    }
            else
                {
                    if($objDALEvaluaciones->getView()=="9")
                        {
                            //Llamada de procedimiento desde el almacenamiento de una
                            //evaluacion terminada.
                            $objDALEvaluaciones->guardarCompleta();
                            }
                    else
                        {
                            if($objDALEvaluaciones->getView()=="7")
                                {
                                    //Llamada de procedimiento desde el almacenamiento
                                    //de una evaluacion parcial.
                                    $objDALEvaluaciones->guardarParcial();
                                    }
                            }                            
                    }                    
            }
    else
        {
            if($objDALEvaluaciones->getAccion() == "EdRS")
                {
                    //EdRS:ELIMINACION de REGISTRO EN SISTEMA.
                    $objDALEvaluaciones->eliminarParametros();
                    }
            }            
?>