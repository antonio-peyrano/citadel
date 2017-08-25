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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    
    class opEvaluaciones
        {
            private $idUsuario = NULL;
            private $idEvaluacion = NULL;
            private $cuerpoEva = '';
            private $habCampos = '';
            
            function __construct()
                {
                    /*
                     * Esta funcion constructor asigna el identificador de usuario
                     * para generar los controles de interfaz de evaluacion.
                     */
                    if(isset($_GET['idusuario'])){$this->idUsuario = $_GET['idusuario'];}
                    if(isset($_GET['idevaluacion'])){$this->idEvaluacion = $_GET['idevaluacion'];}                            
                    }

            function contestados($idEvaluacion)
                {
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $consulta = "SELECT *FROM opResParEva WHERE idEvaluacion=".$idEvaluacion." AND Status=0";
                    $dataSet = $objConexion->conectar($consulta);
                    
                    if($dataSet)
                        {
                            return mysqli_num_rows($dataSet);
                            }
                    else
                        {
                            return 0;
                            }
                    }
                                        
            function checksave($idEscala, $idFactor, $idEvaluacion)
                {
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $consulta = "SELECT *FROM opResParEva WHERE idEscala=".$idEscala." AND idFactor=".$idFactor." AND idEvaluacion=".$idEvaluacion." AND Status=0";
                    $dataSet = $objConexion->conectar($consulta);
                    
                    if(mysqli_num_rows($dataSet) == 0)
                        {
                            return "";
                            }
                    else
                        {
                            return "checked";
                            }                    
                    }

            function checkResultado($idEscala, $idFactor, $idEvaluacion)
                {
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $consulta = "SELECT *FROM (opResParEva INNER JOIN opEscalas ON opEscalas.idEscala = opResParEva.idEscala) WHERE opResParEva.idEscala=".$idEscala." AND opResParEva.idFactor=".$idFactor." AND opResParEva.idEvaluacion=".$idEvaluacion." AND opResParEva.Status=0";
                    $dataSet = $objConexion->conectar($consulta);
                    $registro = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC);
                    
                    if($registro)
                        {
                            return $registro['Ponderacion'];
                            }
                    else
                        {
                            return "NP";
                            }
                    }
                                        
            function contarColumnas($idUsuario)
                {
                    global $username, $password, $servername, $dbname;
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $dataSetEscalas = $objConexion->conectar("SELECT opCedulas.Folio, idEscala, Escala FROM ((opEvaluaciones INNER JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) INNER JOIN opEscalas ON opEscalas.idCedula = opCedulas.idCedula) WHERE 1=1 AND idUsuario=".$idUsuario);
                    $conteo =  mysqli_num_rows($dataSetEscalas);
                    return $conteo;
                    }
                            
            function constructEvaluacion($idUsuario, $idEvaluacion)
                {
                    global $username, $password, $servername, $dbname;
                    
                    $conteo = 0;
                    $conteoFactor = 0;
                    
                    /*
                     * Se ejecuta la conexion con la base de datos y posterior recoleccion
                     * de los items asociados al empleado.
                     */
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $dataSetFactores = $objConexion->conectar("SELECT opCedulas.Folio, idFactor, Factor, Descripcion FROM ((opEvaluaciones INNER JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) INNER JOIN opFactores ON opFactores.idCedula = opCedulas.idCedula) WHERE 1=1 AND idUsuario=".$idUsuario.' AND idEvaluacion='.$idEvaluacion);
                    $rowSetFactores = @mysqli_fetch_array($dataSetFactores,MYSQLI_ASSOC);                    
                                        
                    $this->cuerpoEva = '    <table class="dgTable">
                                                <tr><th class= "dgHeader" colspan='.$this->contarColumnas($this->idUsuario).'>'.$rowSetFactores['Descripcion'].'</th></tr>';
                    
                    while($rowSetFactores)
                        {
                            $conteoFactor+=1;
                            $this->cuerpoEva.='<tr class="dgRowsnormTR"><td style="display:none"><input type="text" class="txtfactor" id="idFactor_'.$conteoFactor.'" value="'.$rowSetFactores['idFactor'].'"></td><td colspan='.$this->contarColumnas($this->idUsuario).'>'.$rowSetFactores['Factor'].'</td></tr>';
                            $this->cuerpoEva.='<tr class="dgRowsaltTR">';
                            
                            $dataSetEscalas = $objConexion->conectar("SELECT opCedulas.Folio, idEscala, Escala, Ponderacion FROM ((opEvaluaciones INNER JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) INNER JOIN opEscalas ON opEscalas.idCedula = opCedulas.idCedula) WHERE 1=1 AND idUsuario=".$idUsuario.' AND idEvaluacion='.$idEvaluacion);
                            $rowSetEscalas = @mysqli_fetch_array($dataSetEscalas,MYSQLI_ASSOC);
                            
                            $conteo+=1;
                            $tmpidEscala=0;
                            
                            while($rowSetEscalas)
                                {
                                    $state = $this->checksave($rowSetEscalas['idEscala'], $rowSetFactores['idFactor'], $this->idEvaluacion);
                                    if($state == "checked")
                                        {
                                            $tmpidEscala = $rowSetEscalas['idEscala'];
                                            }
                                    $this->cuerpoEva.='<td><input type="text" style="display:none" class="txtescala" id="idEscala_'.$conteo.'" value="'.$rowSetEscalas['idEscala'].'"><input type="radio" class="radio" id="Escala_'.$conteo.'" name="Escala_'.$conteo.'" value="'.$rowSetEscalas['Ponderacion'].'"'.$state.$this->habCampos.'>'.$rowSetEscalas['Escala'].'</td>';
                                    $rowSetEscalas = @mysqli_fetch_array($dataSetEscalas,MYSQLI_ASSOC);
                                    }
                                    
                            $this->cuerpoEva.='<td style="display:none"><input type="text" id="Res_'.$conteoFactor.'" value="'.$this->checkResultado($tmpidEscala, $rowSetFactores['idFactor'], $this->idEvaluacion).'"></td></tr>';
                            $rowSetFactores = @mysqli_fetch_array($dataSetFactores,MYSQLI_ASSOC);
                            }    
                    if($this->habCampos == ' disabled')
                        {
                            $controles = '<img id="eva_Volver" title="Regresar" align= "middle" src= "./img/grids/volver.png" width= "35" height= "35" alt= "Volver"/>';                            
                            }
                    else
                        {
                            $controles = '<img id="guardarEncuesta" title="Guardar encuesta parcial" align= "middle" src= "./img/operaciones/guardar_encuesta.png" width= "35" height= "35" alt= "Guardar"/><img id="enviarEncuesta" title="Enviar encuesta terminada" align= "middle" src= "./img/operaciones/enviar_encuesta.png" width= "35" height= "35" alt= "Enviar"/>';
                            }                                                                        
                    echo $this->cuerpoEva.='<tr align="center"><td class="dgTotRowsTR" colspan='.$this->contarColumnas($this->idUsuario).'>'.$controles.'</td></tr></table>';                            
                    }
                                        
            function head()
                {
                    echo '  <head>
                                <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                            </head>';         
                    }
                                        
            function body()
                {
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $dataSet = $objConexion->conectar("SELECT *, opEvaluaciones.Status FROM ((opEvaluaciones INNER JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) INNER JOIN opFactores ON opFactores.idCedula = opCedulas.idCedula) WHERE 1=1 AND opEvaluaciones.idUsuario=".$this->idUsuario);
                    $rowSet = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC);
                    $conteo = mysqli_num_rows($dataSet);
                    
                    if($rowSet['Status'] == 3)
                        {
                            $this->habCampos = ' disabled';
                            }
                            
                    echo '  <body>
                                <div id="control" style="display:none">
                                    <table>
                                        <tr><td>id Evaluacion: </td><td><input id="idEvaForm" type="text" value="'.$this->idEvaluacion.'"></td></tr>
                                        <tr><td>id Usuario: </td><td><input id="idUsuario" type="text" value="'.$this->idUsuario.'"></td></tr>
                                        <tr><td>Reactivos: </td><td><input id="Reactivos" type="text" value="'.$conteo.'"></td></tr>
                                        <tr><td>Contestados: </td><td><input id="Contestados" type="text" value="'.$this->contestados($this->idEvaluacion).'"></td></tr>
                                        <tr><td>Status: </td><td><input id="Status" type="text" value="'.$rowSet['Status'].'"></td></tr>                                            
                                    </table>
                                </div>
                                <div id="workbench">';
                                
                                $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                                $dataSet = $objConexion->conectar("SELECT *FROM opEvaluaciones WHERE idUsuario=".$this->idUsuario.' AND idEvaluacion='.$this->idEvaluacion);
                                $rowSet = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC);
                                        
                                $this->constructEvaluacion($this->idUsuario, $this->idEvaluacion);                                
                    
                    echo '      </div>
                            </body>';
                    }
            }
    
    $objopEvaluaciones = new opEvaluaciones();
    
    echo '  <html>';
                $objopEvaluaciones->head();
                $objopEvaluaciones->body();
    echo '  </html>';
                
?>