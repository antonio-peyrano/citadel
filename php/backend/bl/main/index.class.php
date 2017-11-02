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

    class index
        {
            /*
             * Esta clase contiene los atributos y procedimientos necesarios
             * para la construccion de la interfaz principal del sistema.
             */
            public function __construct()
                {
                    /*
                     * Definicion de contructo de clase (VACIO)
                     */    
                    }
                    
            //DEFINICION DE MENU DESPLEGABLE
            public function headItem($id, $onClick, $imgURL, $caption)
                {
                    /*
                     * Esta funcion genera la linea HTML que corresponde a un elemento cabecera de 
                     * la lista de menu en pantalla.
                     */
                    
                    $item='<li id="'.$id.'"><a href="#" class="desplegable" onclick="'.$onClick.'"><img onmouseover="bigImg(this)" onmouseout="normalImg(this)" src="'.$imgURL.'" width="35" height="35">'.$caption.'</a>';
                    
                    return $item;
                    }
                    
            public function bodyItem($id, $onClick, $imgURL, $caption)
                {
                    /*
                     * Esta funcion genera la linea HTML que corresponde a un elemento anidado de
                     * la lista de menu en pantalla.
                     */
                    
                    $item='<li id="'.$id.'"><a href="#" onclick="'.$onClick.'"><img onmouseover="bigImg(this)" onmouseout="normalImg(this)" src="'.$imgURL.'" width="35" height="35">'.$caption.'</a></li>';
                         
                    return $item;
                    }

            private function drawMenu()
                {
                    /*
                     * Esta funcion dibuja el menu de operaciones de la interfaz principal.
                     */
                         
                    $menuBody = '   <div id="menu-lateral">
                                        <ul class="navegador">'.
                                            $this->headItem("item-cabecera-01", "", "./img/menu/configsys.png", "Catalogos").
                                                '<ul class="subnavegador">'
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Colonias&lreq=1','escritorio');", "./img/menu/colonias.png", "Colonias")                                                    
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Entidades&lreq=1','escritorio');", "./img/menu/entidades.png", "Entidades")
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Puestos&lreq=1','escritorio');", "./img/menu/puestos.png", "Puestos")
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Clientes&lreq=1','escritorio');", "./img/menu/clientes.png", "Clientes")
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Empleados&lreq=1','escritorio');", "./img/menu/empleados.png", "Empleados")
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Usuarios&lreq=1','escritorio');", "./img/menu/usuarios.png", "Usuarios")
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Cedulas&lreq=1','escritorio');", "./img/menu/cedula.png", "Cedulas")
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Factores&lreq=1','escritorio');", "./img/menu/factores.png", "Factores")
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Escalas&lreq=1','escritorio');", "./img/menu/escalas.png", "Escalas").
                                                '</ul></li></ul>'.
                                        '<ul class="navegador">'.
                                            $this->headItem("item-cabecera-01", "", "./img/menu/atencion.png", "Atencion a Cliente").
                                                '<ul class="subnavegador">'
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Solicitudes&lreq=1','escritorio');", "./img/menu/solicitudes.png", "Solicitudes")
                                                .$this->bodyItem("item-cuerpo-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Evaluaciones&lreq=1','escritorio');", "./img/menu/evaluaciones.png", "Evaluaciones").
                                                '</ul></li></ul>'.
                                        '<ul class="navegador">'.
                                            $this->headItem("item-cabecera-01", "cargar('./php/backend/bl/main/cargador.class.php','?modulo=Graficas&lreq=1','escritorio');", "./img/menu/graficas.png", "Graficas").
                                            $this->headItem("item-cabecera-01", "", "./img/menu/contacto.png", "Contacto").
                                            $this->headItem("item-cabecera-03", "validarUsuario('./php/backend/bl/main/logout.php','','escritorio');", "./img/menu/logout.png", "Cerrar Sesion").
                                        '</ul>
                                        <br>
                                        <div id= "profile" class="infousuario">'
                                            .$this->setProfile().
                                        '</div>
                                    </div>';
                         
                    return $menuBody;
                    }
                    
            //DEFINICION DE CUERPO DE LA PAGINA.                  
            private function HTMLHead()
                {
                    /*
                     * Esta funcion contiene la informacion a incluir en la cabecera del html.
                     */
                    $head = '   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
                                <link rel="stylesheet" href="./css/menu.css"></style>
                                <link rel="stylesheet" href="./css/login.css"></style>
                                <link rel="stylesheet" href="./css/main.css"></style>
                                <link rel="stylesheet" href="./css/notificaciones.css"></style>
                                <link rel="stylesheet" href="./css/operativo.css"></style>
                                <link rel="stylesheet" href="./css/datagrid.css"></style>
                                <link rel="stylesheet" href="./css/jquery-ui.css"></style>                                
                                <link rel="stylesheet" href="./css/bootstrap.min.css"></style>                        
                                <link rel="icon" type="image/png" href="./img/icologo.png" />
                                <title>Citadel</title>
                                <script type="text/javascript" src="./js/jquery/jquery-1.9.1.js"></script>
                                <script type="text/javascript" src="./js/jquery/jquery-1.9.1.min.js"></script>                        
                                <script type="text/javascript" src="./js/jquery/jquery.jscrollpane.min.js"></script>
                                <script type="text/javascript" src="./js/jquery/jquery-ui.js"></script>
                                <script type="text/javascript" src="./js/bootstrap/bootstrap.js"></script>                        
                                <script type="text/javascript" src="./js/bootstrap/bootstrap.min.js"></script>
                                <script type="text/javascript" src="./js/bootstrap/bootbox.js"></script>
                                <script type="text/javascript" src="./js/bootstrap/bootbox.min.js"></script>
                                <script type="text/javascript" src="./js/main/menu.js"></script>
                                <script type="text/javascript" src="./js/main/index.js"></script>
                                <script type="text/javascript" src="./js/main/login.js"></script>
                                <script type="text/javascript" src="./js/colonias/colonias.js"></script>
                                <script type="text/javascript" src="./js/entidades/entidades.js"></script>
                                <script type="text/javascript" src="./js/puestos/puestos.js"></script>
                                <script type="text/javascript" src="./js/usuarios/usuarios.js"></script>
                                <script type="text/javascript" src="./js/clientes/clientes.js"></script>
                                <script type="text/javascript" src="./js/empleados/empleados.js"></script>
                                <script type="text/javascript" src="./js/utilidades/graficas.js"></script>
                                <script type="text/javascript" src="./js/instrumentos/cedulas/cedulas.js"></script>
                                <script type="text/javascript" src="./js/instrumentos/factores/factores.js"></script>
                                <script type="text/javascript" src="./js/instrumentos/escalas/escalas.js"></script>
                                <script type="text/javascript" src="./js/instrumentos/evaluaciones/evaluaciones.js"></script>
                                <script type="text/javascript" src="./js/solicitudes/solicitudes.js"></script>';
                    return $head;
                    }
                                        
            private function HTMLBody()
                {
                    /*
                     * Esta funcion contiene la informacion a incluir en el cuerpo del html.
                     */
                    $body = '   <div id= "Contenedor" class= "contenedor">'
                                    .$this->drawMenu().
                                    '<div id="escritorio" class="contenedor-principal">
                                        <div class="area-deslizar"></div>
                                            <a href="#" data-toggle=".contenedor" id="menu-lateral-toggle">
                                                <img src="./img/menu/menu.png" id="menu-icono" alt="Menu" height="32" width="32" title="Menu">
                                            </a>
                                        </div>
                                    </div>
                                    <section class="contenedor-seccion">
                                    </section>';
                    return $body;
                    }

            public function drawUI()
                {
                    /*
                     * Esta funcion dibuja los elementos en pantalla que corresponden a la interfaz.
                     */
                    $HTML = '   <html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
                                    <head>'
                                        .$this->HTMLHead().
                                    '</head>
                                    <body>'
                                        .$this->HTMLBody().
                                        '<script type="text/javascript">
                                            document.oncontextmenu = function(){return true;}
                                            jQuery(document).ready(function ()
                                                {
                                                    if (!jQuery.browser.webkit)
                                                        {
                                                            jQuery(\'.contenedor\').jScrollPane();
                                                            }
                                                    });
                                        </script>
                                    </body>
                                </html>';
                    return $HTML;
                    }
                    
            //DEFINICION DE FUNCIONES AUXILIARES DE LA INTERFAZ.
            public function setProfile()
                {
                    /*
                     * Esta funcion retorna los datos del perfil del usuario para alimentar
                     * la interfaz, solo con fines visuales.
                     */
                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('citadel');
                            session_start();
                            }
                    
                    if(isset($_SESSION['usuario']))
                        {
                            //Si se ha inicializado previamente una sesion.
                            $DIV = '<table class="profileUser">
                                        <tr><td>Bienvenido </td><td>'.$_SESSION['usuario'].'</td></tr>
                                    </table>';
                            }
                    else
                        {
                            //En caso contrario se carga un perfil vacio.
                            $DIV = '';
                            }
                    
                    return $DIV;
                    }                                                    
            }
?>