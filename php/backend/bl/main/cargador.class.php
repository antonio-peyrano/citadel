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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/codificador.class.php"); //Se carga la referencia de la clase para control del encriptado.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/main/usrctrl.class.php"); //Se carga la referencia de clase para control de accesos.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    
    class cargador
        {
            /*
             * Esta clase contiene los atributos y procedimientos necesarios para crear un entorno
             * sandbox para la interaccion con los modulos del sistema.
             */
            private $Modulo = '';
            private $lreq = '';
            private $idUsuario = 0;
            private $Usuario = '';
            private $Clave = '';
                                    
            public function __construct()
                {
                    /*
                     * Este constructor solo valida que la informacion obtenida por el metodo GET
                     * sea valida para la ejecucion del codigo.
                     */
                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('citadel');
                            session_start();
                            }
                                                
                    if(isset($_GET['modulo']))
                        {
                            //SE INVOCO DESDE LA BARRA DE MENU.
                            $this->Modulo = $_GET['modulo'];
                            $_SESSION['modulo'] = $_GET['modulo'];
                            }
                    else
                        {
                            //SE INVOCO DESDE UNA VENTANA DE LOGIN.
                            $this->Modulo = $_SESSION['modulo'];
                            }                            
                                                        
                    if(isset($_GET['lreq']))
                        {
                            //SE INVOCO DESDE LA BARRA DE MENU.
                            $this->lreq = $_GET['lreq'];
                            $_SESSION['lreq'] = $_GET['lreq'];
                            }
                    else
                        {
                            //SE INVOCO DESDE UNA VENTANA DE LOGIN.
                            $this->lreq = $_SESSION['lreq'];
                            }                                                     
                            
                    if(isset($_GET['usuario'])){$this->Usuario = $_GET['usuario'];}
                    if(isset($_GET['clave'])){$this->Clave = $_GET['clave'];}                                               
                    }
                                                    
            public function getModulo()
                {
                    //Esta funcion retorna el nombre del modulo almacenado.
                    return $this->Modulo;
                    }

            public function getlreq()
                {
                    //Esta funcion retorna el valor de control sobre requerimiento de login.
                    return $this->lreq;
                    }
                                        
            public function UIResponse()
                {
                    /*
                     * Esta funcion crea el contenido de la interfaz de usuario pasada la verificacion.
                     */
                    $objUsrCntrl = new usrctrl();
                    
                    if($this->lreq == '1')
                        {
                            /*
                             * Si el modulo requiere de una validacion de ingreso por medio de usuario
                             * y clave, se generan los controles de UILogin.
                             */
                                    
                            if($objUsrCntrl->getCredenciales())
                                {
                                    /*
                                     * Si el usuario ha validado preaviamente su existencia en el sistema,
                                     * sus credenciales son llamadas para validar su operacion.
                                     */
                                    $this->idUsuario = $objUsrCntrl->getidUsuario($_SESSION['usuario'], $_SESSION['clave']);
                                    }
                             else
                                {
                                    /*
                                     * Si el usuario no cuenta aun con credenciales, se procede a la carga
                                     * de estas en sistema.
                                     */                                    
                                    $this->idUsuario = $objUsrCntrl->getidUsuario($this->Usuario, $this->Clave);
                                    }
                                                                                                   
                            if($this->idUsuario != 0)
                                {
                                    /*
                                     * Si se ha localizado un usuario con los datos proporcionados por la interfaz
                                     * de login, se procede a la validacion de credenciales sobre el modulo.
                                     */
                                    $URL = $objUsrCntrl->validarCredenciales($this->idUsuario, $this->Modulo); //Se obtiene la URL del modulo en el servidor.
                                    
                                    if($URL == '')
                                        {
                                            /*
                                             * Si la validacion de credenciales no arroja una URL valida para la carga del modulo,
                                             * se notifica al usuario que no esta autorizado.
                                             */
                                            include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/noAutorizado.php");
                                            }
                                    else
                                        {
                                            
                                            /*
                                             * En caso contrario, se redirecciona a la URL obtenida.
                                             */
                                            if(!file_exists($_SERVER['DOCUMENT_ROOT'].$URL))
                                                {
                                                    //VALIDACION DE UBICACION EN DIRECTORIOS: FALLA.
                                                    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/ERROR404.php");
                                                    }
                                            else
                                                {
                                                    //VALIDACION DE UBICACION EN DIRECTORIOS: EXITOSA.
                                                    include_once ($_SERVER['DOCUMENT_ROOT'].$URL);
                                                    }                                                                                                                                            
                                            }                                            
                                    }
                            else
                                {
                                    /*
                                     * Si los datos proporcionados por el usuario no arrojan un ID,
                                     * se invoca de nuevo el formulario de inicio de sesion.
                                     */
                                    if(($this->idUsuario == 0)&&(isset($_GET['usuario'])&&isset($_GET['clave'])))
                                        {
                                            //SI EL NOMBRE DE USUARIO Y CLAVE NO ARROJAN UN RESULTADO.
                                            include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/ERRORUA.php");
                                            }
                                    else
                                        {
                                            //POR DEFAULT.
                                            include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/main/login.php");
                                            }                                                                                
                                    }                            
                            }
                    else
                        {
                            /*
                             * En caso que se solicite un ingreso a modulo sin validacion,
                             * se carga la interfaz sin login.
                             */
                            $URL = $objUsrCntrl->getURLModulo($this->Modulo);
                            
                            if(($URL == '')||(!file_exists($_SERVER['DOCUMENT_ROOT'].$URL)))
                                {
                                    /*
                                     * En caso de no contar con una URL valida, se redirecciona a una pagina de ERROR 404
                                     */
                                    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/ERROR404.php");
                                    }
                            else
                                {
                                    /*
                                     * En caso contrario, se envia al modulo indicado.
                                     */
                                    include_once ($_SERVER['DOCUMENT_ROOT'].$URL);
                                    }                                    
                            }                            
                    }                    
            }

    $cargador = new cargador();
            
    echo    '<html>
                <head>
                </head>
                <body>
                    <div class="area-deslizar"></div>
                        <a href="#" data-toggle=".contenedor" id="menu-lateral-toggle">
                            <img src="./img/menu/menu.png" id="menu-icono" alt="Menu" height="32" width="32" title="Menu">
                        </a>
                    </div>  
                    <div id="sandbox">';
            
                    $cargador->UIResponse();
            
    echo            '</div>
                </body>
            </html>';            
?>