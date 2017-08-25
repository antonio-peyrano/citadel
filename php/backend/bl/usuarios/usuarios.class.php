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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/codificador.class.php"); //Se carga la referencia a la clase de manejo de encriptado.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    
    class usuarios
        {
            /*
             * Esta clase contiene los atributos y procedimientos vinculados con el comportamiento
             * y funcionalidades de la interfaz del modulo de usuarios.
             */
            
            //ATRIBUTOS APLICABLES AL MODULO catUsuarios.php
            private $Condicionales = "";
            private $Sufijo = "usr_";
            private $Usuario = "";
            private $Correo = "";
            private $Consulta = "SELECT idUsuario, Nivel, Usuario, Clave, Correo, catUsuarios.Status FROM catUsuarios INNER JOIN catNiveles ON catNiveles.idNivel = catUsuarios.idNivel WHERE catUsuarios.Status=0";
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO catUsuarios.php
            
            //ATRIBUTOS APLICABLES AL MODULO opUsuarios.php
            private $idUsuario = 0;
            private $cntView = 0;
            private $claveCod = "";
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO opUsuarios.php
            
            public function __construct()
                {
                    //Declaracion de constructor (VACIO)
                    }

            //PROCEDIMIENTOS APLICABLES AL MODULO catUsuarios.php
            public function getUsuario()
                {
                    /*
                     * Esta funcion retorna el valor de nombre de usuario.
                     */
                    return $this->Usuario;
                    }

            public function getCorreo()
                {
                    /*
                     * Esta funcion retorna el valor de correo del usuario.
                     */
                    return $this->Correo;
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
                                
            public function setCatParametros($Usuario, $Correo)
                {
                    /*
                     * Esta funcion obtiene de la interaccion del usuario, los parametros
                     * para establecer los criterios de busqueda.
                     */
                    $this->Usuario = $Usuario;
                    $this->Correo = $Correo;
                    }  

            public function evaluaCondicion()
                {
                    /*
                     * Esta funcion evalua si la condicion de busqueda cumple con las caracteristica
                     * solicitadas por el usuario.
                     */
                    $this->Condicionales = "";
                    
                    if(!empty($this->getUsuario()))
                        {
                            $this->Condicionales = ' AND Usuario LIKE \'%'.$this->getUsuario().'%\'';
                            }
                            
                    if(!empty($this->getCorreo()))
                        {                       
                            $this->Condicionales= $this->Condicionales.' AND Correo LIKE \'%'.$this->getCorreo().'%\'';
                            }

                    return $this->Condicionales;                            
                    }                    
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO catUsuarios.php
            
            //PROCEDIMIENTOS APLICABLES AL MODULO opUsuarios.php
            public function getNiveles()
                {
                    /*
                     * Esta funcion obtiene la tupla de registros sobre el catalogo de niveles.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta= 'SELECT idNivel, Nivel FROM catNiveles WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dsNiveles = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    return $dsNiveles;
                    }

            public function getRegistro($idRegistro)
                {
                    /*
                     * Esta funciï¿½n obtiene el dataset del registro de usuario apartir del ID proporcionado.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta= 'SELECT idUsuario, catUsuarios.idNivel, Usuario, Clave, Correo, Pregunta, Respuesta, catUsuarios.Status FROM catUsuarios INNER JOIN catNiveles ON catNiveles.idNivel = catUsuarios.idNivel WHERE idUsuario='.$idRegistro; //Se establece el modelo de consulta de datos.
                    $dsUsuario = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    
                    $RegUsuario = @mysqli_fetch_array($dsUsuario,MYSQLI_ASSOC);//Llamada a la funcion de carga de registro de usuario.
                    return $RegUsuario;
                    }                    

            public function setOpParametros($idUsuario, $CntView)
                {
                    /*
                     * Esta funcion obtiene de la interaccion del usuario, los parametros
                     * para establecer los criterios de carga y gestion del registro.
                     */
                    $this->idUsuario = $idUsuario;
                    $this->CntView = $CntView;
                    }

            public function controlBotones($Width, $Height, $cntView)
                {
                    /*
                     * Esta funcion controla los botones que deberan verse en la pantalla deacuerdo con la accion solicitada por el
                     * usuario en la ventana previa.
                     * Si es una edicion, los botones borrar y guardar deben verse.
                     * Si es una creacion solo el boton guardar debe visualizarse.
                     */                    
                    if($cntView == 9)
                        {
                            //CASO ESPECIAL: CUANDO EL INVITADO CREA SU USUARIO EN EL SISTEMA.
                            $this->Sufijo = "scu_";     
                            }
                    else
                        {
                            if(!isset($_SESSION))
                                {
                                    //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                                    session_name('citadel');
                                    session_start();
                                    }                            
                            }                            
                    
                    $botonera = '';
                    $btnVolver_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/volver.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Volver" id="'.$this->Sufijo.'Volver" title= "Volver"/>';
                    $btnBorrar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/erase.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Borrar" id="'.$this->Sufijo.'Borrar" title= "Borrar"/>';
                    $btnGuardar_V =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$this->Sufijo.'Guardar" title= "Guardar"/>';
                    $btnGuardar_H =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$this->Sufijo.'Guardar" title= "Guardar" style="display:none;"/>';
                    $btnEditar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/edit.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Editar" id="'.$this->Sufijo.'Editar" title= "Editar"/>';
                                        
                    if(($cntView == 0)||($cntView == 2)||($cntView == 9))
                        {
                            //CASO: CREACION O EDICION DE REGISTRO.
                            if(isset($_SESSION['nivel']))
                                {
                                    //Se valida que la variable de sesion "nivel" contenga un valor instanciado.
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
                                    //En caso que no se encuentre establecida la variable de sesion
                                    //"nivel" se infiere que el usuario gestiona su creación como invitado.
                                    if($cntView == 9)
                                        {
                                            //Se valida que la ejecución provenga como modo invitado.
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
            
            public function cargarPreguntas($habCampos, $Pregunta)
                {
                    /*
                     * Esta funcion crea un combobox con las preguntas posibles que puede seleccionar el usuario,
                     * tomando en cuenta para la carga si el usuario ha seleccionado previamente una pregunta.
                     */
                    
                    $HTML = '<select id= "Pregunta" class= "inputform" '.$habCampos.' >';
                        	
                    if("" == $Pregunta)
                        {
                            $HTML .= '<option value= "Seleccione" selected="selected">Seleccione</option>';
                            }
                    else
                        {
                            $HTML .= '<option value= "Seleccione">Seleccione</option>';
                            }
                    if("Su primera mascota" == $Pregunta)
                        {
                            $HTML .= '<option value= "Su primera mascota" selected="selected">Su primera mascota</option>';
                            }
                    else
                        {
                            $HTML .= '<option value= "Su primera mascota">Su primera mascota</option>';
                            }
                    if("Su comida favorita" == $Pregunta)
                        {
                            $HTML .= '<option value= "Su comida favorita" selected="selected">Su comida favorita</option>';
                            }
                    else
                        {
                            $HTML .= '<option value= "Su comida favorita">Su comida favorita</option>';
                            }
                    if("Su pasatiempo favorito" == $Pregunta)
                        {
                            $HTML .= '<option value= "Su pasatiempo favorito" selected="selected">Su pasatiempo favorito</option>';
                            }
                    else
                        {
                            $HTML .= '<option value= "Su pasatiempo favorito">Su pasatiempo favorito</option>';
                            }
                    if("Su pelicula favorita" == $Pregunta)
                        {
                            $HTML .= '<option value= "Su pelicula favorita" selected="selected">Su pelicula favorita</option>';
                            }
                    else
                        {
                            $HTML .= '<option value= "Su pelicula favorita">Su pelicula favorita</option>';
                            }
                        	
                    $HTML .= '</select>';
                        	
                    return $HTML;
                    }            
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO opUsuarios.php
            
            //PROCEDIMIENTOS APLICABLES AL MODULO opPermisos.php
            public function getPermisos($idUsuario)
                {
                    /*
                     * Esta funcion obtiene la tupla de registros sobre los modulos asociados al usuario.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta = 'SELECT *FROM ((catModulos INNER JOIN opRelPerUsr ON opRelPerUsr.idModulo = catModulos.idModulo) INNER JOIN catUsuarios ON catUsuarios.idUsuario = opRelPerUsr.idUsuario) WHERE opRelPerUsr.Status=0 AND opRelPerUsr.idUsuario='.$idUsuario; //Se establece el modelo de consulta de datos.
                    $dsPerUsr = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    return $dsPerUsr;
                    }

            public function getModulos()
                {
                    /*
                     * Esta funcion obtiene la tupla de registros sobre el catalogo de modulos.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta = 'SELECT *FROM catModulos WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dsModulos = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    return $dsModulos;
                    }

            public function listaModulos($habCampos, $idUsuario)
                {
                    /*
                     * Esta funcion genera el codigo HTML que corresponde a los checkbox asociados a los
                     * modulos disponibles en sistema. Seleccionando aquellos que han sido previamente
                     * asociados al usuario.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $HTML = '';
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    
                    $dsModulos = $this->getModulos();                    
                    $regModulos = @mysqli_fetch_array($dsModulos,MYSQLI_ASSOC);
                    
                    while($regModulos)
                        {
                            //MIENTRAS EXISTAN MODULOS POR VERIFICAR.
                            $dsPermisos = $this->getPermisos($idUsuario);
                            $regPermisos = @mysqli_fetch_array($dsPermisos,MYSQLI_ASSOC);
                            
                            $checkState = '';
                                                        
                            while($regPermisos)
                                {
                                    //MIENTRAS EXISTAN PERMISOS POR VERIFICAR.
                                    if($regModulos['idModulo'] == $regPermisos['idModulo'])
                                        {
                                            //AL ENCONTRAR COINCIDENCIA, SE MARCA EN LA LISTA.
                                            $checkState = 'checked';
                                            }                                            
                                    $regPermisos = @mysqli_fetch_array($dsPermisos,MYSQLI_ASSOC);
                                    }
                                    
                            $HTML .= '<input type="checkbox" class="check" id="modulos[]" name="modulos[]" '.$habCampos.' value="'.$regModulos['idModulo'].'" '.$checkState.'>'.$regModulos['Modulo'].' ';                                    
                            $regModulos = @mysqli_fetch_array($dsModulos,MYSQLI_ASSOC);
                            }
                            
                    return $HTML;
                    }                    
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO opPermisos.php
            }
?>