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
    header('Content-Type: text/html; charset=ISO-8859-1'); //Forzar la codificacion a ISO-8859-1.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/grid.class.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/empleados/empleados.class.php");
    
    class catEmpleados
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la creacion de la interfaz
             * del catalogo de empleados.
             */
           
            private $Paterno = "";
            private $Materno = "";
            private $Nombre = "";
            private $curp = "";
            private $rfc = "";
            private $Inicio = 0;
            private $Pagina = 1;
            private $DisplayRow = 10;
                       
            public function __construct()
                {
                    /*
                     * Esta funcion constructor, valida los datos recibidos por medio
                     * de la URL.
                     */
                    if(isset($_GET['buspaterno']))
                        {
                            $this->Paterno = $_GET['buspaterno'];
                            }
                            
                    if(isset($_GET['busmaterno']))
                        {
                            $this->Materno = $_GET['busmaterno']; 
                            }

                    if(isset($_GET['busnombre']))
                        {
                            $this->Nombre = $_GET['busnombre'];
                            }

                    if(isset($_GET['buscurp']))
                        {
                            $this->curp = $_GET['buscurp'];
                            }

                    if(isset($_GET['busrfc']))
                        {
                            $this->rfc = $_GET['busrfc'];
                            }
                            
                    if(isset($_GET['pagina']))
                        {
                            //Se proporciona referencia de pagina a mostrar.
                            $this->Pagina = $_GET['pagina'];
                            $this->Inicio = ($this->Pagina-1)*$this->DisplayRow;
                            }
                    else
                        {
                            //En caso de no ser proporcionada la pagina.
                            $this->Inicio = 0;
                            $this->Pagina = 1;
                            }                                                        
                    }

            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML de la interfaz grafica
                     * del catalogo de empleados.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $objEmpleados = new empleados();

                    $objEmpleados->setCatParametros($this->Paterno, $this->Materno, $this->Nombre, $this->curp, $this->rfc);                    
                    $Consulta = $objEmpleados->getConsulta().$objEmpleados->evaluaCondicion()." limit ".$this->Inicio.",".$this->DisplayRow;

                    $dsEmpleados = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    $objGridEmpleados = new myGrid($dsEmpleados, 'Catalogo de Empleados', $objEmpleados->getSufijo(), 'idEmpleado');

                    echo'
                            <html>
                                <head>
                                </head>
                                <body>';
                    
                                    echo $objGridEmpleados->headerTable();
                                    echo $objGridEmpleados->bodyTable();
                    
                    echo'
                                </body>
                            </html>';                    
                    }                    
            }
            
    $objCatEmpleados = new catEmpleados();
    $objCatEmpleados->drawUI();            
?>