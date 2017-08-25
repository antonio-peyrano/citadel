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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/instrumentos/factores/factores.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opFactores
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de factores.
             */
            private $cntView = 0;
            private $idFactor = 0;
            private $imgTitleURL = './img/menu/factores.png';
            private $Title = 'Factores';
                        
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idFactor = $_GET['id'];}
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
                     * Esta funcion retorna el valor obtenido del ID de Factor.
                     */
                    return $this->idFactor;
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
                    $RegFactors = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                                           
                    while($RegFactors)
                        {
                            if($Registro['idCedula'] == $RegFactors['idCedula'])
                                {
                                    //Si el item fue previamente marcado, se selecciona en el codigo.
                                    $HTML .= '<option value='.$RegFactors['idCedula'].' selected>'.$RegFactors['Folio'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se escribe la secuencia base de codigo.
                                    $HTML .= '<option value='.$RegFactors['idCedula'].'>'.$RegFactors['Folio'].'</option>';
                                    }                            
                            $RegFactors = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                    
                    $HTML .= '</select></td></tr>';
                    return $HTML;
                    }

            public function drawCBTipoFactor($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * tipo de factor.
                     */
                        $HTML = '<tr><td class="td-panel" width="100px">Tipo: </td><td><select name= "Tipo" id= "Tipo" value= "-1"'.$habilitador.'>
                                <option value=-1>Seleccione</option>';
                                             
                        if($Registro['Tipo'] == '1')
                            {
                                $HTML .= '<option value=1 selected>Interno</option>';
                                $HTML .= '<option value=2>Externo</option>';
                                }
                        else
                            {
                                $HTML .= '<option value=1>Interno</option>';
                                $HTML .= '<option value=2 selected>Externo</option>';                                
                                }                                
                    
                        $HTML .= '</select></td></tr>';
                        return $HTML;
                    }
                                        
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */                    
                    
                    $objFactores = new factores();                    
                    
                    $RegFactor = $objFactores->getRegistro($this->getID());
                                                                                                   
                    if(!empty($RegFactor['idCedula']))
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
                                                    <tr><td>idFactor: </td><td><input id="idFactor" type="text" value="'.$RegFactor['idFactor'].'"></td></tr>
                                                    <tr><td>Status: </td><td><input id="Status" type="text" value="'.$RegFactor['Status'].'"></td></tr>
                                                </table>
                                            </div>
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                    .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>
                                                        <tr><td class="td-panel" width="100px">Factor:</td><td><input type= "text" id= "Factor" required= "required" '.$habCampos.' value= "'.$RegFactor['Factor'].'"></td></tr>'
                                                        .$this->drawCBTipoFactor($RegFactor, $habCampos)
                                                        .$this->drawCBCedula($RegFactor, $habCampos).
                                                    '</table>                                   
                                                </div>                                                    
                                                <div id="pie" class="pie-operativo">'.$objFactores->controlBotones("32", "32", $this->getView()).'</div>                                                                                                                                                                                   
                                            </div>
                                        </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpFactores = new opFactores();
    

    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                            $objOpFactores->drawUI().
                        '</center>
                    </body>
                </html>';
                    
    echo $HTML;        
?>