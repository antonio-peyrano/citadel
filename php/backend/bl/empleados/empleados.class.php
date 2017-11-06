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
    
    class empleados
        {
            /*
             * Esta clase contiene los atributos y procedimientos vinculados con el comportamiento
             * y funcionalidades de la interfaz del modulo de empleados.
             */
            
            //ATRIBUTOS APLICABLES AL MODULO catEmpleados.php
            private $Condicionales = "";
            private $Sufijo = "emp_";
            private $idColonia = 0;
            private $idEntidad = 0;
            private $Paterno = "";
            private $Materno = "";
            private $Nombre = "";
            private $fNacimiento = "";
            private $calle = "";
            private $nInt = "";
            private $nExt = "";
            private $rfc = "";
            private $curp = "";            
            private $telFijo = "";
            private $telCel = "";            
            private $Consulta = "SELECT idEmpleado, rfc AS RFC, curp as CURP, CONCAT (Nombre,' ' , Paterno,' ', Materno) AS Empleado, fNacimiento AS Nacimiento, Status FROM catEmpleados WHERE Status=0";
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO catEmpleados.php
            
            //ATRIBUTOS APLICABLES AL MODULO opEmplados.php
            private $idEmpleado = 0;
            private $cntView = 0;
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO opEmplados.php
            
            public function __construct()
                {
                    //Declaracion de constructor (VACIO)
                    }

            //PROCEDIMIENTOS APLICABLES AL MODULO catEmpleados.php
            public function getPaterno()
                {
                    /*
                     * Esta funcion retorna el valor de apellido paterno.
                     */
                    return $this->Paterno;
                    }

            public function getMaterno()
                {
                    /*
                     * Esta funcion retorna el valor de apellido materno.
                     */
                    return $this->Materno;
                    }

            public function getNombre()
                {
                    /*
                     * Esta funcion retorna el valor de Nombre.
                     */
                    return $this->Nombre;
                    }
                                        
            public function getCURP()
                {
                    /*
                     * Esta funcion retorna el valor de CURP del empleado.
                     */
                    return $this->curp;
                    }

            public function getRFC()
                {
                    /*
                     * Esta funcion retorna el valor de RFC del empleado.
                     */
                    return $this->rfc;
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
                                
            public function setCatParametros($Paterno, $Materno, $Nombre, $curp, $rfc)
                {
                    /*
                     * Esta funcion obtiene de la interaccion del usuario, los parametros
                     * para establecer los criterios de busqueda.
                     */
                    $this->Paterno = $Paterno;
                    $this->Materno = $Materno;
                    $this->Nombre = $Nombre;
                    $this->curp = $curp;
                    $this->rfc = $rfc;
                    }  

            public function evaluaCondicion()
                {
                    /*
                     * Esta funcion evalua si la condicion de busqueda cumple con las caracteristica
                     * solicitadas por el usuario.
                     */
                    $this->Condicionales = "";
                    
                    if(!empty($this->getPaterno()))
                        {
                            $this->Condicionales = ' AND Paterno LIKE \'%'.$this->getPaterno().'%\'';
                            }
                            
                    if(!empty($this->getMaterno()))
                        {                       
                            $this->Condicionales= $this->Condicionales.' AND Materno LIKE \'%'.$this->getMaterno().'%\'';
                            }

                    if(!empty($this->getNombre()))
                        {
                            $this->Condicionales= $this->Condicionales.' AND Nombre LIKE \'%'.$this->getNombre().'%\'';
                            }

                    if(!empty($this->getCURP()))
                        {
                            $this->Condicionales= $this->Condicionales.' AND curp LIKE \'%'.$this->getCURP().'%\'';
                            }

                    if(!empty($this->getRFC()))
                        {
                            $this->Condicionales= $this->Condicionales.' AND rfc LIKE \'%'.$this->getRFC().'%\'';
                            }
                    return $this->Condicionales;                            
                    }                    
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO catEmpleados.php
            
            //PROCEDIMIENTOS APLICABLES AL MODULO opEmplados.php.

            public function cargarEntidades()
                {
                    /*
                     * Esta funcion establece la carga del conjunto de registros de entidades.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }

            public function cargarPuestos($idEntidad)
                {
                    /*
                     * Esta funcion establece la carga del conjunto de registros de puestos
                     * asociados a la entidad proporcionada.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT catPuestos.idPuesto, Puesto FROM (catPuestos INNER JOIN opRelEntPst ON opRelEntPst.idPuesto = catPuestos.idPuesto) WHERE catPuestos.Status=0 AND opRelEntPst.idEntidad='.$idEntidad; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }
                    
            public function drawCBEntidad($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * entidad.
                     */
                    $HTML = '<tr><td class="td-panel" width="100px">Entidad: <select class="inputform" name= "idEntidad" id= "idEntidad" value= "-1"'.$habilitador.'>
                                <option value=-1>Seleccione</option>';
                        
                    $subconsulta = $this->cargarEntidades();
                    $RegEntidades = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        
                    while($RegEntidades)
                        {
                            if($Registro['idEntidad'] == $RegEntidades['idEntidad'])
                                {
                                    //Si el item fue previamente marcado, se selecciona en el codigo.
                                    $HTML .= '<option value='.$RegEntidades['idEntidad'].' selected>'.$RegEntidades['Entidad'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se escribe la secuencia base de codigo.
                                    $HTML .= '<option value='.$RegEntidades['idEntidad'].'>'.$RegEntidades['Entidad'].'</option>';
                                    }
                                    
                            $RegEntidades = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                        
                    $HTML .= '</select></td>';
                    return $HTML;
                    }

            public function drawCBPuesto($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * puestos.
                     */
                    $HTML = '<select class="inputform" name= "idPuesto" id= "idPuesto" value= "-1"'.$habilitador.'>
                                <option value=-1>Seleccione</option>';
                        
                    $subconsulta = $this->cargarPuestos($Registro['idEntidad']);
                    $RegPuestos = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                        
                    while($RegPuestos)
                        {
                            if($Registro['idPuesto'] == $RegPuestos['idPuesto'])
                                {
                                    //Si el item fue previamente marcado, se selecciona en el codigo.
                                    $HTML .= '<option value='.$RegPuestos['idPuesto'].' selected>'.$RegPuestos['Puesto'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se escribe la secuencia base de codigo.
                                    $HTML .= '<option value='.$RegPuestos['idPuesto'].'>'.$RegPuestos['Puesto'].'</option>';
                                    }
                            
                            $RegPuestos = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                        
                    $HTML .= '</select>';
                    return $HTML;
                    }

            public function drawCBRAC($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * puestos.
                     */
                    $HTML = '<tr><td class="td-panel" width="100px">RAC: <select class="inputform" name= "esRAC" id= "esRAC" value= "-1"'.$habilitador.'>';
                        
                    if($Registro['esRAC'] == 1)
                        {
                            //Si el empleado es un Responsable de Atencion a Cliente.
                            $HTML .= '<option value=-1>Seleccione</option>';
                            $HTML .= '<option value="0">No</option>';
                            $HTML .= '<option value="1" selected>Si</option>';
                            }
                    else
                        {
                            if($Registro['esRAC'] == 0)
                                {
                                    //Si el empleado no es un Responsable de Atencion al Cliente.
                                    $HTML .= '<option value=-1>Seleccione</option>';
                                    $HTML .= '<option value="0" selected>No</option>';
                                    $HTML .= '<option value="1">Si</option>';
                                    }
                            else
                                {
                                    //En defecto.
                                    $HTML .= '<option value=-1 selected>Seleccione</option>';
                                    $HTML .= '<option value="0">No</option>';
                                    $HTML .= '<option value="1">Si</option>';
                                    }
                            }
                        
                    $HTML .= '</select></td>';
                    return $HTML;
                    }
                    
            public function getRegistro($idRegistro)
                {
                    /*
                     * Esta funcion obtiene el dataset del registro de cliente apartir del ID proporcionado.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta= 'SELECT * FROM catEmpleados WHERE idEmpleado='.$idRegistro; //Se establece el modelo de consulta de datos.
                    $dsEmpleado = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    
                    $RegEmpleado = @mysqli_fetch_array($dsEmpleado,MYSQLI_ASSOC);//Llamada a la funcion de carga de registro de usuario.
                    return $RegEmpleado;
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

            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO opEmplados.php            
            }
?>