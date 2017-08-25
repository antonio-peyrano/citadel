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

/*********************************************************************************************************
 * Archivo: myGrid.class.php * Descripcion: Clase que contiene el codigo para la creacion de objetos     *
 *                           * correspondientes a rejillas de datos, para la visualizacion e interaccion *
 *                           * con elementos de una base de datos.                                       *
 *********************************************************************************************************
 * Desarrollador: Mtro. Jesus Antonio Peyrano Luna * Ultima modificacion: 27/09/2016                     *                            
 *********************************************************************************************************/
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/main/usrctrl.class.php"); //Se carga la referencia de clase para control de accesos.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/dal/main/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    
    class myGrid
        {
            /*
             * Esta clase contiene la definicion de metodos para una
             * rejilla de datos.
             */
            private $totColumn = null;
            private $titulo = null; //Variable de gestion para el titulo a visualizar en la tabla.
            private $sufijo = null; //Variable de identificador auxiliar para el control de items manipulables por el usuario.
            
            private $dataset = null; //Objeto de gestion para la tupla de datos.
            private $idEntity = null; //Variable de gestion para el ID de control de los registros en la tupla.
            private $DisplayRow = 10; //Cantidad de registros a observar.
             
            function __construct($dataset, $titulo, $sufijo, $idEntity)
                {
                    /*
                     * Definicion de constructor para la clase rejilla,
                     * se ejecuta la inicialización de atributos.
                     */
                    $this->dataset = $dataset;
                    $this->titulo = $titulo;
                    $this->sufijo = $sufijo;
                    $this->idEntity = $idEntity;
                    }

            public function controles($tupla)
                {
                    /*
                     * Esta funcion establece los controles que se visualizaran para el usuario
                     * a razon del perfil de acceso con el que cuenta.
                     * Lector = [Visualizacion]
                     * Administrador = [Visualizacion/Edicion/Eliminacion]
                     */
                    $HTML = "";
                    
                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('citadel');
                            session_start();
                            }
                            
                    if($_SESSION['nivel'] == "Lector")
                        {
                            /*
                             * Si el usuario cuenta con un perfil de lector, se crea la referencia
                             * para el control de solo visualizacion.
                             */
                            $HTML = '<td id="reg_'.$tupla[$this->idEntity].'" width= "90"><img id="'.$this->sufijo.'visualizar_'.$tupla[$this->idEntity].'"align= "middle" src= "./img/grids/view.png" width= "25" height= "25" alt= "Visualizar"  title= "Visualizar"/></td></tr>';        
                            }
                    else
                        {
                            if($_SESSION['nivel'] == "Administrador")
                                {
                                    $HTML = '<td id="reg_'.$tupla[$this->idEntity].'" width= "90"><img id="'.$this->sufijo.'visualizar_'.$tupla[$this->idEntity].'"align= "middle" src= "./img/grids/view.png" width= "25" height= "25" alt= "Visualizar" title= "Visualizar"/><img id="'.$this->sufijo.'edit_'.$tupla[$this->idEntity].'"align= "middle" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" title= "Editar"/><a class="borrar" id="'.$this->sufijo.'delete'.$tupla[$this->idEntity].'" href="#"><img id="'.$this->sufijo.'delete_'.$tupla[$this->idEntity].'" align= "middle" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" title= "Borrar"/></a></td></tr>';
                                    }
                            }
                            
                    return $HTML;                            
                    } 
                                       
            function headerTable()
                {
                    /*
                     * Esta funcion crea la estructura automatizada de codigo HTML
                     * para la cabecera de la rejilla de datos.
                     */
                    
                    $colHeader = '<th style="display:none">';
                    $field = mysqli_fetch_field($this->dataset); //Se realiza el desplazamiento al siguiente elemento de la coleccion.
                    $colHeader= $colHeader.$field->name.'</th>'; //Se obtiene el primer nombre de campo para la tabla.
                    $field = mysqli_fetch_field($this->dataset); //Se realiza el desplazamiento al siguiente elemento de la coleccion.
                    $this->totColumn = 1;
                    
                    while($field)
                        {
                            /*
                             * Mientras existan columnas dentro de la tupla,
                             * se ejecuta un ciclo para crear la cadena de codigo
                             * correspondiente a los campos de la cabecera.
                             */
                            $colHeader = $colHeader.'<th>'.$field->name.'</th>';
                            $field = mysqli_fetch_field($this->dataset); //Se realiza el desplazamiento al siguiente elemento de la coleccion.
                            $this->totColumn = $this->totColumn+1;
                            }

                    /*
                     * Se crea el codigo HTML de la cabecera de tabla,
                     * concatenando los nombres de campo obtenidos.
                     */       
                    $response = '<div id= "dgDiv" class= "dgMainDiv">';
                    $response = $response.'<table class="dgTable">';
                    $response = $response.'<tr align= "center"><td colspan= '.$this->totColumn.' class= "dgHeader">'.$this->titulo.'</td></tr>';
                    $response = $response.'<tr class="dgTitles">'.$colHeader.'<th>Acciones<img id="'.$this->sufijo.'add" align= "right" src= "./img/grids/add.png" width= "25" height= "25" alt="Agregar"/></th></tr>';                            
                    return $response;                    
                    }

            function bodyTable()
                {
                    /*
                     * Esta funcion crea la estructura automatizada de codigo HTML
                     * para el cuerpo de la rejilla de datos.
                     */
                    $tupla = @mysqli_fetch_array($this->dataset,MYSQLI_ASSOC); //Se organiza la tupla para su manipulacion, haciendo un corrimiento al siguiente elemento.
                    $rowCount = 1; //Se inicializa la variable de conteo de filas para su despliegue como información.                    
                    $totRows = mysqli_num_rows($this->dataset);
                    $response = null; //Se inicializa la cadena de codigo HTML.
                    
                    while($tupla)
                        {
                            /*
                             * Mientras existan elementos dentro de la coleccion de datos,
                             * se procede a un ciclo de recorrido para generar la cadena de codigo.
                             */
                            if(($rowCount % 2)==0)
                                {
                                    //En caso de ser una fila de datos non, se propone el color base.
                                    $response = $response.'<tr class="dgRowsnormTR">';
                                    }
                            else
                                {
                                    //En caso de ser una fila par, se propone el color alterno.
                                    $response = $response.'<tr class="dgRowsaltTR">';
                                    }
                
                            $display = 1; //Variable de control para la visualizacion de la columna de ID.
                                    
                            foreach ($tupla as $value)
                                {
                                    /*
                                     * Para cada elemento en el arreglo, se dispone de una casilla en la
                                     * tabla.
                                     */                                    
                                    if($display == 1)
                                        {
                                            /*
                                             * Si el valor corresponde a uno, la visualizacion del campo de ID no se efectua.
                                             */
                                            $response = $response.'<td style= "display:none">'.$value.'</td>';
                                            $display = 0; //Se cambia el valor de control para las siguientes interacciones.
                                            }
                                    else
                                        {
                                            /*
                                             * En caso que el valor corresponda a cero, se visualizan los elementos en la rejilla
                                             * de datos.
                                             */
                                            $response = $response.'<td>'.$value.'</td>';
                                            }                                                 
                                    }
                        
                            $response = $response.$this->controles($tupla);
                            $rowCount = $rowCount + 1; //Se incrementa el contador de filas.     
                            $tupla = @mysqli_fetch_array($this->dataset,MYSQLI_ASSOC); //Se organiza la tupla para su manipulacion, haciendo un corrimiento al siguiente elemento.
                            }
                            
                    if($totRows == 0)
                        {
                            $response = '<tr class="dgRowsnormTR" align="center"><td colspan= "'.$this->totColumn.'"><b>NO SE LOCALIZARON REGISTROS</b></td></tr>';
                            }

                    /*
                     * Se concatenan los elementos restantes de la cadena de codigo para dar el formato a la
                     * tabla en pantalla.
                     */
                    $response = $response.'<tr class= "dgTotRowsTR"><td alignt= "left" colspan= '.$this->totColumn.'"><img id="'.$this->sufijo.'add" align= "right" src= "./img/grids/add.png" width= "25" height= "25" alt= "Agregar"/></td></tr>';
                    $response = $response.'<tr class= "dgPagRow"><td align= "left" colspan= "'.$this->totColumn.'">Visualizando ' .($rowCount-1). ' registros <img  align="right" id="'.$this->sufijo.'Previous_'.$this->DisplayRow.'" src="./img/grids/previous.png" width="25" height="25" title="Previos '.$this->DisplayRow.'"><img align="right" id="'.$this->sufijo.'Next_'.$this->DisplayRow.'"src="./img/grids/next.png" width="25" height="25" title="Siguientes '.$this->DisplayRow.'"></td></tr>';
                    $response = $response.'</table>';
                    $response = $response.'</div>';                                          
                    return $response;
                    }                    
            }
?>