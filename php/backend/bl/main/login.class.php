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

    class login
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la creacion
             * de la interfaz de login.
             */            
            public function __construct()
                {
                    //Declaracion de constructor (VACIO)
                    }
                    
            public function UILogin()
                {
                    /*
                     * Esta funcion crea la interfaz de usuario para el modulo de
                     * ingreso a la funcionalidad de sistema.
                     */
                    
                    $UILogin =  '<div id="logins" class= "login-div">
                                        <div id="login" class= "login">
                                            <h1 class="cabecera-login">.</h1>
                                            <div name = "login">
                                                <p><input id="usuario" required="required" type="text" value="" placeholder="Usuario o Email"/></p>
                                                <p><input id="clave" required="required" type="password" value="" placeholder="Contraseña"/></p>
                                                <button id="ingresar" class="button-blue" onclick="validarUsuario(\'./php/backend/bl/main/cargador.class.php\',\'?usuario=\'+document.getElementById(\'usuario\').value.toString()+\'&clave=\'+document.getElementById(\'clave\').value.toString(),\'escritorio\')"><img src="./img/menu/login.png" width="35" height="35"/>Iniciar Sesion</button>
                                            </div>
                                            <div class="ayuda-login">
                                                <p>¿Aun no tiene su cuenta?<a id= "linkCrear" href= "#">De click aqui para registrarse.</a>.</p>
                                                <p>¿Olvido su contraseña?<a id= "linkRecuperar" href= "#">De click aqui para recuperarla.</a>.</p>
                                            </div>
                                        </div>
                                </div>';
            
                    return $UILogin;
                    }
            }
?>