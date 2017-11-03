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
    
    class idColonias
        {
            private $Parametro = '';
            
            public function __construct()
                {
                    //Declaracion de constructor de clase (Vacio)
                    if(isset($_GET['parametro'])){$this->Parametro = $_GET['parametro'];}
                    }
                    
            public function getParametro()
                {
                    //Esta funcion retorna el valor obtenido para parametro.
                    return $this->Parametro;
                    }
                    
            public function cargarColonias()
                {
                    /*
                     * Esta funcion establece la carga del conjunto de registros de colonias.
                     */                    
                    global $username, $password, $servername, $dbname;
                    $ColArray = array();                    
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT CONCAT(idColonia,\'-\', Colonia) AS Colonia FROM catColonias WHERE Status=0 AND Colonia LIKE \'%'.$this->getParametro().'%\''; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegColonia = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);

                    while($RegColonia)
                        {
                            $ColArray[] = utf8_encode($RegColonia['Colonia']);
                            $RegColonia = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                            }
                    
                    return $ColArray;                    
                    }
            }
            
    $objidColonias = new idColonias();
    echo json_encode($objidColonias->cargarColonias(),JSON_UNESCAPED_UNICODE);
?>