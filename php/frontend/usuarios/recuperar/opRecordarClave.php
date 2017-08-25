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
    include_once ($_SERVER['DOCUMENT_ROOT']."/citadel/php/backend/bl/utilidades/mail.class.php"); //Se carga la referencia del codificador de cadenas.    
    
    class oprecordarclave
        {
            /*
             * Esta clase contiene los atributos y procedimientos para generar
             * la interfaz secundaria de recuperacion de claves.
             */
            private $Correo = '';
            private $Pregunta = '';
            private $Respuesta = '';
            
            public function __construct()
                {
                    /*
                     * Este constructor inicializa los atributos de la clase por medio
                     * de los datos obtenidos en la consulta por URL.
                     */
                    if(isset($_GET['correo'])){$this->Correo=$_GET['correo'];}
                    if(isset($_GET['pregunta'])){$this->Pregunta=$_GET['pregunta'];}
                    if(isset($_GET['respuesta'])){$this->Respuesta=$_GET['respuesta'];}
                    }
                    
            public function getCorreo()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de correo electronico.
                     */
                    return $this->Correo;
                    }

            public function getPregunta()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de pregunta.
                     */
                    return $this->Pregunta;
                    }
                    
            public function getRespuesta()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de respuesta.
                     */
                    return $this->Respuesta;
                    }                                        
            }

    $objOpRecordarClave = new oprecordarclave();
    $objMail = new mail(); 

    $objMail->getParamRecordarClave($objOpRecordarClave->getCorreo(), $objOpRecordarClave->getPregunta(), $objOpRecordarClave->getRespuesta());
    $objMail->buscarUsuario();
    
    if((!empty($objMail->getUsuario()))&&(!empty($objMail->getClave())))
        {
            //VALIDACION DE USUARIO EN SISTEMA.
            echo $objMail->enviarCorreo();
            }
    else
        {
            //FALLA EN LA VALIDACION DEL USUARIO EN SISTEMA.
            echo $objMail->msgErrorValidacion($objOpRecordarClave->getCorreo());
            }
?>