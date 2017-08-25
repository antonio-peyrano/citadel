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
    
    class dalCedulas
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion de los datos
             * correspondientes a la entidad Cedulas.
             */
            private $Accion = '';
            private $cntrlVar = 0;
            private $idCedula = NULL;
            private $idEntidad = NULL;
            private $Folio = '';
            private $Descripcion = '';
            private $Fecha = '';
            private $Horizonte = '';
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
                    if(isset($_GET['id'])){$this->idCedula = $_GET['id'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['identidad'])){$this->idEntidad = $_GET['identidad'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['folio'])){$this->Folio = $_GET['folio'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['descripcion'])){$this->Descripcion = $_GET['descripcion'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['fecha']))
                        {
                            date_default_timezone_set("America/Mexico_City");
                            $this->Fecha = date("Y/m/d H:i:s",strtotime($_GET['fecha']));
                            }
                    else{$this->cntrlVar+=1;}                    
                    if(isset($_GET['horizonte'])){$this->Horizonte = $_GET['horizonte'];}else{$this->cntrlVar+=1;}
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
                                                           
                            if($this->idCedula != NULL)
                                {
                                    //EDICION DE REGISTRO                                    
                                    $consulta = 'UPDATE opCedulas SET Folio=\''.$this->Folio.'\', Descripcion=\''.$this->Descripcion.'\', Fecha=\''.$this->Fecha.'\', Horizonte=\''.$this->Horizonte.'\', idEntidad=\''.$this->idEntidad.'\', Status=\''.$this->Status.'\' WHERE idCedula='.$this->idCedula; //Se establece el modelo de consulta de datos.
                                    $dsCedula = $objConexion->conectar($consulta); //Se ejecuta la consulta.                                        
                                    }
                            else
                                {
                                    //CREACION DE REGISTRO.                                    
                                    $consulta = 'INSERT INTO opCedulas (Folio, Descripcion, Fecha, Horizonte, idEntidad) VALUES ('.'\''.$this->Folio.'\',\''.$this->Descripcion.'\',\''.$this->Fecha.'\',\''.$this->Horizonte.'\',\''.$this->idEntidad.'\')'; //Se establece el modelo de consulta de datos.
                                    $dsCedula = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                    
                                    }    
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/instrumentos/cedulas/busCedulas.php");                                                    
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
                    $consulta= 'UPDATE opCedulas SET Status=1 where idCedula='.$this->idCedula; //Se establece el modelo de consulta de datos.
                    $dsCedula = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/instrumentos/cedulas/busCedulas.php");
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
            
    $objDALCedulas = new dalCedulas();
            
    if($objDALCedulas->getAccion() == "CoER")
        {
            //CoER: CREACION o EDICION DE REGISTRO.
            $objDALCedulas->almacenarParametros();
            }
    else
        {
            if($objDALCedulas->getAccion() == "EdRS")
                {
                    //EdRS:ELIMINACION de REGISTRO EN SISTEMA.
                    $objDALCedulas->eliminarParametros();
                    }
            }            
?>