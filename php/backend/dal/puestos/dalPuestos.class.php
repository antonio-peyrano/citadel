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
    
    class dalPuestos
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion de los datos
             * correspondientes a la entidad Colonias.
             */
            private $Accion = '';
            private $cntrlVar = 0;
            private $idPuesto = NULL;
            private $idRelEntPst = NULL;
            private $idEntidad = '';
            private $nonidEntidad = '';
            private $Puesto = '';
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
                    if(isset($_GET['id'])){$this->idPuesto = $_GET['id'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['puesto'])){$this->Puesto = $_GET['puesto'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['identidad'])){$this->idEntidad = $_GET['identidad'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['nonidentidad'])){$this->nonidEntidad = $_GET['nonidentidad'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['status'])){$this->Status = $_GET['status'];}else{$this->cntrlVar+=1;}
                    }

            public function existencias($idRegEnt, $idRegPst)
                {
                    /*
                     * Esta función establece la busqueda para determinar si un registro ya existe en el sistema
                     * con las condiciones proporcionadas.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT *FROM opRelEntPst WHERE idPuesto='.$idRegPst.' AND idEntidad='.$idRegEnt; //Se establece el modelo de consulta de datos.
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
                            
                            $temp = explode('%',$this->idEntidad); //Aqui se convierte el vector en un arreglo con los id seleccionados.
                            $nontemp = explode('%',$this->nonidEntidad); //Aqui se convierte el vector en un arreglo con los id no seleccionados.
                            
                            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.                            
                                                           

                            if($this->idPuesto != NULL)
                                {
                                    //EDICION DE REGISTRO 
                                    $consulta = 'UPDATE catPuestos SET Puesto=\''.$this->Puesto.'\', Status=\''.$this->Status.'\' WHERE idPuesto='.$this->idPuesto; //Se establece el modelo de consulta de datos.                                    
                                    $dsUsuario = $objConexion->conectar($consulta); //Se ejecuta la consulta.
                                    
                                    //Se crean los elementos de la relacion.
                                    for($conteo=1; $conteo < count($temp); $conteo++)
                                        {
                                            if(!$this->existencias($temp[$conteo], $this->idPuesto))
                                                {
                                                    /*
                                                     * En caso de no existir referencias previas, se crean en la entidad de las relaciones.
                                                     */
                                                    $consulta = 'INSERT INTO opRelEntPst (idEntidad, idPuesto) VALUES ('.'\''.$temp[$conteo].'\',\''.$this->idPuesto.'\')'; //Se establece el modelo de consulta de datos.
                                                    $dataset = $objConexion->conectar($consulta); //Se ejecuta la consulta.
                                                    }
                                            else
                                                {
                                                    /*
                                                     * En caso de existir referencias previas, considerando que la relación fue eliminada previamente.
                                                     */
                                                    $consulta = 'UPDATE opRelEntPst SET Status= 0 WHERE idPuesto='.$this->idPuesto.' AND idEntidad='.$temp[$conteo]; //Se establece el modelo de consulta de datos.
                                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                    }
                                            }
                                    
                                    //Se eliminan los elementos de la relacion si fueron desmarcados.
                                    for($conteo=1; $conteo < count($nontemp); $conteo++)
                                        {
                                            if($this->existencias($nontemp[$conteo], $this->idPuesto))
                                                {
                                                    /*
                                                     * En caso de existir referencias previas, se eliminan en la entidad de las relaciones.
                                                     */
                                                    $consulta = 'UPDATE opRelEntPst SET Status= 1 WHERE idPuesto='.$this->idPuesto.' AND idEntidad='.$nontemp[$conteo]; //Se establece el modelo de consulta de datos.
                                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                    }
                                            }
                                    }
                            else
                                {                                    
                                    /*
                                     * En caso que la acción ejecutada sea una creación.
                                     */
                                    $consulta = 'INSERT INTO catPuestos (Puesto) VALUES ('.'\''.$this->Puesto.'\')'; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    
                                    //Se busca el puesto creado para obtener su id.
                                    $consulta = 'SELECT *FROM catPuestos WHERE Puesto LIKE \'%'.$this->Puesto.'%\''; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                                    
                                    //Se crean los elementos de la relacion.
                                    for($conteo=1; $conteo < count($temp); $conteo++)
                                        {
                                            $consulta = 'INSERT INTO opRelEntPst (idEntidad, idPuesto) VALUES ('.'\''.$temp[$conteo].'\',\''.$Registro['idPuesto'].'\')'; //Se establece el modelo de consulta de datos.
                                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                            }
                                    }    
                                    
                            include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/puestos/busPuestos.php");
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

                    if($this->cntView == '3')
                        {
                            /*
                             * En caso que el origen no proceda del listado, se procede a borrar el puesto completo.
                             */
                            $consulta = 'UPDATE catPuestos SET Status=1 where idPuesto='.$this->idPuesto; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                        
                            /*
                             * Con las relaciones de las entidades.
                             */
                            $consulta = 'UPDATE opRelEntPst SET Status=1 where idPuesto='.$this->idPuesto; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                            }
                    else
                        {
                            /*
                             * En caso que el origen proceda del listado, se procede a borrar la relación seleccionada.
                             */
                            $consulta= 'UPDATE opRelEntPst SET Status=1 where idRelEntPst='.$this->idPuesto; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                            }
                    
                    include_once($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/puestos/busPuestos.php");
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
            
    $objDALPuesto = new dalPuestos();
            
    if($objDALPuesto->getAccion() == "CoER")
        {
            //CoER: CREACION o EDICION DE REGISTRO.
            $objDALPuesto->almacenarParametros();
            }
    else
        {
            if($objDALPuesto->getAccion() == "EdRS")
                {
                    //EdRS:ELIMINACION de REGISTRO EN SISTEMA.
                    $objDALPuesto->eliminarParametros();
                    }
            }            
?>