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
    
    class puestos
        {
            /*
             * Esta clase contiene los atributos y procedimientos vinculados con el comportamiento
             * y funcionalidades de la interfaz del modulo de puestos.
             */
            
            //ATRIBUTOS APLICABLES AL MODULO catPuestos.php
            private $Condicionales = "";
            private $Sufijo = "pst_";
            private $Puesto = "";
            private $idEntidad = NULL;            
            private $Consulta = "SELECT idRelEntPst, Puesto, Entidad, opRelEntPst.Status FROM (opRelEntPst INNER JOIN catPuestos ON catPuestos.idPuesto = opRelEntPst.idPuesto) INNER JOIN catEntidades ON catEntidades.idEntidad = opRelEntPst.idEntidad WHERE opRelEntPst.Status=0";
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO catPuestos.php
            
            //ATRIBUTOS APLICABLES AL MODULO opPuestos.php
            private $idPuesto = NULL;
            private $cntView = 0;
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO opPuestos.php
            
            public function __construct()
                {
                    //Declaracion de constructor (VACIO)
                    }

            //PROCEDIMIENTOS APLICABLES AL MODULO catPuestos.php
            public function getPuesto()
                {
                    /*
                     * Esta funcion retorna el valor del nombre puesto.
                     */
                    return $this->Puesto;
                    }

            public function getidEntidad()
                {
                    /*
                     * Esta funcion retorna el valor de idEntidad.
                     */
                    return $this->idEntidad;
                    }

            public function getConsulta()
                {
                    /*
                     * Esta funcion retorna el valor de la cadena de consulta.
                     */
                    return $this->Consulta;
                    }

            public function getSufijo()
                {
                    /*
                     * Esta funcion retorna el valor de sufijo para la interfaz.
                     */
                    return $this->Sufijo;
                    }
                                
            public function setCatParametros($Puesto, $idEntidad)
                {
                    /*
                     * Esta funcion obtiene de la interaccion del usuario, los parametros
                     * para establecer los criterios de busqueda.
                     */
                    $this->Puesto = $Puesto;
                    $this->idEntidad = $idEntidad;
                    }  

            public function evaluaCondicion()
                {
                    /*
                     * Esta funcion evalua si la condicion de busqueda cumple con las caracteristica
                     * solicitadas por el usuario.
                     */
                    $this->Condicionales = "";
                    
                    if(!empty($this->getPuesto()))
                        {
                            $this->Condicionales = ' AND Puesto LIKE \'%'.$this->getPuesto().'%\'';
                            }
                            
                    if((!empty($this->getidEntidad()))&&($this->getidEntidad()!="-1"))
                        {

                            $this->Condicionales= $this->Condicionales.' AND opRelEntPst.idEntidad =\''.$this->getidEntidad().'\'';
                            }
                                                        
                    return $this->Condicionales;                            
                    }                    
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO catPuestos.php
            
            //PROCEDIMIENTOS APLICABLES AL MODULO opPuestos.php.
            public function getRegistro($idRegistro)
                {
                    /*
                     * Esta funcion obtiene el dataset del registro de colonia apartir del ID proporcionado.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.                    
                    $Consulta= 'SELECT *FROM (opRelEntPst INNER JOIN catPuestos ON catPuestos.idPuesto = opRelEntPst.idPuesto) INNER JOIN catEntidades ON catEntidades.idEntidad = opRelEntPst.idEntidad WHERE opRelEntPst.idRelEntPst='.$idRegistro; //Se establece el modelo de consulta de datos.
                    $dsPuesto = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    $RegPuesto = @mysqli_fetch_array($dsPuesto,MYSQLI_ASSOC);//Llamada a la funcion de carga de registro.
                    
                    return $RegPuesto;
                    }                    
                    
            public function controlBotones($Width, $Height, $cntView)
                {
                    /*
                     * Esta funcion controla los botones que deberan verse en la pantalla deacuerdo con la accion solicitada por el
                     * usuario en la ventana previa.
                     * Si es una edicion, los botones borrar y guardar deben verse.
                     * Si es una creacion solo el boton guardar debe visualizarse.
                     */                    
                    
                    $botonera = '';
                    $btnVolver_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/volver.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Volver" id="'.$this->Sufijo.'Volver" title= "Volver"/>';
                    $btnBorrar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/erase.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Borrar" id="'.$this->Sufijo.'Borrar" title= "Borrar"/>';
                    $btnGuardar_V =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$this->Sufijo.'Guardar" title= "Guardar"/>';
                    $btnGuardar_H =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$this->Sufijo.'Guardar" title= "Guardar" style="display:none;"/>';
                    $btnEditar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/edit.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Editar" id="'.$this->Sufijo.'Editar" title= "Editar"/>';

                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('citadel');
                            session_start();
                            }
                            
                    if(($cntView == 0)||($cntView == 2)||($cntView == 9))
                        {
                            //CASO: CREACION O EDICION DE REGISTRO.
                            if($_SESSION['nivel'] == "Lector")
                                {
                                    /*
                                     * Si el usuario cuenta con un perfil de lector, se crea la referencia
                                     * para el control de solo visualizacion.
                                     */
                                    $botonera .= $btnVolver_V;
                                    }
                            else
                                {
                                    if($_SESSION['nivel'] == "Administrador")
                                        {
                                            $botonera .= $btnGuardar_V.$btnVolver_V;
                                            }
                                    }
                            }
                    else
                        {
                            if($cntView == 1)
                                {
                                    //CASO: VISUALIZACION CON OPCION PARA EDICION O BORRADO.
                                    if($_SESSION['nivel'] == "Lector")
                                        {
                                            /*
                                             * Si el usuario cuenta con un perfil de lector, se crea la referencia
                                             * para el control de solo visualizacion.
                                             */
                                            $botonera .= $btnVolver_V;
                                            }
                                    else
                                        {
                                            if($_SESSION['nivel'] == "Administrador")
                                                {
                                                    $botonera .= $btnEditar_V.$btnBorrar_V.$btnGuardar_H.$btnVolver_V;
                                                    }
                                            }
                                    }
                            }
                            
                    return $botonera;
                    }                                

            public function cargarEntidades($idRegistro, $idPuesto, $habCampos)
                {
                    /*
                     * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
                     */
                    global $username, $password, $servername, $dbname, $habCampos;
                    
                    $HTML = '';
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta= 'SELECT *FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion->conectar($consulta); //Se ejecuta la consulta.
                        
                    $HTML .= '<tr><td class="td-panel" width="100px">Entidades:</td><td><div id="idEntidadChk" style="height:100px; overflow:scroll;">';
                        
                    if($idRegistro == -1)
                        {
                            /*
                             * Si la operacion solicitada es para la creacion de un registro,
                             * se carga el listado sin marcar.
                             */
                            $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                            
                            while ($RegNiveles)
                                {
                                    $HTML .= '<br><input type="checkbox" class="check" id="idEntidad[]" name="idEntidad[]" '.$habCampos.' value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'];
                                    $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                                    }
                            }
                    else
                        {
                            /*
                             * Si la operacion solicitada es para editar el registro,
                             * se carga el listado con los elementos previamente marcados.
                             */
                            $subconsulta = 'SELECT *FROM opRelEntPst WHERE idPuesto='.$idPuesto.' AND Status=0'; //Se establece el modelo de consulta de datos.
                            $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                            $vector = "";
                            $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                            
                            if($RegNiveles)
                                {
                                    /*
                                     * Si la lectura del registro no apunta a vacio, se agrega
                                     * el id al vector.
                                     */
                                    $vector.=$RegNiveles['idEntidad'];
                                    }
                            
                            $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                            
                            while ($RegNiveles)
                                {
                                    /*
                                     * Se hace un recorrido sobre el dataset para crear un vector que contenga
                                     * los id de las entidades seleccionadas por el usuario previamente.
                                     */
                                    $vector.=','.$RegNiveles['idEntidad'];
                                    $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                                    }
                            
                            $tmparray = explode(',',$vector); //El vector resultante se convierte en un arreglo.
                            
                            $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                            
                            while ($RegNiveles)
                                {
                                    /*
                                     * Mientras no se llegue al final de la coleccion, se procede a la lectura
                                     * y generacion del listado.
                                     */
                                    if(in_array($RegNiveles['idEntidad'], $tmparray,true))
                                        {
                                            /*
                                             * En caso de tratarse de una opcion previamente seleccionada por el usuario.
                                             */
                                            $HTML .= '<br><input type="checkbox" class="check" id="idEntidad[]" name="idEntidad[]" '.$habCampos.' value='.$RegNiveles['idEntidad'].' checked>'.$RegNiveles['Entidad'];
                                            }
                                    else
                                        {
                                            /*
                                             * En caso contrario se agrega una entrada de formato convencional.
                                             */
                                            $HTML .= '<br><input type="checkbox" class="check" id="idEntidad[]" name="idEntidad[]" '.$habCampos.' value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'];
                                            }
                                
                                    $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                                    }
                            }
                        
                    $HTML .= '</div></td></tr>';
                    
                    return $HTML;
                    }
                    
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO opPuestos.php            
            }
?>