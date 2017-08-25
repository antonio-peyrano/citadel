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

    /*
     * Este es el archivo de configuracion principal del sistema, debe cargarse en cada modulo que requiera del uso
     * de las constantes predefinidas de ejecucion.
     */
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.
    
    /*
     * Para ejecucion en local quite las acotaciones de comentario.
     */

    $servername="397Y09vK3uXj";
    $dbname="1tjp09PH2w==";
    $username="5d7k5g==";
    $password="1OXW3t7Qpqmm";
    $SitioWeb="http://localhost/citadel/";
 /*   
    $servername='5uDhpJ+andTo2OPd4eLWpKDS4tw=';
    $dbname='1aTUo6WaoaejqaDU1djW0NbU3w==';
    $username='1aTUo6WaoaejqaA=';
    $password='1OXW3t7Qpqmm';
    $SitioWeb="http://peycom.byethost5.com/citadel/";
*/	    
/*
	$objCodificador=new codificador();
    echo $objCodificador->decrypt($servername,'ouroboros');
    echo $objCodificador->decrypt($dbname,'ouroboros');
    echo $objCodificador->decrypt($username,'ouroboros');
    echo $objCodificador->decrypt('1OXW3t7Q','ouroboros');
*/
?>
