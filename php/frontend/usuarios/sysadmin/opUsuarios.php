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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/codificador.class.php"); //Se carga la referencia de la clase para manejo de encriptado.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/usuarios/usuarios.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opUsuarios
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de usuarios.
             */
            private $cntView = 0;
            private $idUsuario = 0;
            private $ClaveCod = '';
            private $imgTitleURL = './img/menu/usuarios.png';
            private $Title = 'Usuarios';
                        
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idUsuario = $_GET['id'];}
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
                     * Esta funcion retorna el valor obtenido del ID de Usuario.
                     */
                    return $this->idUsuario;
                    }

            public function getNiveles($habCampos, $Registro)
                {
                    /*
                     * Esta funcion retorna el codigo HTML correspondiente al combobox del
                     * listado de niveles.
                     */
                    $HTML = '<select name= "idNivel" id= "idNivel" '.$habCampos.' value= "'.$Registro['idNivel'].'"><option value=-1>Seleccione</option>';
                    
                    $objUsuarios = new usuarios();
                    $dsNiveles = $objUsuarios->getNiveles();
                    
                    $regNiveles = @mysqli_fetch_array($dsNiveles,MYSQLI_ASSOC);
                    
                    while($regNiveles)
                        {
                            if($regNiveles['idNivel'] == $Registro['idNivel'])
                                {
                                    //En caso que el valor almacenado coincida con uno de los existentes en lista.
                                    $HTML .= '<option value='.$regNiveles['idNivel'].' selected="selected">'.$regNiveles['Nivel'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se carga la etiqueta por default.
                                    $HTML .= '<option value='.$regNiveles['idNivel'].'>'.$regNiveles['Nivel'].'</option>';
                                    }
                                    
                            $regNiveles = @mysqli_fetch_array($dsNiveles,MYSQLI_ASSOC);
                            }
                            
                    return $HTML.'</select>';                                                    
                    }
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */                    
                    
                    $objUsuarios = new usuarios();                    
                    
                    $objUsuarios->getNiveles();
                    $RegUsuario = $objUsuarios->getRegistro($this->getID());
                    
                    $objCodificador = new codificador();
                    
                    $this->ClaveCod = $objCodificador->decrypt($RegUsuario['Clave'],"ouroboros");
                    
                    if(!empty($RegUsuario['idUsuario']))
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
                                            <div id="statsUser" style="display:none">
                                                <table>
                                                    <tr><td>idUsuario: </td><td><input id="idUsuario" type="text" value="'.$RegUsuario['idUsuario'].'"></td></tr>
                                                    <tr><td>Status: </td><td><input id="Status" type="text" value="'.$RegUsuario['Status'].'"></td></tr>
                                                </table>
                                            </div>
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                    .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>
                                                        <tr><td class="td-panel" width="100px">Usuario:</td><td><input type= "text" id= "Usuario" required= "required" '.$habCampos.' value= "'.$RegUsuario['Usuario'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Clave:</td><td><input type= "text" required= "required" id= "Clave" '.$habCampos.' value= "'.$this->ClaveCod.'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Correo:</td><td><input type= "text" required= "required" id= "Correo" '.$habCampos.' value= "'.$RegUsuario['Correo'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Nivel:</td><td>'.$this->getNiveles($habCampos, $RegUsuario).'</td></tr>
                                                        <tr><td class="td-panel" width="100px">Pregunta:</td><td>'.$objUsuarios->cargarPreguntas($habCampos, $RegUsuario['Pregunta']).'</td></tr>
                                                        <tr><td class="td-panel" width="100px">Respuesta: </td><td><input id= "Respuesta" type= "text" '.$habCampos.' value= "'.$RegUsuario['Respuesta'].'"></td></tr>
                                                        <tr><td class="td-panel" width="100px">Permisos: </td><td class="td-panel"><div id="lstPermisos" style="height:100px; overflow:scroll;">'.$objUsuarios->listaModulos($habCampos, $RegUsuario['idUsuario']).'</div></td></tr>
                                                    </table>                                   
                                                </div>                                                    
                                                <div id="pie" class="pie-operativo">'.$objUsuarios->controlBotones("32", "32", $this->getView()).'</div>                                                                                                                                                                                   
                                            </div>
                                        </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpUsuarios = new opUsuarios();
    

    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                            $objOpUsuarios->drawUI().
                        '</center>
                    </body>
                </html>';
                    
    echo $HTML;        
?>