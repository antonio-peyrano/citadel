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
    class usrctrl
        {
            /*
             * Esta clase contiene los atributos y procedimientos necesarios para
             * el manejo de los permisos de usuario.
             */
            
            public function __construct()
                {
                    /*
                     * Declaracion de constructor de clase (VACIO)
                     */
                    }
                    
            public function setCredenciales($idUsuario, $Usuario, $Nivel, $Clave)
                {
                    /*
                     * Esta funcion obtiene las credenciales del usuario (ID, Nombre, Nivel)
                     * y transfiere a las variables de control de sesion.
                     */
                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('citadel');
                            session_start();
                            }
                 
                    $_SESSION['idusuario'] = $idUsuario;
                    $_SESSION['usuario'] = $Usuario;
                    $_SESSION['nivel'] = $Nivel;
                    $_SESSION['clave'] = $Clave;
                    $_SESSION['idEmpleado'] = $this->getidEmpleado($idUsuario);
                    }

            public function getCredenciales()
                {
                    /*
                     * Esta funcion obtiene las credenciales del usuario (ID, Nombre, Nivel)
                     * y envia el valor de permiso concedido.
                     */
                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('citadel');
                            session_start();
                            }
                    
                    if(isset($_SESSION['idusuario'])&&isset($_SESSION['usuario'])&&isset($_SESSION['nivel'])&&isset($_SESSION['clave']))
                        {
                            return true; //Se asigna el valor de confirmacion de permiso.
                            }
                    
                    return false; //Si fallo la verificacion, se indica la denegacion de permiso.
                    }

            public function getURLModulo($Modulo)
                {
                    /*
                     * Esta funcion obtiene la URL del modulo para redireccionar en la carga
                     * de la pagina
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $conexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    
                    $consulta = 'SELECT *FROM catModulos WHERE Modulo="'.$Modulo.'" AND Status=0';
                    $dsModulos = $conexion->conectar($consulta);
                    $drModulos = @mysqli_fetch_array($dsModulos,MYSQLI_ASSOC);
                    
                    if($drModulos)
                        {
                            /*
                             * Al localizarse el registro correspondiente, se envia la URL
                             * al manejador del constructor de la UI.
                             */
                            return $drModulos['URL'];
                            }
                    
                    return ''; //En caso contrario se envia el valor de fallo.
                    }
            
            public function getidEmpleado($idUsuario)
                {
                    /*
                     * Esta funcion obtiene el ID del empleado en el sistema para
                     * la gestion de la visualizacion de la planeacion.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $conexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    
                    $consulta = 'SELECT opEmpleados.idEmpleado FROM (opEmpleados INNER JOIN relUsrEmp ON opEmpleados.idEmpleado = relUsrEmp.idEmpleado) WHERE relUsrEmp.idUsuario="'.$idUsuario.'" AND opEmpleados.Status=0';
                    $dsEmpleados = $conexion->conectar($consulta);
                    $drEmpleados = @mysqli_fetch_array($dsEmpleados,MYSQLI_ASSOC);

                    if($drEmpleados)
                        {
                            /*
                             * Al localizarse el registro correspondiente, se envia la URL
                             * al manejador del constructor de la UI.
                             */
                            return $drEmpleados['idEmpleado'];
                            }
                    
                    return -1; //En caso contrario se envia el valor de fallo.                    
                    }
                                        
            public function getidUsuario($Usuario, $Clave)
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
                            $this->setCredenciales($drUsuarios['idUsuario'], $drUsuarios['Usuario'], $drUsuarios['Nivel'], $Clave);
                            return $drUsuarios['idUsuario'];
                            }
                    
                    return 0; //En caso contrario se envia el valor de fallo.
                    }
                    
            public function validarCredenciales($idUsuario, $Modulo)
                {
                    /*
                     * Esta funcion valida que el usuario tenga permisos sobre el modulo
                     * al que se estan validando las credenciales de uso. Si existe se
                     * retorna la URL almacenada. En caso contrario se envia cadena
                     * vacia.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $conexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $consulta = 'SELECT *FROM (opRelPerUsr INNER JOIN catModulos ON opRelPerUsr.idModulo = catModulos.idModulo) WHERE idUsuario="'.$idUsuario.'" AND Modulo="'.$Modulo.'" AND opRelPerUsr.Status=0';
                    $dsPermisos = $conexion->conectar($consulta);
                    $drPermisos = @mysqli_fetch_array($dsPermisos,MYSQLI_ASSOC);
                    
                    if($drPermisos)
                        {
                            /*
                             * Al localizarse el registro correspondiente, se envia la URL
                             * al manejador del constructor de la UI.
                             */
                            return $drPermisos['URL'];
                            }
                    
                    return ''; //En caso contrario se envia el valor de fallo.
                    }                    
            }
?>