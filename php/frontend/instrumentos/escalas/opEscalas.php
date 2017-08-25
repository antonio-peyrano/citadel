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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/instrumentos/escalas/escalas.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opEscalas
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de escalas.
             */
            private $cntView = 0;
            private $idEscala = 0;
            private $imgTitleURL = './img/menu/escalas.png';
            private $Title = 'Escalas';
                        
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idEscala = $_GET['id'];}
                    }

            public function getView()
                {
                    /*
                     * Esta funcion retorna el valor obtenido del modo de visualizacion
                     */
                    return $this->cntView;
                    }

            public function getID()
                {
                    /*
                     * Esta funcion retorna el valor obtenido del ID de Escala.
                     */
                    return $this->idEscala;
                    }

            public function cargarCedulas()
                {
                    /*
                     * Esta funcion establece la carga del conjunto de registros de cedulas.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idCedula, Folio FROM opCedulas WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }
                                                                                
            public function drawCBCedula($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * cedula.
                     */
                    $HTML = '<tr><td class="td-panel" width="100px">Cedula: </td><td><select name= "idCedula" id= "idCedula" value= "-1"'.$habilitador.'>
                                <option value=-1>Seleccione</option>';
                    
                    $subconsulta = $this->cargarCedulas();
                    $RegEscalas = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                                           
                    while($RegEscalas)
                        {
                            if($Registro['idCedula'] == $RegEscalas['idCedula'])
                                {
                                    //Si el item fue previamente marcado, se selecciona en el codigo.
                                    $HTML .= '<option value='.$RegEscalas['idCedula'].' selected>'.$RegEscalas['Folio'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se escribe la secuencia base de codigo.
                                    $HTML .= '<option value='.$RegEscalas['idCedula'].'>'.$RegEscalas['Folio'].'</option>';
                                    }                            
                            $RegEscalas = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                    
                    $HTML .= '</select></td></tr>';
                    return $HTML;
                    }
                                        
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */                    
                    
                    $objEscalas = new escalas();                    
                    
                    $RegEscala = $objEscalas->getRegistro($this->getID());
                                                                                                   
                    if(!empty($RegEscala['idCedula']))
                        {
                            //CASO: VISUALIZACION DE REGISTRO PARA SU EDICION O BORRADO.
                            if($this->getView() == 1)
                                {
                                    //VISUALIZAR.
                                    $habCampos = 'disabled= "disabled"';
                                    }
                            else
                                {
                                    //EDICION.
                                    $habCampos = '';
                                    }                                                                
                            }
                    else
                        {
                            //CASO: CREACION DE NUEVO REGISTRO.
                            $habCampos = '';
                            }                                               
                            
                    $HTMLBody = '      <div id="cntOperativo">
                                            <div id="statsCliente" style="display:none">
                                                <table>
                                                    <tr><td>idEscala: </td><td><input id="idEscala" type="text" value="'.$RegEscala['idEscala'].'"></td></tr>
                                                    <tr><td>Status: </td><td><input id="Status" type="text" value="'.$RegEscala['Status'].'"></td></tr>
                                                </table>
                                            </div>
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                    .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>
                                                        <tr><td class="td-panel" width="100px">Escala:</td><td><input type= "text" id= "Escala" required= "required" '.$habCampos.' value= "'.$RegEscala['Escala'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Ponderacion:</td><td><input type= "text" id= "Ponderacion" required= "required" '.$habCampos.' value= "'.$RegEscala['Ponderacion'].'"></td></tr>'
                                                        .$this->drawCBCedula($RegEscala, $habCampos).
                                                    '</table>                                   
                                                </div>                                                    
                                                <div id="pie" class="pie-operativo">'.$objEscalas->controlBotones("32", "32", $this->getView()).'</div>                                                                                                                                                                                   
                                            </div>
                                        </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpEscalas = new opEscalas();
    

    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                            $objOpEscalas->drawUI().
                        '</center>
                    </body>
                </html>';
                    
    echo $HTML;        
?>