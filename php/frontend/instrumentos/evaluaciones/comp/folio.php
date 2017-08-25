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
include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/instrumentos/evaluaciones/evaluaciones.class.php"); //Se carga la referencia de la clase para manejo de la entidad usuarios.
include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/main/usrctrl.class.php"); //Se carga la referencia de clase para control de accesos.

    function calcularFolio()
        {
            /*
             * Esta funcion establece el calculo de la clave de la evaluacion a razon
             * de los elementos existentes.
             */
            global $username, $password, $servername, $dbname;
            $Clave = "";
            $objAux = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.

            $consulta = "SELECT *FROM opEvaluaciones";
            $dataset = $objAux -> conectar($consulta);
            $RowCount = mysqli_num_rows($dataset);

            $now = time(); //Se obtiene la referencia del tiempo actual del servidor.
            date_default_timezone_set("America/Mexico_City"); //Se establece el perfil del uso horario.
            $fecha = date("Y/m/d H:i:s",$now); //Se obtiene la referencia compuesta de fecha.

            $parseFecha = explode("/",$fecha);
            $parseFecha = implode("",$parseFecha);

            $parseFecha = explode(":",$parseFecha);
            $parseFecha = implode("",$parseFecha);

            $parseFecha = explode(" ",$parseFecha);
            $parseFecha = implode("",$parseFecha);

            if($Clave == "")
                {
                    //Si se trata de un nuevo registro, se genera una clave artificial nueva.
                    $Clave = 'EVA'.'-'.$parseFecha."-".($RowCount + 1);
                    }

            return $Clave;
            }

    echo '<input type="text" id="Folio" value="'.calcularFolio().'"/>';
?>