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
    
    class escalas
        {
            /*
             * Esta clase contiene los atributos y procedimientos vinculados con el comportamiento
             * y funcionalidades de la interfaz del modulo de escalas.
             */
            
            //ATRIBUTOS APLICABLES AL MODULO catEscalas.php
            private $Condicionales = "";
            private $Sufijo = "esc_";
            private $idCedula = NULL;
            private $Escala = "";
            private $Ponderacion = "";
            private $Consulta = "SELECT idEscala, opCedulas.Folio AS Cedula, Escala, Ponderacion, opEscalas.Status FROM (opEscalas INNER JOIN opCedulas ON opEscalas.idCedula = opCedulas.idCedula) WHERE opEscalas.Status=0";
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO catEscalas.php
            
            //ATRIBUTOS APLICABLES AL MODULO opEscalas.php
            private $idEscala = NULL;
            private $cntView = 0;
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO opEscalas.php
            
            public function __construct()
                {
                    //Declaracion de constructor (VACIO)                                        
                    }

            //PROCEDIMIENTOS APLICABLES AL MODULO catEscalas.php
            public function getEscala()
                {
                    /*
                     * Esta funcion retorna el valor del nombre de factor.
                     */
                    return $this->Escala;
                    }

            public function getPonderacion()
                {
                    /*
                     * Esta funcion retorna el valor de tipo de factor.
                     */
                    return $this->Ponderacion;
                    }

            public function getidCedula()
                {
                    /*
                     * Esta funcion retorna el valor de ID de la Cedula asociada.
                     */
                    return $this->idCedula;
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
                                
            public function setCatParametros($Escala, $Ponderacion, $idCedula)
                {
                    /*
                     * Esta funcion obtiene de la interaccion del usuario, los parametros
                     * para establecer los criterios de busqueda.
                     */
                    $this->Escala = $Escala;
                    $this->Ponderacion = $Ponderacion;
                    $this->idCedula = $idCedula;
                    }  

            public function evaluaCondicion()
                {
                    /*
                     * Esta funcion evalua si la condicion de busqueda cumple con las caracteristica
                     * solicitadas por el usuario.
                     */
                    $this->Condicionales = "";
                    
                    if(!empty($this->getEscala()))
                        {
                            $this->Condicionales = ' AND Escala LIKE \'%'.$this->getEscala().'%\'';
                            }
                            
                    if(!empty($this->getPonderacion()))
                        {
                            $this->Condicionales .= ' AND Ponderacion = \''.$this->getPonderacion().'\'';
                            }

                    if(!empty($this->getidCedula()))
                        {
                            if($this->getidCedula()!="-1")
                                {                            
                                    $this->Condicionales .= ' AND opEscalas.idCedula LIKE \'%'.$this->getidCedula().'%\'';
                                    }
                            }
                                                
                    return $this->Condicionales;                            
                    }                    
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO catEscalas.php
            
            //PROCEDIMIENTOS APLICABLES AL MODULO opEscalas.php.
                                
            public function getRegistro($idRegistro)
                {
                    /*
                     * Esta funcion obtiene el dataset del registro de escalas apartir del ID proporcionado.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta= 'SELECT idEscala, idCedula, Escala, Ponderacion, Status FROM opEscalas WHERE idEscala='.$idRegistro; //Se establece el modelo de consulta de datos.
                    $dsEscala = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    
                    $RegEscala = @mysqli_fetch_array($dsEscala,MYSQLI_ASSOC);//Llamada a la funcion de carga de registro de usuario.
                    return $RegEscala;
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

            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO opEscalas.php            
            }
?>