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
    
    class idPuesto
        {
            private $idEntidad = NULL;
            
            public function __construct()
                {
                    //Declaracion de constructor de clase (Vacio)
                    if(isset($_GET['identidad'])){$this->idEntidad = $_GET['identidad'];}
                    }
                    
            public function getParametro()
                {
                    //Esta funcion retorna el valor obtenido para parametro.
                    return $this->idPuesto;
                    }

            public function getidEntidad()
                {
                    //Esta funcion retorna el valor obtenido para idEntidad.
                    return $this->idEntidad;
                    }
                    
            public function cargarPuestos($idEntidad)
                {
                    /*
                     * Esta funcion establece la carga del conjunto de registros de puestos
                     * asociados a la entidad proporcionada.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT catPuestos.idPuesto, Puesto FROM (catPuestos INNER JOIN opRelEntPst ON opRelEntPst.idPuesto = catPuestos.idPuesto) WHERE catPuestos.Status=0 AND opRelEntPst.idEntidad='.$idEntidad; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }
                    
            public function drawCBPuesto($idEntidad)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * puestos.
                     */
                    $HTML = '<select class="inputform" name= "idPuesto" id= "idPuesto" value= "-1">
                                <option value=-1>Seleccione</option>';
                        
                    $subconsulta = $this->cargarPuestos($idEntidad);
                    $RegPuestos = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                        
                    while($RegPuestos)
                        {
                            if($Registro['idPuesto'] == $RegPuestos['idPuesto'])
                                {
                                    //Si el item fue previamente marcado, se selecciona en el codigo.
                                    $HTML .= '<option value='.$RegPuestos['idPuesto'].' selected>'.$RegPuestos['Puesto'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se escribe la secuencia base de codigo.
                                    $HTML .= '<option value='.$RegPuestos['idPuesto'].'>'.$RegPuestos['Puesto'].'</option>';
                                    }
                            
                            $RegPuestos = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                        
                    $HTML .= '</select>';
                    return $HTML;
                    }
            }
            
    $objidPuestos = new idPuesto();
    echo $objidPuestos->drawCBPuesto($objidPuestos->getidEntidad());
?>