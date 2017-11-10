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

    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/jpgraph/src/jpgraph.php"); //Se carga la referencia a la clase base de graficador.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/jpgraph/src/jpgraph_pie.php"); //Se carga la referencia a la clase especifica de grafica de pay.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/jpgraph/src/jpgraph_pie3d.php"); //Se carga la referencia a la clase especifica de grafica de pay.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    header('Content-Type: image/png');
    
    class dalGraficaCircular
        {
            private $idEntidad = '';
            private $zeroControll = 0; //Atributo de control para la salida de datos y prevension de errores.
            private $Identificador = '';
            private $Conceptos = array (0.00, 0.00, 0.00, 0.00, 0.00);
            private $Leyendas = array('Registradas','Canalizadas', 'En Proceso','Procesadas','Canceladas');
            
            //Atributos para la gestion de errores en el procesamiento de datos.
            private $fontname;
            private $textArray = array("Datos insuficientes para procesar", "Error con la conectividad de la BD", 'Fallo critico, contactar con el administrador'); //Listado de posibles mensajes.
            private $letra; //Estilo de letra y tamaño.
            private $text;
            
            public function __construct()
                {
                    /*
                     * Declaracion de constructor de la clase.
                     */
                    if(isset($_GET['identidad'])){$this->idEntidad = $_GET['identidad'];}
                    }
            
            public function getidEntidad()
                {
                    //Esta funcion retorna el valor almacenado para el atributo idEntidad.
                    return $this->idEntidad;
                    }

            public function getZeroControll()
                    {
                        //Esta funcion retorna el valor almacenado para el atributo de control de fallos.
                        return $this->zeroControll;
                    }
                    
            public function obtenerDatos()
                {
                    //Esta función obtiene el valor esperado de la eficacia por la entidad consultada.
                    global $username, $password, $servername, $dbname;
                   
                    $this->zeroControll = 0;                    
                    $idCampo = 'Entidad';
                    
                    $consIdentificador = 'SELECT *FROM catEntidades WHERE idEntidad ='.$this->getidEntidad();
                    $consRegistradas = 'SELECT *FROM opSolicitudes WHERE Status=0 AND idEntidad='.$this->getidEntidad();
                    $consCanalizadas = 'SELECT *FROM opSolicitudes WHERE Status=1 AND idEntidad='.$this->getidEntidad();
                    $consEnProceso = 'SELECT *FROM opSolicitudes WHERE Status=2 AND idEntidad='.$this->getidEntidad();
                    $consProcesadas = 'SELECT *FROM opSolicitudes WHERE Status=3 AND idEntidad='.$this->getidEntidad();
                    $consCanceladas = 'SELECT *FROM opSolicitudes WHERE Status=4 AND idEntidad='.$this->getidEntidad();
                        
                    //Se obtiene el referente del identificador a evaluar.
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $dsIdentificador = $objConexion -> conectar($consIdentificador); //Se ejecuta la consulta.
                    $RegIdentificador = @mysqli_fetch_array($dsIdentificador,MYSQLI_ASSOC);
                        
                    $this->Identificador = $RegIdentificador[$idCampo];
                        
                    //Se obtienen el conteo de Registradas.
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $dsConsulta = $objConexion -> conectar($consRegistradas); //Se ejecuta la consulta.
                    if(@mysqli_num_rows($dsConsulta)>0){$this->Conceptos[0] = @mysqli_num_rows($dsConsulta);}else{$this->zeroControll+=1;}
                    
                    //Se obtienen el conteo de Canalizadas.
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $dsConsulta = $objConexion -> conectar($consCanalizadas); //Se ejecuta la consulta.
                    if(@mysqli_num_rows($dsConsulta)>0){$this->Conceptos[1] = @mysqli_num_rows($dsConsulta);}else{$this->zeroControll+=1;}
                    
                    //Se obtienen el conteo de En Proceso.
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $dsConsulta = $objConexion -> conectar($consEnProceso); //Se ejecuta la consulta.
                    if(@mysqli_num_rows($dsConsulta)>0){$this->Conceptos[2] = @mysqli_num_rows($dsConsulta);}else{$this->zeroControll+=1;}
                        
                    //Se obtienen el conteo de Procesadas.
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $dsConsulta = $objConexion -> conectar($consProcesadas); //Se ejecuta la consulta.
                    if(@mysqli_num_rows($dsConsulta)>0){$this->Conceptos[3] = @mysqli_num_rows($dsConsulta);}else{$this->zeroControll+=1;}
                        
                    //Se obtienen el conteo de Canceladas.
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $dsConsulta = $objConexion -> conectar($consCanceladas); //Se ejecuta la consulta.
                    if(@mysqli_num_rows($dsConsulta)>0){$this->Conceptos[4] = @mysqli_num_rows($dsConsulta);}else{$this->zeroControll+=1;}
                    }

            public function dimensions($ldef, $texto)
                {
                    /*
                     * Esta funcion establece los parametros de dimensiones para
                     * el texto a visualizar como imagen.
                     */
                    //Calculo del ancho de la imagen a partir de la longitud del texto.
                    $box = imagettfbbox ($ldef['size'], 0, $ldef['font'], $texto);
                    $width = abs(abs($box[2]) - abs($box[0]));
                        
                    //Calculo del alto del texto a partir del tamaño de la fuente y su estilo.
                    $box = imagettfbbox ($ldef['size'], 0, $ldef['font'], 'ILyjgq'); // Tiene caracteres que no terminan en la baseline
                    $height = abs($box[7] - $box[1]);                        
                    $baseline = abs($box[5]);
                        
                    return array($width, $height, $baseline);
                    }
                    
            public function msgError()
                {
                    //Creamos la imagen, calculando primero sus dimensiones dependiendo del tamaño del texto.
                    $fontname = $_SERVER['DOCUMENT_ROOT'].'/citadel/fonts/LinLibertine_Bd.ttf';
                    $this->textArray = array("Datos insuficientes para procesar", "Error con la conectividad de la BD", 'Fallo critico, contactar con el administrador'); //Listado de posibles mensajes.
                    $this->letra = array ('font'  => $fontname, 'size'  => 30, 'color' => array(255,255,255),); //Estilo de letra y tamaño.
                    
                    $this->text = $this->textArray[0];
                    $box_size = $this->dimensions($this->letra, $this->text);
                    $Imagen = imagecreatetruecolor($box_size[0], $box_size[1]);
                    
                    //Creamos dos colores, y definimos el color que actuará como transparente
                    $background = imagecolorallocate($Imagen, 204, 0, 0);
                    $fontColor  = imagecolorallocate($Imagen, $this->letra['color'][0], $this->letra['color'][1], $this->letra['color'][2]);
                    //imagecolortransparent($Imagen, $background);
                    
                    //Ponemos el fondo de la imagen transparente
                    imagefilledrectangle($Imagen, 0, 0, $box_size[0], $box_size[1], $background);
                    
                    
                    //Creamos el texto
                    imagettftext($Imagen, $this->letra['size'], 0, 0, $box_size[2], $fontColor, $this->letra['font'], $this->text);
                    
                    //Generamos la salida
                    imagepng($Imagen);
                    imagedestroy($Imagen);
                    }
                    
            public function graficador()
                {
                    /*
                     * Esta funcion genera un grafico de barras apartir de un vector de datos provisto
                     * por una consulta del usuario.
                     */
                                    
                    $grafico = new PieGraph(1000,500); //Se crea la instancia del objeto de grafico.
                        
                    /*
                     * El siguiente bloque de parametros, establece las caracteristicas que debe tener
                     * el area del grafico y su respectivo formato de atributos.
                     */
                        
                    $grafico->SetShadow();
                        
                    //Ajuste sobre el formato general del grafico.
                    $grafico->title->Set("Relacion de Atencion a Solicitudes para ". $this->Identificador);
                    $grafico->title->SetFont(FF_ARIAL,FS_BOLD,12);
                    $grafico->title->SetColor('white');
                    $grafico->SetColor('#A4A4A4');
                        
                    $p1 = new PiePlot3D($this->Conceptos);
                    $p1->SetLabelType(PIE_VALUE_ADJPERCENTAGE); //Correccion de redondeo entero.
                    $p1->SetAngle(20);
                    $p1->SetSize(0.5);
                    $p1->SetCenter(0.45);
                    $p1->SetLegends($this->Leyendas);
                        
                    //Ajuste sobre el aspecto visual de los valores.
                    $p1->value->SetFont(FF_ARIAL,FS_BOLD);
                    $p1->value->SetColor('white');
                    $p1->SetLabelPos(0.6);
                        
                    $grafico->Add($p1);
                    $grafico->Stroke(); //Se puede establecer una ruta para almacenar la imagen.
                    }            
            }       
            
    $objGraficador = new dalGraficaCircular();

    $objGraficador->obtenerDatos(); //Obtencion de datos para el graficado.
    
    if($objGraficador->getZeroControll() == 5)
        {
            //En caso de ocurrir un error de valores vacios.
            $objGraficador->msgError();
            }
    else
        {
            //En caso de contar con informacion suficiente para graficar.
            $objGraficador->graficador(); //Llamada a la función de graficación.
            }        
?>