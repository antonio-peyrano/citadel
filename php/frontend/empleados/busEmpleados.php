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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/empleados/empleados.class.php"); //Se carga la referencia de la clase para manejo de la entidad usuarios.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/main/usrctrl.class.php"); //Se carga la referencia de clase para control de accesos.
    
    class busEmpleados
        {
            private $Sufijo = "emp_";
            
            public function __construct()
                {
                    //Declaracion de constructor de clase (VACIO)
                    }
                    
            public function drawUI()
                {
                    $HTML = '
                            <div id="paginado" style="display:none">
                                <input id="pagina" type="text" value="1">
                                <input id="pgpaterno" type="text" value="">
                                <input id="pgmaterno" type="text" value="">
                                <input id="pgnombre" type="text" value="">
                                <input id="pgcurp" type="text" value="">
                                <input id="pgrfc" type="text" value="">
                            </div> 
                            <div id= "divBusqueda">
                                <table class="queryTable" colspan= "7">
                                    <tr><td class= "queryRowsnormTR" width ="180">Por apellido paterno: </td><td class= "queryRowsnormTR" width= "250"><input type= "text" id= "buspaterno"></td><td rowspan= "5"><img id="'.$this->Sufijo.'buscar" align= "left" src= "./img/grids/view.png" width= "25" height= "25" alt="Buscar"/></td></tr>
                                    <tr><td class= "queryRowsnormTR">Por apellido materno: </td><td class= "queryRowsnormTR"><input type= "text" id= "busmaterno"></td><td></td></tr>
                                    <tr><td class= "queryRowsnormTR">Por nombre del empleado: </td><td class= "queryRowsnormTR"><input type= "text" id= "busnombre"></td><td></td></tr>
                                    <tr><td class= "queryRowsnormTR">Por CURP: </td><td class= "queryRowsnormTR"><input type= "text" id= "buscurp"></td><td></td></tr>
                                    <tr><td class= "queryRowsnormTR">Por RFC: </td><td class= "queryRowsnormTR"><input type= "text" id= "busrfc"></td><td></td></tr>
                                </table>
                            </div>';
                    
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
            $Modulo = 'Empleados';
            
            if($objUsrCtrl->validarCredenciales($idUsuario, $Modulo)!='')
                {
                    /*
                     * Se valida que las credenciales autoricen la ejecucion del
                     * modulo solicitado.
                     */
                    $objBusEmpleados = new busEmpleados();

                    echo '  <html>
                                <center>'.$objBusEmpleados->drawUI().'</center><br>';

                    echo '      <div id= "busRes">';
                                    include_once("catEmpleados.php");
                    echo '      </div>
                            </html>';
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