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

    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/main/usrctrl.class.php"); //Se carga la referencia de clase para control de accesos.
    
    class opGraficas
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de graficacion de resultados.
             */
            private $imgTitleURL = './img/menu/graficas.png';
            private $Title = 'Reporte de Seguimiento Graficado';

            public function __construct()
                {
                    //Declaracion de funcion constructor de la clase (VACIO)
                    }
                    
            public function controlBotones($Width, $Height)
                {
                    /*
                     * Esta funcion controla los botones que deberan verse en la pantalla deacuerdo con la accion solicitada por el
                     * usuario en la ventana previa.
                     * Si es una edicion, los botones borrar y guardar deben verse.
                     * Si es una creacion solo el boton guardar debe visualizarse.
                     */
                        
                    $botonera =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/busquedas.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Graficar" id="btnConsultar" title= "Graficar"/>';
                        
                    return $botonera;
                    }
                    
            public function cargarEntidades()
                {
                    /*
                     * Esta funcion genera la carga de la tupla de datos
                     * correspondiente al registro de entidades del sistema.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta= 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }

            public function drawcbEntidades()
                {
                    /*
                     * Esta funcion construye el codigo HTML correspondiente al
                     * control combobox para Entidades
                     */
                    $HTML = '';
                    $subconsulta = $this->cargarEntidades();
                        
                    $HTML .=  '<tr><td class="td-panel" width="100px"> Entidad: <select name= "idEntidad" id= "gridEntidad" value= "-1">
                                <option value=-1>Seleccione</option>';
                        
                    $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                        
                    while($RegNiveles)
                        {
                            $HTML .= '  <option value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'].'</option>';
                            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                        
                    $HTML .= '</select></td></tr>';
                        
                    return $HTML;
                    }

            public function drawUI()
                {
                    $HTML = '<html>
                                <head>
                                    <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                                    <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                                </head>
                                <body>
                                    <center>
                                        <div id="cntOperativo">
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>'.$this->drawcbEntidades().
                                                        '<tr><td><div id="graficaPasarela"></div></td></tr>
                                                    </table>
                                                </div>
                                                <div id="pie" class="pie-operativo">'.$this->controlBotones("32", "32").'</div>
                                            </div>
                                        </div>
                                    </center>
                                </body>
                            </html>';
                    return $HTML;
                    }
            }

    $objUsrCtrl = new usrctrl();
            
    if($objUsrCtrl->getCredenciales())
        {
            /*
             * Se valida que el usuario tenga sus credenciales cargadas
             * previo login en el sistema.
             */
            $idUsuario = $objUsrCtrl->getidUsuario($_SESSION['usuario'], $_SESSION['clave']);
            $Modulo = 'Graficas';
                
            if($objUsrCtrl->validarCredenciales($idUsuario, $Modulo)!='')
                {
                    /*
                     * Se valida que las credenciales autoricen la ejecucion del
                     * modulo solicitado.
                     */
                    $objOpGraficas = new opGraficas();
                    echo $objOpGraficas->drawUI();
                    }
            else
                {
                    /*
                     * En caso que no cuente con credenciales validas, el sistema impedira
                     * la brecha de seguridad.
                     */
                    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/noAutorizado.php");
                }
            }
    else
        {
            /*
             * En caso que no cuente con credenciales validas, el sistema impedira
             * la brecha de seguridad.
             */
            include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/frontend/notificaciones/noAutorizado.php");
            }
?>