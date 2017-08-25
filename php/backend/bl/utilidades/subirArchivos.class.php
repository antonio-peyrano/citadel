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
    class subirArchivos
        {
            /*
             * Esta clase contiene los atributos y procedimientos necesarios para
             * el proceso de subida de archivos al servidor.
             */
            private $directorio = '';
            
            public function __construct($directorio)
                {
                    //Esta funcion constructor incializa los valores de control.
                    $this->directorio = $_SERVER['DOCUMENT_ROOT'].'/citadel/uploads/'.$directorio;
                    }
                    
            public function getDirectorio()
                {
                    //Esta funcion retorna el valor inicializado de directorio.
                    return $this->directorio;
                    }
                    
            public function genList()
                {
                    /*
                     * Esta funcion genera el listado de archivos dentro de la ruta
                     * especificada por el sistema.
                     */
                    $HTML = '<div id="File-List">';
                    
                    if(file_exists($this->getDirectorio()))
                        {
                            //Si el directorio es localizado, se genera un listado de archivos.
                            $archivos  = scandir($this->getDirectorio());
                            $items = count($archivos);

                            for($i=0; $i<$items; $i++)
                                {
                                    //Recorrido para generar la cadena de rutas de archivos.
                                    $HTML.= '<a href="'.'/citadel/uploads/'.$_GET["rutaadjuntos"].'/'.$archivos[$i].'">'.$archivos[$i].'</a><br>';
                                    }
                            }
                    else
                        {
                            //En caso de no localizar el directorio, se notifica al usuario.
                            $HTML .= '<br><b>NO SE LOCALIZARON ARCHIVOS</b>';
                            }
                    
                    return $HTML.'</div>';            
                    }

            public function MIMEType($MIMEType)
                {
                    /*
                     * Esta funcion evalua los tipos MIME soportados para
                     * la ejecucion del sistema.
                     * NOTA: Para mas referencias visitar: https://developer.mozilla.org/es/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Lista_completa_de_tipos_MIME
                     */
                    if(($MIMEType == "application/pdf")||($MIMEType == "image/jpeg")||($MIMEType == "image/png"))
                        {
                            //Si el tipo de archivo corresponde 
                            //a uno de los formatos de imagen y/o documento aceptados.
                            return true;
                            }
                            
                    return false;
                    }
                    
            public function uploader()
                {
                /*
                 * Esta funcion ejecuta la carga de archivos al servidor, a partir del directorio
                 * propuesto por la interaccion del usuario.
                 */
                    if(isset($_FILES["archivo"]) && $_FILES["archivo"]["name"][0])
                        {
                            //Si el usuario ha proporcionado al menos un archivo para su procesamiento.    
                            for($i=0;$i<count($_FILES["archivo"]["name"]);$i++)
                                {
                                    //Ciclo para el barrido de los archivos a subir al servidor.
                                    if($this->MIMEType($_FILES["archivo"]["type"][$i]))                                    
                                        {                                                                                                
                                            //Si el formato de archivo corresponde con los tipos permitidos (jpeg/png/pdf)
                                            if(file_exists($this->getDirectorio()) || @mkdir($this->getDirectorio()))                                
                                                {                                    
                                                    $origen = $_FILES["archivo"]["tmp_name"][$i];                                    
                                                    $destino = $this->getDirectorio().$_FILES["archivo"]["name"][$i];
                                                                                    
                                                    if(@move_uploaded_file($origen, $destino))
                                                        {
                                                            //echo $destino;
                                                            //echo "<br>".$_FILES["archivo"]["name"][$i]." movido correctamente";                                        
                                                            }
                                                    else
                                                        {                                        
                                                            //echo "<br>No se ha podido mover el archivo: ".$_FILES["archivo"]["name"][$i];                                        
                                                            }
                                                    }
                                            else
                                                {                                    
                                                    //echo "<br>No se ha podido crear la carpeta: up/".$user;                                    
                                                    }                                
                                            }
                                    else
                                        {
                                            //echo "<br>".$_FILES["archivo"]["name"][$i]." - NO ES UN FORMATO VALIDO DE ARCHIVO";    
                                            }
                                    }
                        
                            }
                    else
                        {
                            //echo "<br>No se ha subido ninguna imagen";
                            }
                    }                    
            }            
    
    if(isset($_FILES["archivo"]))
        {
            //Si el usuario ingreso un archivo valido para su ingreso.
            if(isset($_GET["rutaadjuntos"]))
                {
                    //Si se especifico la ruta para los archivos a subir.
                    $autoload = new subirArchivos($_GET["rutaadjuntos"].'/');
                    $autoload->uploader();
                    include_once($_SERVER['DOCUMENT_ROOT'].'/citadel/php/frontend/utilidades/archivos/subirArchivos.php');
                    }
            }
?>