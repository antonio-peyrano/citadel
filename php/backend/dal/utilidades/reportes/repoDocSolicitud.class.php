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
    include_once ($_SERVER['DOCUMENT_ROOT'].'/citadel/php/backend/bl/utilidades/vendor/autoload.php');
    //header('Content-Type: image/png');
    
    use Spipu\Html2Pdf\Html2Pdf;
    use Spipu\Html2Pdf\Exception\Html2PdfException;
    use Spipu\Html2Pdf\Exception\ExceptionFormatter;
    
    class repoDocSolicitud
        {
    
            private $Folio = '';
            private $fRegistro = '';
            private $Asunto = '';
            private $Entidad = '';
            private $Detalle = '';
            private $idUsuario = NULL;
            private $idEntidad = NULL;
            private $Usuario = '';        
            private $Status = '';
        
            public function __construct()
                {
                    /*
                     * Declaracion del procedimiento constructor para la interfaz de documento
                     * de levantamiento de solicitud.
                     */
                    if(isset($_GET['folio'])){$this->Folio=$_GET['folio'];}
                    if(isset($_GET['fregistro'])){$this->fRegistro=$_GET['fregistro'];}
                    if(isset($_GET['asunto'])){$this->Asunto=$_GET['asunto'];}
                    if(isset($_GET['identidad'])){$this->idEntidad=$_GET['identidad'];}
                    if(isset($_GET['detalle'])){$this->Detalle=$_GET['detalle'];}
                    if(isset($_GET['idusuario'])){$this->idUsuario=$_GET['idusuario'];}
                    if(isset($_GET['status'])){$this->Status=$_GET['status'];}
                    $this->getBarCode($this->Folio);
                    }

            public function getStatus()
                {
                    /*
                     * Esta funcion obtiene el texto que corresponde al Status de la solicitud
                     * en el sistema.
                     */
                    if($this->Status == '0'){$this->Status="Registrada";}
                    if($this->Status == '1'){$this->Status="Canalizada";}
                    if($this->Status == '2'){$this->Status="En Proceso";}
                    if($this->Status == '3'){$this->Status="Procesada";}
                    if($this->Status == '4'){$this->Status="Cancelada";}
                    }
                    

            public function getUsuario()
                {
                    /*
                     * Esta funcion obtiene el nombre del usuario a partir del ID
                     * proporcionado.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idUsuario, Usuario FROM catUsuarios WHERE Status=0 AND idUsuario='.$this->idUsuario; //Se establece el modelo de consulta de datos.
                    $dsUsuario = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegUsuario = mysqli_fetch_array($dsUsuario, MYSQLI_ASSOC);
                    
                    if($RegUsuario)
                        {
                            $this->Usuario = $RegUsuario['Usuario'];
                            }
                    else
                        {
                            $this->Usuario = "Usuario no definido en sistema";
                            }                            
                    }

            public function getEntidad()
                {
                    /*
                     * Esta funcion obtiene el nombre de la entidad a partir del ID
                     * proporcionado.
                     */
                    global $username, $password, $servername, $dbname;
                        
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0 AND idEntidad='.$this->idEntidad; //Se establece el modelo de consulta de datos.
                    $dsEntidad = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegEntidad = mysqli_fetch_array($dsEntidad, MYSQLI_ASSOC);
                        
                    if($RegEntidad)
                        {
                            $this->Entidad = $RegEntidad['Entidad'];
                            }
                    else
                        {
                            $this->Entidad = "Entidad no definida en sistema";
                            }
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
                    
            public function getBarCode($Folio)
                {
                    //Creamos la imagen, calculando primero sus dimensiones dependiendo del tamaño del texto.
                    $fontname = $_SERVER['DOCUMENT_ROOT'].'/citadel/fonts/Code39.ttf';                    
                    $this->letra = array ('font'  => $fontname, 'size'  => 12, 'color' => array(255,255,255),); //Estilo de letra y tamaño.
                        
                    $this->text = '*'.$Folio.'*';
                    $box_size = $this->dimensions($this->letra, $this->text);
                    $Imagen = imagecreatetruecolor($box_size[0], $box_size[1]);
                        
                    //Creamos dos colores, y definimos el color que actuará como transparente
                    $background = imagecolorallocate($Imagen, 0, 0, 0);
                    $fontColor  = imagecolorallocate($Imagen, $this->letra['color'][0], $this->letra['color'][1], $this->letra['color'][2]);
                    imagecolortransparent($Imagen, $background);
                        
                    //Ponemos el fondo de la imagen transparente
                    imagefilledrectangle($Imagen, 0, 0, $box_size[0], $box_size[1], $background);
                        
                        
                    //Creamos el texto
                    imagettftext($Imagen, $this->letra['size'], 0, 0, $box_size[2], $fontColor, $this->letra['font'], $this->text);
                        
                    //Generamos la salida
                    imagepng($Imagen, $_SERVER['DOCUMENT_ROOT'].'/citadel/uploads/temporal/'.$Folio.'.png');
                    imagedestroy($Imagen);
                    }
                                        
            public function repoSolicitudUI()
                {
                    /*
                     * Esta funcion construye el cuerpo HTML que sera utilizado para generar el reporte en
                     * PDF
                     */
                    global $SitioWeb;
                    
                    $this->getUsuario();
                    $this->getEntidad();
                    $this->getStatus();
                    
                    $HTML =     '<style type="text/css">
                                    p.Folio {
                                                color: red;
                                                font-weight: bold;
                                                }
                                    div.centerTable {
                                                        text-align: center;
                                                        }
                                    div.centerTable table {
                                                            margin: 0 auto;text-align: left;
                                                            }
                                    td.labels {
                                                background-color: #4CAF50;
                                                color: white;
                                                border-bottom: 1px solid #ddd;
                                                width: auto;
                                                height: 30px;
                                                text-align: center;
                                                vertical-align: middle;
                                                font-weight: bold;
                                                }
                                </style>';
                    
                    $HTML .=    '<div align="center">
                                    <p><b>FORMATO PARA GESTION DE SOLICITUDES DE ATENCION A CLIENTE</b></p>
                                </div><br>';
                    
                    $HTML .=    '<div align="right">
                                    <p class="Folio"><img src="'.$SitioWeb.'uploads/temporal/'.$this->Folio.'.png">
                                    <br>'.$this->Folio.'</p>
                                </div><br>';
                    
                    $HTML .=    '<div class="centerTable">
                                    <table>
                                        <tr>
                                            <td class="labels">Folio </td>
                                            <td style="width: auto; border: solid 1px">'.$this->Folio.'</td>
                                            <td class="labels">Fecha de Registro </td>
                                            <td style="width: auto; border: solid 1px">'.$this->fRegistro.'</td></tr>
                                        <tr><td class="labels">Asunto </td>
                                            <td style="width: auto; border: solid 1px" colspan="3">'.$this->Asunto.'</td></tr>
                                        <tr><td class="labels" colspan="4">Detalle </td></tr>
                                        <tr><td style="width: auto; border: solid 1px" colspan="4">'.$this->Detalle.'</td></tr>
                                        <tr><td class="labels">Entidad </td>
                                            <td style="width: auto; border: solid 1px" colspan="3">'.$this->Entidad.'</td></tr>
                                        <tr><td class="labels">Usuario </td>
                                            <td style="width: auto; border: solid 1px" colspan="3">'.$this->Usuario.'</td></tr>
                                        <tr><td class="labels">Estado de la Solicitud </td>
                                            <td style="width: auto; border: solid 1px" colspan="3">'.$this->Status.'</td></tr>
                                    </table>
                                </div>';
                    
                    return $HTML;                   
                    }
        }
        
    try 
        {
            //ob_start();
            $objRepoSolicitud = new repoDocSolicitud();
            $content = $objRepoSolicitud->repoSolicitudUI();
            //echo $content;
            //$content = ob_get_clean();
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');            
            $html2pdf->writeHTML($content);
            $html2pdf->output('exemple00.pdf');
            }
    catch (Html2PdfException $e)
        {
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
?>