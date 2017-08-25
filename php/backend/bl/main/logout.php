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

    class logout
        {
            /*
             * Esta clase controla los eventos de cierre de la sesion de usuario
             * en el sistema.
             */
            public function securelogout()
                {
                    /*
                     * Esta funcion genera el evento de cierre seguro, garantizando
                     * que seran destruidos los datos del usuario almacenados en la
                     * cookie del navegador.
                     */
                    session_name('citadel');
                    session_start();
                    session_unset();
                    session_destroy();
                    }
            }

    $objLogout = new logout();
    $objLogout->securelogout();
?>