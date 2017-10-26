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
    
    class solicitudes
        {
            /*
             * Esta clase contiene los atributos y procedimientos vinculados con el comportamiento
             * y funcionalidades de la interfaz del modulo de solicitudes.
             */
            
            //ATRIBUTOS APLICABLES AL MODULO catSolicitudes.php
            private $Condicionales = "";
            private $Sufijo = "sol_";
            private $Folio = "";
            private $fRegistro = "";
            private $Asunto = "";
            private $Detalle = "";
            private $Consulta = 'SELECT idSolicitud, idUsuario, Folio, fRegistro AS Registro, Asunto, if(Status=0,"Registrada",if(Status=1,"En Proceso",if(Status=2,"Procesada",if(Status=3,"Cancelada","No definido")))) AS Status FROM opSolicitudes WHERE 1=1';
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO catSolicitudes.php
            
            //ATRIBUTOS APLICABLES AL MODULO opSolicitudes.php
            private $idSolicitud = "";
            private $idUsuario = "";
            private $cntView = 0;
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO opSolicitudes.php
            
            public function __construct()
                {
                    //Esta funcion crea las referencias de valores para los datos
                    //de ID de Usuario y Fecha de Registro.
                    $this->setIDUsuario();
                    $this->setFechaRegistro(); //Se genera la fecha de registro.
                    }
                                        
            //PROCEDIMIENTOS APLICABLES AL MODULO catSolicitudes.php
            public function getFolio()
                {
                    /*
                     * Esta funcion retorna el valor de folio.
                     */
                    return $this->Folio;
                    }

            public function getfRegistro()
                {
                    /*
                     * Esta funcion retorna el valor de fecha de registro.
                     */
                    return $this->fRegistro;
                    }

            public function getAsunto()
                {
                    /*
                     * Esta funcion retorna el valor de asunto.
                     */
                    return $this->Asunto;
                    }
                                        
            public function getIDUsuario()
                {
                    /*
                     * Esta funcion retorna el valor de ID del Usuario.
                     */
                    return $this->idUsuario;
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
                                
            public function setCatParametros($Folio, $Asunto, $fRegistro, $idUsuario)
                {
                    /*
                     * Esta funcion obtiene de la interaccion del usuario, los parametros
                     * para establecer los criterios de busqueda.
                     */
                    $this->Folio = $Folio;
                    $this->Asunto = $Asunto;
                    $this->fRegistro = $fRegistro;
                    $this->idUsuario = $idUsuario;
                    }  

            public function evaluaCondicion()
                {
                    /*
                     * Esta funcion evalua si la condicion de busqueda cumple con las caracteristica
                     * solicitadas por el usuario.
                     */
                    $this->Condicionales = "";
                    
                    if(!empty($this->getFolio()))
                        {
                            $this->Condicionales = ' AND Folio LIKE \'%'.$this->getFolio().'%\'';
                            }
                            
                    if(!empty($this->getAsunto()))
                        {                       
                            $this->Condicionales .= ' AND Asunto LIKE \'%'.$this->getAsunto().'%\'';
                            }

                    if(!empty($this->getfRegistro()))
                        {
                            date_default_timezone_set("America/Mexico_City");
                            $this->Condicionales .= ' AND fRegistro = \''.date("Y/m/d H:i:s",strtotime($this->getfRegistro())).'\'';
                            }

                    if(!empty($this->getIDUsuario()))
                        {
                            if($this->getIDUsuario()!="-1")
                                {
                                    $this->Condicionales .= ' AND idUsuario = \''.$this->getIDUsuario().'\'';
                                    }
                            }
                                                        
                    return $this->Condicionales;                            
                    }                    
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO catSolicitudes.php
            
            //PROCEDIMIENTOS APLICABLES AL MODULO opSolicitudes.php.            
            public function setFechaRegistro()
                {
                    /*
                     * Esta funcion calcula la fecha en la que se da de alta el registro
                     */
                    $now = time(); //Se obtiene la referencia del tiempo actual del servidor.
                    date_default_timezone_set("America/Mexico_City"); //Se establece el perfil del uso horario.
                    $this->fRegistro = date("Y/m/d",$now); //Se obtiene la referencia compuesta de fecha y hora.
                    }

            public function setIDUsuario()
                {
                    /*
                     * Esta funcion establece el ID del usuario que realiza la solicitud
                     * a partir de su perfil de sesion.
                     */
                    
                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('citadel');
                            session_start();
                            }
                    
                    $objUsrCntrl = new usrctrl();
                    
                    $this->idUsuario = $objUsrCntrl->getidUsuario($_SESSION['usuario'], $_SESSION['clave']);
                    }
                                        
            public function getRegistro($idRegistro)
                {
                    /*
                     * Esta funcion obtiene el dataset del registro de cliente apartir del ID proporcionado.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta= 'SELECT idSolicitud, idUsuario, idEntidad, Folio, Asunto, Detalle, fRegistro, Status FROM opSolicitudes WHERE idSolicitud='.$idRegistro; //Se establece el modelo de consulta de datos.
                    $dsUsuario = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    
                    $RegSolicitud = @mysqli_fetch_array($dsUsuario,MYSQLI_ASSOC);//Llamada a la funcion de carga de registro de usuario.
                    return $RegSolicitud;
                    }
                    
            public function getEntidades()
                {
                    /*
                     * Esta funcion obtiene la tupla de registros sobre el catalogo de entidades.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta= 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dsEntidades = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    return $dsEntidades;
                    }
            
            public function controlBotones($Width, $Height, $cntView, $idUsuario)
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
                    $btnSubirArchivo_V = '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/uploads.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Adjuntar archivos" id="'.$this->Sufijo.'Adjuntar" title= "Adjuntar archivos"/>';
                    $btnSubirArchivo_H = '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/uploads.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Adjuntar archivos" id="'.$this->Sufijo.'Adjuntar" title= "Adjuntar archivos" style="display:none;"/>';
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
                                    if($cntView == 0)
                                        {
                                            //Para el caso de creaci�n de nuevo registro.
                                            $botonera .= $btnSubirArchivo_V.$btnGuardar_V.$btnVolver_V;
                                            }
                                    else
                                        {
                                            //Por default.
                                            $botonera .= $btnVolver_V;
                                            }                                    
                                    }
                            else
                                {
                                    if($_SESSION['nivel'] == "Administrador")
                                        {
                                            $botonera .= $btnSubirArchivo_V.$btnGuardar_V.$btnVolver_V;
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
                                            if($idUsuario == $this->getIDUsuario())
                                                {
                                                    //Si el registro fue creado por el usuario, se le permite el proceso de edicion.
                                                    $botonera .= $btnEditar_V.$btnBorrar_V.$btnSubirArchivo_V.$btnGuardar_H.$btnVolver_V;
                                                    }
                                            else
                                                {
                                                    //En el caso que no sea un registro previamente creado por el usuario
                                                    //se deja la restricci�n de solo visualizar.
                                                    $botonera .= $btnVolver_V;
                                                    }
                                            }
                                    else
                                        {
                                            if($_SESSION['nivel'] == "Administrador")
                                                {
                                                    $botonera .= $btnEditar_V.$btnBorrar_V.$btnSubirArchivo_V.$btnGuardar_H.$btnVolver_V;
                                                    }
                                            }                                    
                                    }
                            }
                            
                    return $botonera;
                    }                                

            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO opSolicitudes.php            
            }
?>