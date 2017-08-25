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
    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificacion a ISO-8859-1.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/subirArchivos.class.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php");
    
    function headHTML()
        {
            global $SitioWeb;
            
            $HEAD = '   <head>
                            <link rel="stylesheet" href="'.$SitioWeb.'css/uploadfile.css"></style>
                            <script type="text/javascript" src="'.$SitioWeb.'js/jquery/jquery-1.9.1.js"></script>
                            <script type="text/javascript" src="'.$SitioWeb.'js/jquery/jquery.jscrollpane.min.js"></script>
                            <script type="text/javascript" src="'.$SitioWeb.'js/jquery/jquery-1.9.1.min.js"></script>
                            <script type="text/javascript" src="'.$SitioWeb.'js/main/uploadfile.js"></script>
                        </head>';
            return $HEAD;
            }
            
    function drawUI()
        {
            global $SitioWeb;
            
            if(isset($_GET["rutaadjuntos"]))
                {
                    //Si el usuario ha especificado la ruta disponible por medio
                    //de la navegacion en el sistema
                    $sa = new subirArchivos($_GET["rutaadjuntos"]);
                    }
            else
                {
                    //En caso que el usuario no establesca la referencia de la ruta
                    //por medio de la navegacion.
                    $sa = new subirArchivos("");
                    }
                    
            if(isset($_GET["visualizar"]))
                {
                    //Si se ha establecido el valor de la variable de visualizacion.
                    if($_GET["visualizar"] == '1')
                        {
                            //Si el usuario gestiona la apertura desde una operacion de visualizacion.
                            $btnAdjuntar = '';
                            }
                    else
                        {
                            //Si el usuario gestiona la apertura desde una operacion de edicion/creacion.
                            $btnAdjuntar = '<input id="enviarSolicitud" name="enviarSolicitud" type="submit" value="Subir Archivo"/>';
                            }                            
                    }
            else{
                    //En caso contrario solo se deja el modo por default (sin carga de archivos)
                    $btnAdjuntar = '';
                    }
                            
            $HTMLBODY = '
                            <body>
                                    <div id="Contenedor-Archivos">
                                        <form enctype="multipart/form-data" action="'.$SitioWeb.'php/backend/bl/utilidades/subirArchivos.class.php?rutaadjuntos='.$_GET["rutaadjuntos"].'" name="formulario" method="post">
                                            <input type="file" name="archivo[]" id="archivo[]" multiple="multiple">'
                                            .$sa->genList().
                                            '
                                            <div id="Contenedor-Acciones">
                                                <input id="cerrarVentana" name="cerrarVentana" type="button" value="Cerrar Ventana"/>'
                                            .$btnAdjuntar.
                                            '</div>
                                        </form>
                                    </div>                                    
                            </body>';
            
            return $HTMLBODY;
            }

    echo "<HTML>".headHTML().drawUI()."</HTML>";    
?>