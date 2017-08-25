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

/**************************************************************************************************************
 * Archivo: codificador.class.php * Descripcion: Clase que contiene el codigo para la creacion de objetos     *
 *                           * correspondientes al manejo de encriptacion de datos entre la capa de usuario   *
 *                           * y el servidor de base de datos.                                                *
 **************************************************************************************************************
 * Desarrollador: Mtro. Jesus Antonio Peyrano Luna * Ultima modificacion: 27/09/2016                          *
 **************************************************************************************************************/

    class codificador
        {           
            function __construct(){}
            
            function decrypt($string, $key)
                {
                    /*
                     * Esta funcion ejecuta la decriptacion de una cadena propuesta por el usuario, apartir de
                     * una llave publica.
                     */
                    $result = '';
                    $string = base64_decode($string);
            
                    for($i=0; $i<strlen($string); $i++)
                        {
                            $char = substr($string, $i, 1);
                            $keychar = substr($key, ($i % strlen($key))-1, 1);
                            $char = chr(ord($char)-ord($keychar));
                            $result.=$char;
                            }
                    return $result;
                    }
                    
            function encrypt($string, $key)
                {
                    /*
                     * Esta funcion ejecuta la encriptacion de una cadena propuesta por el usuario, apartir de
                     * una llave publica.
                     */
                    $result = '';
                    
                    for($i=0; $i<strlen($string); $i++)
                        {
                            $char = substr($string, $i, 1);
                            $keychar = substr($key, ($i % strlen($key))-1, 1);
                            $char = chr(ord($char)+ord($keychar));
                            $result.=$char;
                            }
                    return base64_encode($result);
                    }            
            }                                      
?>