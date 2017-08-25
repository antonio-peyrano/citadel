/*************************************************************************************
 * Esta libreria de scripts contiene las funcionalidades de control del lado cliente *
 * sobre la interfaz de ingreso al sistema.                                          *
 *************************************************************************************/

	function validarUsuario(URL,Parametro,Target)
		{
			/*
			 * Esta funcion ejecuta la carga de los elementos que corresponden
			 * al modulo solicitado por el usuario y su perfil para visualizacion.
			 */
			cargarSync(URL,Parametro,Target);
			cargarSync('./php/backend/bl/main/profile.class.php',Parametro,'profile');
			}

	/*
	 * El presente segmento de codigo evalua la accion de click sobre el boton de retorno
	 * a la ventana de login.
	 */
	$(document).ready(function()
		{
	    	$("div").click(function(e)
	    		{
	    			e.stopPropagation();
	    			if(e.target.id == "rtnlogin")
	    				{
	    					//Se confirma el retorno a la ventana de login.
	    					cargar('./php/frontend/main/login.php','','sandbox');
	    					}
	    			});                 
			});
	
	/*
	 * El presente segmento de codigo evalua la accion de click sobre el link que redirige
	 * al formulario de recuperacion de datos de cuenta.
	 */
	$(document).ready(function()
		{
	    	$("div").click(function(e)
	    		{
	    			e.stopPropagation();
	    			if(e.target.id == "linkRecuperar")
	    				{
	    					//Se confirma el direccionamiento al recordatorio de clave.
	    					cargar('./php/frontend/usuarios/recuperar/catRecordarClave.php','','sandbox');
	    					}
	    			});                 
			});

	/*
	 * El presente segmento de codigo evalua la accion de click sobre el link que redirige
	 * al formulario de creacion de datos de cuenta.
	 */
	$(document).ready(function()
		{
	    	$("div").click(function(e)
	    		{
	    			e.stopPropagation();
	    			if(e.target.id == "linkCrear")
	    				{
	    					//Se confirma el direccionamiento al recordatorio de clave.
	    					cargar('./php/frontend/usuarios/guest/opUsuarios.php','?view=9','sandbox');
	    					}
	    			});                 
			});
	
	/*
	 * El presente segmento de codigo evalua la accion de click sobre el boton de busqueda de
	 * pregunta secreta asociada a la cuenta.
	 */
	$(document).ready(function()
		{
	    	$("div").click(function(e)
	    		{
	    			e.stopPropagation();
	    			if(e.target.id == "btnBusPregunta")
	    				{
	    					//Se carga el componente input de pregunta.
	    					if(validarCorreo(document.getElementById('Correo').value.toString()))
	    						{
	    							//EL CORREO CORRESPONDE A UN FORMATO DE EXPRESION REGULAR VALIDO.
	    							cargar('./php/frontend/usuarios/recuperar/comp/opInputPregunta.php','?correo='+document.getElementById('Correo').value.toString(),'divPregunta');
	    							}
	    					else
	    						{
	    							//EL CORREO NO CONTIENE UNA FORMA REGULAR VALIDA.
	    							bootbox.alert("La direccion de correo proporcionada no tiene un formato valio.");
	    							}
	    					}
	    			});                 
			});
	
	/*
	 * El presente segmento de codigo evalua la accion de click sobre el boton de envio de solicitud
	 * de recuperacion de datos asociados a la cuenta.
	 */
	$(document).ready(function()
		{
	    	$("div").click(function(e)
	    		{
	    			e.stopPropagation();
	    			if(e.target.id == "btnEnviarRecordatorio")
	    				{
	    					//Se carga el componente input de pregunta.
	    					var cntError = 0;
	    			
	    					if(document.getElementById('Correo').value.toString() == ""){cntError+=1;}
	    					if(document.getElementById('Pregunta').value.toString() == ""){cntError+=1;}
	    					if(document.getElementById('Respuesta').value.toString() == ""){cntError+=1;}
	    			
	    					if(cntError!=0)
	    						{
	    							//SE NOTIFICA AL USUARIO SOBRE LOS PARAMETROS FALTANTES.
	    							bootbox.alert("Existen datos pendientes de cubrir.");
	    							}
	    					else
	    						{
	    							//LOS PARAMETROS NECESARIOS FUERON OBTENIDOS.
	    							cargar('./php/frontend/usuarios/recuperar/opRecordarClave.php','?correo='+document.getElementById('Correo').value.toString()+'&pregunta='+document.getElementById('Pregunta').value.toString()+'&respuesta='+document.getElementById('Respuesta').value.toString(),'sandbox');
	    							}	    			
	    				}
	    		});                 
			});