/*********************************************************************************************
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del *
 * catálogo de usuarios en el sistema, creacion de usuario como invitado y los controles de  *
 * validacion contra scripts de automatizacion (captcha)                                     *
 *********************************************************************************************/

    function guardarUsuario(url,parametro)
        {
            /*
             * Esta función valida que los datos para ser almacenados en el registro sean correctos.
             */	
            var error= 0;
		
            if(document.getElementById("Usuario").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;			
                    }
		
            if(document.getElementById("Clave").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;
                    }
		
            if(document.getElementById("Correo").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;			
                    }
            
            if(!validarCorreo(document.getElementById("Correo").value.toString()))
            	{
                	//En caso de no ocurrir un error de validación, se asigna el valor de paso.
                	error = error+1;			            	
            		}
            
            if(!parametro.includes('&view=9'))
            	{
            		//CASO: SOLICITUD PROCESADA DESDE UN USUARIO EN SISTEMA.
            		if(document.getElementById("idNivel").value.toString() == "-1")
            			{
            				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
            				error = error+1;			
            				}
            		}
                    	
            if(error > 0)
                {
                    /*
                     * En caso de ocurrir un error de validación, se notifica al usuario.
                     */
                    bootbox.alert("Existen campos pendientes por completar");
			         }
	       else
                {
                    /*
                     * En caso que la validación de campos sea satisfactoria.
                     */
                    cargar(url,parametro,'sandbox');		
                    }
            }

    function modulosID()
		{
			/*
			 * Esta función obtiene los ID de los modulos seleccionados
			 * por el usuario.
			 */    	
			var checkboxes = $('.check');
			var temp = '-1'; 

			for (var x=0; x < checkboxes.length; x++) 
				{
					if (checkboxes[x].checked) 
						{
							temp = temp + 'B' + checkboxes[x].value.toString();
							}
					}
			return temp;
			}
	
    function nonModulosID()
		{
			/*
			 * Esta función obtiene los ID de los modulos no seleccionados
			 * por el usuario.
			 */    
			var checkboxes = $('.check');
			var temp = '-1'; 
		
			for (var x=0; x < checkboxes.length; x++) 
				{
					if (!checkboxes[x].checked) 
						{
							temp = temp + 'B' + checkboxes[x].value.toString();
							}
					}
		
			return temp;
			}

    function validarCorreo(correo) 
		{
			/*
			 * Esta funcion evalua una cadena de texto apartir de una expresion regular
			 * y verifica si el formato es consistente con una direccion de correo.
			 */
    		expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    		return expr.test(correo);        		
			}
    
    function habUsuario()
		{
			/*
			 * Esta función habilita los controles del formulario de usuario.
			 */
			var checkboxes = $('.check');
	
			for (var x=0; x < checkboxes.length; x++)
				{
					checkboxes[x].disabled = false;
					}
		
			document.getElementById('Usuario').disabled = false;
			document.getElementById('Clave').disabled = false;
			document.getElementById('Correo').disabled = false;
			document.getElementById('Pregunta').disabled = false;
			document.getElementById('Respuesta').disabled = false;
			document.getElementById('idNivel').disabled = false;
			document.getElementById('usr_Guardar').style.display="block";
			document.getElementById('usr_Borrar').style.display="none";
			document.getElementById('usr_Editar').style.display="none";
			}

//DECLARACION DE FUNCIONES A EJECUTARSE SOBRE FORMULARIO DE CATALOGO.    
/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
    $(document).ready(function()
    	{
        	$("div").click(function(e)
        		{
        			e.stopPropagation();
        			if(e.target.id.substring(0,10) == "usr_buscar")
        				{
        					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
        					document.getElementById('pgusuario').value = document.getElementById('bususuario').value.toString();
        					document.getElementById('pgcorrusuario').value = document.getElementById('buscorreo').value.toString();            	
        					cargar('./php/frontend/usuarios/sysadmin/catUsuarios.php','?bususuario='+document.getElementById('bususuario').value.toString()+'&buscorreo='+document.getElementById('buscorreo').value.toString(),'busRes');
        					}
        			});                 
    		});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id delete_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
    $(document).ready(function()
    	{
        	$("div").click(function(e)
        		{
        			e.stopPropagation();
        			if(e.target.id.substring(0,10) == "usr_delete")
        				{
        					//En caso de coincidir el id con la accion delete.
	            			bootbox.confirm(
    			            	{
    				            	message: "¿Confirma que desea borrar el registro?",
    				            	buttons: 
    				            		{
    				            			confirm: 
    				            				{
    				            					label: 'SI',
    				            					className: 'btn-success'
    				            					},
    				            			cancel: 
    				            				{
    				            					label: 'NO',
    				            					className: 'btn-danger'
    				            					}
    				            			},
    				            	callback: function (result)
    				            		{
    				            			if(result)
    				            				{
    				            					//EL USUARIO DECIDE BORRAR EL REGISTRO.
    				            					cargar('./php/backend/dal/usuarios/dalUsuarios.class.php','?id='+e.target.id.substring(11)+'&accion=EdRS','sandbox');
    				            					}			            					
    				            			}
    			            		});
        					}
        			});                 
    		});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id add_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
    $(document).ready(function()
    	{
        	$("div").click(function(e)
        		{
        			e.stopPropagation();
        			if(e.target.id.substring(0,7) == "usr_add")
        				{
        					//En caso de coincidir el id con la accion agregar.
        					cargar('./php/frontend/usuarios/sysadmin/opUsuarios.php','?id=-1&view=0','sandbox');
        					}
        			});                 
    		});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id visualizar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
    $(document).ready(function()
    	{
        	$("div").click(function(e)
        		{
        			e.stopPropagation();
        			if(e.target.id.substring(0,14) == "usr_visualizar")
        				{
        					//En caso de coincidir el id con la accion visualizar.
        					cargar('./php/frontend/usuarios/sysadmin/opUsuarios.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
        					}
        			});                 
    		});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id edit_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
    $(document).ready(function()
    	{
        	$("div").click(function(e)
        		{
        			e.stopPropagation();
        			if(e.target.id.substring(0,8) == "usr_edit")
        				{
        					//En caso de coincidir el id con la accion editar.
        					cargar('./php/frontend/usuarios/sysadmin/opUsuarios.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
        					}
        			});                 
    		});

/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de retroceso de pagina
 * sobre el grid de datos.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    				e.stopPropagation();
    				if(e.target.id == "usr_Previous_10")
    					{
    						//En caso de coincidir con el control de retroceso.
    						if((document.getElementById('pagina').value-1)!=0)
    							{
    								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
    								}							
    						cargar('./php/frontend/usuarios/sysadmin/catUsuarios.php','?bususuario='+document.getElementById('pgusuario').value.toString()+'&buscorreo='+document.getElementById('pgcorrusuario').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
    						}
    				});                 
			});

/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de avance de pagina
 * sobre el grid de datos.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    				e.stopPropagation();
    				if(e.target.id == "usr_Next_10")
    					{
    						//En caso de coincidir con el control de avance.
    						document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
    						cargar('./php/frontend/usuarios/sysadmin/catUsuarios.php','?bususuario='+document.getElementById('pgusuario').value.toString()+'&buscorreo='+document.getElementById('pgcorrusuario').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
    						}
    				});                 
			});

//DECLARACION DE ACCIONES A EJECUTARSE SOBRE FORMULARIO OPERATIVO.
/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de retorno
 * pulsado sobre el formulario operativo.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    		     	e.stopPropagation();
    		        if(e.target.id == "usr_Volver")
    		        	{
    		            	//En caso de coincidir el id con la accion volver.
    		            	cargar('./php/frontend/usuarios/sysadmin/busUsuarios.php','','sandbox');
    		            	}
    				});                 
			});
    		
/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de borrado
 * pulsado sobre el formulario operativo.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    			 	e.stopPropagation();
    			    if(e.target.id == "usr_Borrar")
    			    	{
    			         	//En caso de coincidir el id con la accion borrar.
    			            bootbox.confirm(
    			            	{
    				            	message: "¿Confirma que desea borrar el registro?",
    				            	buttons: 
    				            		{
    				            			confirm: 
    				            				{
    				            					label: 'SI',
    				            					className: 'btn-success'
    				            					},
    				            			cancel: 
    				            				{
    				            					label: 'NO',
    				            					className: 'btn-danger'
    				            					}
    				            			},
    				            	callback: function (result)
    				            		{
    				            			if(result)
    				            				{
    				            					//EL USUARIO DECIDE BORRAR EL REGISTRO.
    				            					cargar('./php/backend/dal/usuarios/dalUsuarios.class.php','?id='+document.getElementById('idUsuario').value.toString()+'&accion=EdRS','sandbox');
    				            					}			            					
    				            			}
    			            		});
    			    		}
    				});                 
			});

/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de guardado
 * pulsado sobre el formulario operativo.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    				e.stopPropagation();
    				if(e.target.id == "usr_Guardar")
    					{
    				     	//En caso de coincidir el id con la accion guardar.
    				        bootbox.confirm(
    				        	{
    				            	message: "¿Confirma que desea almacenar los cambios?",
    				            	buttons: 
    				            		{
    				            			confirm: 
    				            				{
    				            					label: 'SI',
    				            					className: 'btn-success'
    				            					},
    				            			cancel: 
    				            				{
    				            					label: 'NO',
    				            					className: 'btn-danger'
    				            					}
    				            			},
    				            	callback: function (result)
    				            		{
    				            			if(result)
    				            				{
    				            					//EL USUARIO DECIDE ALMACENAR LOS DATOS.
    				            					guardarUsuario('./php/backend/dal/usuarios/dalUsuarios.class.php','?id='+document.getElementById('idUsuario').value.toString()+'&usuario='+document.getElementById('Usuario').value.toString()+'&clave='+document.getElementById('Clave').value.toString()+'&correo='+document.getElementById('Correo').value.toString()+'&idnivel='+document.getElementById('idNivel').value.toString()+'&pregunta='+document.getElementById('Pregunta').value.toString()+'&respuesta='+document.getElementById('Respuesta').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&nonmod='+nonModulosID()+'&mod='+modulosID()+'&accion=CoER');
    				            					}			            					
    				            			}
    				        		});			        		
    						}
    				});                 
			});

/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de edicion
 * pulsado sobre el formulario operativo.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    				e.stopPropagation();
    				if(e.target.id == "usr_Editar")
    					{
    				     	//En caso de coincidir el id con la accion edicion.
    				        habUsuario();
    						}
    				});                 
			});

//DECLARACION DE FUNCIONES A EJECUTARSE SOBRE FORMULARIO DE SOLICITUD DE CREACION DE USUARIO (INVITADO).
    		
/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de guardado
 * pulsado sobre el formulario operativo.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    				e.stopPropagation();
    				if(e.target.id == "scu_Guardar")
    					{
    					 	//En caso de coincidir el id con la accion guardar.
    					    bootbox.confirm(
    					    	{
    					    		message: "¿Confirma que desea almacenar los cambios?",
    					            buttons: 
    					            	{
    					            		confirm: 
    					            			{
    					            				label: 'SI',
    					            				className: 'btn-success'
    					            				},
    					            		cancel: 
    					            			{
    					            				label: 'NO',
    					            				className: 'btn-danger'
    					            				}
    					            		},
    					            callback: function (result)
    					            	{
    					            		if(result)
    					            			{
    					            				//EL USUARIO DECIDE ALMACENAR LOS DATOS.
    					            				guardarUsuario('./php/backend/dal/usuarios/dalUsuarios.class.php','?id='+document.getElementById('idUsuario').value.toString()+'&usuario='+document.getElementById('Usuario').value.toString()+'&clave='+document.getElementById('Clave').value.toString()+'&correo='+document.getElementById('Correo').value.toString()+'&pregunta='+document.getElementById('Pregunta').value.toString()+'&respuesta='+document.getElementById('Respuesta').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&captcha='+document.getElementById('captcha_code').value.toString().toUpperCase()+'&paterno='+document.getElementById('Paterno').value.toString()+'&materno='+document.getElementById('Materno').value.toString()+'&nombre='+document.getElementById('Nombre').value.toString()+'&fnacimiento='+document.getElementById('fNacimiento').value.toString()+'&curp='+document.getElementById('curp').value.toString()+'&telfijo='+document.getElementById('telFijo').value.toString()+'&telcel='+document.getElementById('telCel').value.toString()+'&accion=CoER&view=9');
    					            				}			            					
    					            		}
    					    		});			        		
    						}
    				});                 
			});

/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de retorno
 * pulsado sobre el formulario operativo.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    				e.stopPropagation();
    				if(e.target.id == "scu_Volver")
    					{
    				     	//En caso de coincidir el id con la accion volver.
    				        cargar('./php/frontend/main/login.php','','sandbox');
    				        }
    				});                 
			});
    		
/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento refresh
 * pulsado sobre el formulario operativo.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    				e.stopPropagation();
    				if(e.target.id == "rfrCaptcha")
    					{
    				     	//En caso de coincidir el id con la accion volver.
    				        cargar('./php/frontend/utilidades/captcha/opCaptcha.php','','captcha-controll');
    						}
    				});                 
			});	