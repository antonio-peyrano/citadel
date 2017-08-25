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
    header('Content-Type: text/html; charset=ISO-8859-1'); //Forzar la codificacion a ISO-8859-1.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/codificador.class.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    
    class profile
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el manejo de la interfaz
             * de perfil de usuario que se visualizaran de lado cliente.
             */
            private $idUsuario="";
            private $Usuario = "";
            private $Clave = "";
            private $Nivel = "";
            
            public function __construct()
                {
                    /*
                     * Declaracion de constructor en el que se valida los datos obtenidos
                     * por medio de la URL.
                     */
                    if(isset($_GET['usuario'])){$this->Usuario=$_GET['usuario'];}
                    if(isset($_GET['clave'])){$this->Clave=$_GET['clave'];}
                    }
                    
            public function getProfile($Usuario, $Clave)
                {
                    /*
                     * Esta funcion valida los datos proporcionados por medio del formulario
                     * de ingreso, en caso de localizarse un usuario valido, se retorna su ID.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objCodificador = new codificador();
                    $conexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    
                    $consulta = 'SELECT *FROM (catUsuarios INNER JOIN catNiveles ON catUsuarios.idNivel = catNiveles.idNivel) WHERE Usuario="'.$Usuario.'" AND Clave="'.$objCodificador->encrypt($Clave, "ouroboros").'" AND catUsuarios.Status=0';
                    $dsUsuarios = $conexion->conectar($consulta);
                    $drUsuarios = @mysqli_fetch_array($dsUsuarios,MYSQLI_ASSOC);
                    
                    if($drUsuarios)
                        {
                            /*
                             * Al localizarse el registro correspondiente, se envia la URL
                             * al manejador del constructor de la UI.
                             */
                            $this->setidUsuario($drUsuarios['idUsuario']);
                            $this->setNivel($drUsuarios['Nivel']);
                            return true;
                            }
                    
                    return false; //En caso contrario se envia el valor de fallo.                    
                    }
                                        
            public function getidUsuario()
                {
                    /*
                     * Esta funcion retorna el valor obtenido del id del usuario.
                     */
                    return $this->idUsuario;
                    }

            public function setidUsuario($idUsuario)
                {
                    /*
                     * Esta funcion establece el valor obtenido del id del usuario.
                     */                    
                    $this->idUsuario = $idUsuario;
                    }
                    
            public function getUsuario()
                {
                    /*
                     * Esta funcion retorna el valor obtenido el nombre del usuario.
                     */                    
                    return $this->Usuario;
                    }

            public function setUsuario($Usuario)
                {
                    /*
                     * Esta funcion establece el valor obtenido del id del usuario.
                     */                    
                    $this->Usuario = $Usuario;
                    }
                                        
            public function getClave()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de la clave de usuario.
                     */                    
                    return $this->Clave;
                    }

            public function setClave($Clave)
                {
                    /*
                     * Esta funcion establece el valor obtenido del la clave de usuario.
                     */                    
                    $this->Clave = $Clave;
                    }
                                        
            public function getNivel()
                {
                    /*
                     * Esta funcion retorna el valor obtenido del nivel de usuario.
                     */                    
                    return $this->Nivel;
                    }

            public function setNivel($Nivel)
                {
                    /*
                     * Esta funcion establece el valor obtenido del nivel de usuario.
                     */                    
                    $this->Nivel = $Nivel;
                    }                    
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion genera el codigo HTML que corresponde a la parte grafica
                     * del perfil de usuario como lo visualizara en cliente.
                     */
                    if($this->getProfile($this->getUsuario(),$this->getClave()))
                        {
                            //Si la autenticacion fue satisfactoria.
                            $HTML =     '<table class="profileUser">
                                            <tr><td>Bienvendio </td><td>'.$this->getUsuario().'</td></tr>                                            
                                        </table>';                            
                            }
                    else                    
                        {
                            //En caso de no autenticarse en el sistema, se regresa el parametro en blanco.
                            $HTML = '';                            
                            }
                    
                    return $HTML;
                    }
            }

    $objProfile = new profile();
    echo $objProfile->drawUI();            
?>