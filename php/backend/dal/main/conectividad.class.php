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

/***********************************************************************************************************
 * Archivo: conectividad.class.php * Descripcion: Clase que contiene el codigo para la creacion de objetos *
 *                                 * que permitan la manipulacion de registros en una base de datos mySQL  *
 ***********************************************************************************************************
 * Desarrollador: Mtro. Jesus Antonio Peyrano Luna * Ultima modificacion: 27/09/2016                       *
 ***********************************************************************************************************/
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/codificador.class.php"); //Se carga la referencia de la clase para manejo de encriptado.
    
	class mySQL_conexion
		{
			/*
			 * Esta clase tiene como funcion establecer los parametros para la conexion con la base de datos
			 * de datos e interactuar con la informacion.
			 */
			 private $conexion = null; 		#Variable de control de conexion (True = Conexion OK / False = Falla).
			 private $bdConexion = null;	#Variable de control de base de datos (True = Localizada / False = Falla).
			 private $dataset = null;		#Variable de control para la tupla de datos.
			 
			 private $bdName = '';			#El nombre asignado de la base de datos a conectarse.
			 private $serverName = '';		#El nombre del servidor o su direccionamiento IP.
			 private $userName = '';		#El usuario con el que haremos conexion de la BD.
			 private $userPassword = '';	#La clave asignada del usuario para acceder a la BD.
			 
			public function __construct($user, $pass, $server, $bd)
				{
					/*
					 * Esta funcion inicializa los parametros para interactuar con las funciones de la clase.
					 */
				    $objCodificador = new codificador();
					$this->bdName = $objCodificador->decrypt($bd, "ouroboros"); #El nombre asignado de la base de datos a conectarse.
					$this->serverName = $objCodificador->decrypt($server, "ouroboros");	#El nombre del servidor o su direccionamiento IP.
					$this->userName = $objCodificador->decrypt($user, "ouroboros");		#El usuario con el que haremos conexion de la BD.
					$this->userPassword = $objCodificador->decrypt($pass, "ouroboros");	#La clave asignada del usuario para acceder a la BD. 
					}
					
			 public function conectar($consulta)
				{
					/*
					 * Esta funcion ejecuta las instrucciones necesarias para conectar con la base de datos
					 * asi como obtener o modificar la informacion mediante la consulta sugerida.
					 */					
				    $this->conexion = mysqli_connect($this->serverName, $this->userName, $this->userPassword);
				    mysqli_set_charset($this->conexion,"ISO-8859-1");
				    
					if(!$this->conexion)
						{
							//En caso de ocurrir un error con la entrada a la base de datos
							//se notifica al usuario por medio de un mensaje en pantalla.
							die('No pudo establecerse la conexion el servidor: ' . mysqli_error($this->conexion));
							}
					else
						{
							//En caso de obtener una conexion satisfactoria con la base de datos
							//se procede a la ejecucion de las instrucciones.
							$this->bdConexion = mysqli_select_db($this->conexion, $this->bdName);
							
							if(!$this->bdConexion)
								{
									//En caso de ocurrir un error con la seleccion de la base de datos
									//se notifica al usuario por medio de un mensaje en pantalla.								
								    die ('No se puede usar '. $this->bdName .': '. mysqli_error($this->conexion));
									}
							else
								{
									//En caso de obtener control de la base de datos.
                                    $this->dataset = mysqli_query($this->conexion, $consulta);                                    
									}
							}
							
					/*mysql_free_result($this->dataset);*/
					mysqli_close($this->conexion); //Se cierra la conexion con la base de datos.
					return $this->dataset;
					}
			}
?>
