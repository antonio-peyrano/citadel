/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento cerrarVentana
 * dentro del formulario de subida de archivos
 */

$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id == "cerrarVentana")
    		{
    			//Si el usuario confirma su solicitud de cerrar la ventana de gestion.  
    			window.close();
    			}
    });                 
});