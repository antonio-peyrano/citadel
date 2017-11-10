/*********************************************************************************************
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del *
 * catálogo de solicitudes en el sistema.                                                    *
 *********************************************************************************************/

    function guardarSolicitud(url,parametro)
        {
            /*
             * Esta función valida que los datos para ser almacenados en el registro sean correctos.
             */	
            var error= 0;
		
            if(document.getElementById("Folio").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;			
                    }
		
            if(document.getElementById("Asunto").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;
                    }
		
            if(document.getElementById("Detalle").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;			
                    }
                        
            if(document.getElementById("fRegistro").value.toString() == "")
            	{
                	//En caso de no ocurrir un error de validación, se asigna el valor de paso.
                	error = error+1;			            	
            		}

            if(document.getElementById("idEntidad").value.toString() == "-1")
        		{
            		//En caso de no ocurrir un error de validación, se asigna el valor de paso.
            		error = error+1;			            	
        			}
            
            if(document.getElementById("captcha_code").value.toString() == "")
        		{
            		//En caso de no ocurrir un error de validación, se asigna el valor de paso.
            		error = error+1;			            	
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
	    	   		parametro = parametro.replace(/\n/g,"<br>"); //Se cambian los saltos de linea por el tag <br>.
                    cargar(url,parametro,'sandbox');		
                    }
            }
    
    function habSolicitud()
		{
			/*
			 * Esta función habilita los controles del formulario de solicitud.
			 */
			document.getElementById('Asunto').disabled = false;
			document.getElementById('Detalle').disabled = false;
			document.getElementById('Status').disabled = false;
			document.getElementById('idEntidad').disabled = false;
			document.getElementById('sol_Guardar').style.display="block";
			document.getElementById('sol_Adjuntar').style.display="block";									
			document.getElementById('sol_ImprimirPDF').style.display="block";
			document.getElementById('sol_Borrar').style.display="none";
			document.getElementById('sol_Editar').style.display="none";
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
        			if(e.target.id.substring(0,10) == "sol_buscar")
        				{
        					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
        					document.getElementById('pgfolio').value = document.getElementById('busfolio').value.toString();
        					document.getElementById('pgasunto').value = document.getElementById('busasunto').value.toString();
        					document.getElementById('pgidusuario').value = document.getElementById('busidusuario').value.toString();
        					document.getElementById('pgfregistro').value = document.getElementById('busfregistro').value.toString();
        					cargar('./php/frontend/solicitudes/catSolicitudes.php','?busfolio='+document.getElementById('busfolio').value.toString()+'&busasunto='+document.getElementById('busasunto').value.toString()+'&busidusuario='+document.getElementById('busidusuario').value.toString()+'&busfregistro='+document.getElementById('busfregistro').value.toString(),'busRes');
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
        			if(e.target.id.substring(0,10) == "sol_delete")
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
    				            					cargar('./php/backend/dal/solicitudes/dalSolicitudes.class.php','?id='+e.target.id.substring(11)+'&accion=EdRS','sandbox');
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
        			if(e.target.id.substring(0,7) == "sol_add")
        				{
        					//En caso de coincidir el id con la accion agregar.        					
        					cargar('./php/frontend/solicitudes/opSolicitudes.php','?id=-1&view=0','sandbox');
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
        			if(e.target.id.substring(0,14) == "sol_visualizar")
        				{
        					//En caso de coincidir el id con la accion visualizar.
        					cargar('./php/frontend/solicitudes/opSolicitudes.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
        			if(e.target.id.substring(0,8) == "sol_edit")
        				{
        					//En caso de coincidir el id con la accion editar.
        					cargar('./php/frontend/solicitudes/opSolicitudes.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
    				if(e.target.id == "sol_Previous_10")
    					{
    						//En caso de coincidir con el control de retroceso.
    						if((document.getElementById('pagina').value-1)!=0)
    							{
    								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
    								}							
    						cargar('./php/frontend/solicitudes/catSolicitudes.php','?busfolio='+document.getElementById('pgfolio').value.toString()+'&busasunto='+document.getElementById('pgasunto').value.toString()+'&busidusuario='+document.getElementById('pgidusuario').value.toString()+'&busfregistro='+document.getElementById('pgfregistro').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    				if(e.target.id == "sol_Next_10")
    					{
    						//En caso de coincidir con el control de avance.
    						document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
    						cargar('./php/frontend/solicitudes/catSolicitudes.php','?busfolio='+document.getElementById('pgfolio').value.toString()+'&busasunto='+document.getElementById('pgasunto').value.toString()+'&busidusuario='+document.getElementById('pgidusuario').value.toString()+'&busfregistro='+document.getElementById('pgfregistro').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
    						}
    				});                 
			});

//DECLARACION DE ACCIONES A EJECUTARSE SOBRE FORMULARIO OPERATIVO.

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id edit_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
    $(document).ready(function()
    	{
        	$("div").click(function(e)
        		{
        			e.stopPropagation();
        			if(e.target.id == "sol_ImprimirPDF")
        				{
        					//En caso de coincidir el id con la accion editar.
    	    				var visualizar = '';
    	    				var w = 200;
    	    				var h = 100;
    	    			
    	    				var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    	    				var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
    	    			
    	    		    	var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    	    		    	var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    	    		    	var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    	    		    	var top = ((height / 2) - (h / 2)) + dualScreenTop;
    	    		    	detalle = document.getElementById('Detalle').value.toString();
    	    		    	detalle = detalle.replace(/\n/g,"<br>");
        					window.open('./php/backend/dal/utilidades/reportes/repoDocSolicitud.class.php?folio='+document.getElementById('Folio').value.toString()+'&fregistro='+document.getElementById('fRegistro').value.toString()+'&asunto='+document.getElementById('Asunto').value.toString()+'&identidad='+document.getElementById('idEntidad').value.toString()+'&detalle='+detalle+'&idusuario='+document.getElementById('idUsuario').value.toString()+'&status='+document.getElementById('Status').value.toString(), "Formato para Impresion", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, toolbar=yes, tittlebar=no, width="+width.toString()+", height="+height.toString()+", top="+top.toString()+", left="+left.toString());
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
    		        if(e.target.id == "sol_Volver")
    		        	{
    		            	//En caso de coincidir el id con la accion volver.
    		            	cargar('./php/frontend/solicitudes/busSolicitudes.php','','sandbox');
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
    			    if(e.target.id == "sol_Borrar")
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
    				            					cargar('./php/backend/dal/solicitudes/dalSolicitudes.class.php','?id='+document.getElementById('idSolicitud').value.toString()+'&accion=EdRS','sandbox');
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
    				if(e.target.id == "sol_Guardar")
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
    				            					guardarSolicitud('./php/backend/dal/solicitudes/dalSolicitudes.class.php','?id='+document.getElementById('idSolicitud').value.toString()+'&folio='+document.getElementById('Folio').value.toString()+'&asunto='+document.getElementById('Asunto').value.toString()+'&detalle='+document.getElementById('Detalle').value.toString()+'&fregistro='+document.getElementById('fRegistro').value.toString()+'&idusuario='+document.getElementById('idUsuario').value.toString()+'&identidad='+document.getElementById('idEntidad').value.toString()+'&captcha='+document.getElementById('captcha_code').value.toString().toUpperCase()+'&status='+document.getElementById('Status').value.toString()+'&accion=CoER');
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
    				if(e.target.id == "sol_Editar")
    					{
    				     	//En caso de coincidir el id con la accion edicion.
    				        habSolicitud();
    						}
    				});                 
			});
	
	$(document).ready(function() {
	    $("div").click(function(e){
	    	e.stopPropagation();
	    	if(e.target.id == "sol_Adjuntar")
	    		{
	    			//Se confirma la carga de actualizacion sobre archivos adjuntos.
	    			var visualizar = '';
	    			var w = 200;
	    			var h = 100;
	    			
	    			var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
	    			var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
	    			
	    		    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
	    		    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

	    		    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
	    		    var top = ((height / 2) - (h / 2)) + dualScreenTop;
	    		    
	    		    if(document.getElementById('sol_Guardar').style.display == "none")
	    		    	{
	    		    		//Registro en modo visualizacion.
	    		    		visualizar='&visualizar=1';
	    		    		}
	    		    else
	    		    	{
	    		    		//Registro en modo de edicion.
	    		    		visualizar='&visualizar=0';
	    		    		}
	    		    
	    			window.open('./php/frontend/utilidades/archivos/subirArchivos.php?rutaadjuntos='+document.getElementById('Folio').value.toString()+visualizar, "Subir Archivos", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, toolbar=yes, tittlebar=no, width="+width.toString()+", height="+height.toString()+", top="+top.toString()+", left="+left.toString());    			
	    			}
	    });                 
	});	