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
    
    class clientes
        {
            /*
             * Esta clase contiene los atributos y procedimientos vinculados con el comportamiento
             * y funcionalidades de la interfaz del modulo de clientes.
             */
            
            //ATRIBUTOS APLICABLES AL MODULO catClientes.php
            private $Condicionales = "";
            private $Sufijo = "cli_";
            private $Paterno = "";
            private $Materno = "";
            private $Nombre = "";
            private $curp = "";
            private $fNacimiento = "";
            private $telFijo = "";
            private $telCel = "";
            private $Consulta = "SELECT idCliente, CONCAT (Nombre,' ' , Paterno,' ', Materno) AS Cliente, fNacimiento AS Nacimiento, Status FROM catClientes WHERE Status=0";
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO catClientes.php
            
            //ATRIBUTOS APLICABLES AL MODULO opClientes.php
            private $idCliente = 0;
            private $cntView = 0;
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO opClientes.php
            
            public function __construct()
                {
                    //Declaracion de constructor (VACIO)
                    }

            //PROCEDIMIENTOS APLICABLES AL MODULO catClientes.php
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
                     * Esta funcion retorna el valor de CURP del cliente.
                     */
                    return $this->curp;
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
                                
            public function setCatParametros($Paterno, $Materno, $Nombre, $curp)
                {
                    /*
                     * Esta funcion obtiene de la interaccion del usuario, los parametros
                     * para establecer los criterios de busqueda.
                     */
                    $this->Paterno = $Paterno;
                    $this->Materno = $Materno;
                    $this->Nombre = $Nombre;
                    $this->curp = $curp;
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
                                                        
                    return $this->Condicionales;                            
                    }                    
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO catClientes.php
            
            //PROCEDIMIENTOS APLICABLES AL MODULO opClientes.php.
            public function getRegistro($idRegistro)
                {
                    /*
                     * Esta funcion obtiene el dataset del registro de cliente apartir del ID proporcionado.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta= 'SELECT idCliente, Paterno, Materno, Nombre, fNacimiento, curp, telFijo, telCel, Status FROM catClientes WHERE idCliente='.$idRegistro; //Se establece el modelo de consulta de datos.
                    $dsUsuario = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    
                    $RegUsuario = @mysqli_fetch_array($dsUsuario,MYSQLI_ASSOC);//Llamada a la funcion de carga de registro de usuario.
                    return $RegUsuario;
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

            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO opClientes.php            
            }
?>