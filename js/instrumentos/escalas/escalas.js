/*********************************************************************************************
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del *
 * catálogo de escalas en el sistema.                                                       *
 *********************************************************************************************/

    function guardarEscala(url,parametro)
        {
            /*
             * Esta función valida que los datos para ser almacenados en el registro sean correctos.
             */	
            var error= 0;
		
            if(document.getElementById("Escala").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;			
                    }

            if(document.getElementById("Ponderacion").value.toString() == "-1")
        		{
            		//En caso de no ocurrir un error de validación, se asigna el valor de paso.
            		error = error+1;			
            		}
            
            if(document.getElementById("idCedula").value.toString() == "-1")
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
                    cargar(url,parametro,'sandbox');		
                    }
            }
    
    function habEscala()
		{
			/*
			 * Esta función habilita los controles del formulario de escalas.
			 */
			document.getElementById('Escala').disabled = false;
			document.getElementById('idCedula').disabled = false;
			document.getElementById('Ponderacion').disabled = false;
			document.getElementById('esc_Guardar').style.display="block";
			document.getElementById('esc_Borrar').style.display="none";
			document.getElementById('esc_Editar').style.display="none";
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
        			if(e.target.id.substring(0,10) == "esc_buscar")
        				{
        					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
        					document.getElementById('pgescala').value = document.getElementById('busescala').value.toString();
        					document.getElementById('pgponderacion').value = document.getElementById('busponderacion').value.toString();
        					document.getElementById('pgidcedula').value = document.getElementById('busidcedula').value.toString();
        					cargar('./php/frontend/instrumentos/escalas/catEscalas.php','?busescala='+document.getElementById('busescala').value.toString()+'&busponderacion='+document.getElementById('busponderacion').value.toString()+'&busidcedula='+document.getElementById('busidcedula').value.toString(),'busRes');
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
        			if(e.target.id.substring(0,10) == "esc_delete")
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
    				            					cargar('./php/backend/dal/instrumentos/escalas/dalEscalas.class.php','?id='+e.target.id.substring(11)+'&accion=EdRS','sandbox');
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
        			if(e.target.id.substring(0,7) == "esc_add")
        				{
        					//En caso de coincidir el id con la accion agregar.
        					cargar('./php/frontend/instrumentos/escalas/opEscalas.php','?id=-1&view=0','sandbox');
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
        			if(e.target.id.substring(0,14) == "esc_visualizar")
        				{
        					//En caso de coincidir el id con la accion visualizar.
        					cargar('./php/frontend/instrumentos/escalas/opEscalas.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
        			if(e.target.id.substring(0,8) == "esc_edit")
        				{
        					//En caso de coincidir el id con la accion editar.
        					cargar('./php/frontend/instrumentos/escalas/opEscalas.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
    				if(e.target.id == "esc_Previous_10")
    					{
    						//En caso de coincidir con el control de retroceso.
    						if((document.getElementById('pagina').value-1)!=0)
    							{
    								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
    								}							
    						cargar('./php/frontend/instrumentos/escalas/catEscalas.php','?busescala='+document.getElementById('pgescala').value.toString()+'&busponderacion='+document.getElementById('pgponderacion').value.toString()+'&busidcedula='+document.getElementById('pgidcedula').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    				if(e.target.id == "esc_Next_10")
    					{
    						//En caso de coincidir con el control de avance.
    						document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
    						cargar('./php/frontend/instrumentos/escalas/catEscalas.php','?busescala='+document.getElementById('pgescala').value.toString()+'&busponderacion='+document.getElementById('pgponderacion').value.toString()+'&busidcedula='+document.getElementById('pgidcedula').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    		        if(e.target.id == "esc_Volver")
    		        	{
    		            	//En caso de coincidir el id con la accion volver.
    		            	cargar('./php/frontend/instrumentos/escalas/busEscalas.php','','sandbox');
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
    			    if(e.target.id == "esc_Borrar")
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
    				            					cargar('./php/backend/dal/instrumentos/escalas/dalEscalas.class.php','?id='+document.getElementById('idEscala').value.toString()+'&accion=EdRS','sandbox');
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
    				if(e.target.id == "esc_Guardar")
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
    				            					guardarEscala('./php/backend/dal/instrumentos/escalas/dalEscalas.class.php','?id='+document.getElementById('idEscala').value.toString()+'&escala='+document.getElementById('Escala').value.toString()+'&ponderacion='+document.getElementById('Ponderacion').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&accion=CoER');
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
    				if(e.target.id == "esc_Editar")
    					{
    				     	//En caso de coincidir el id con la accion edicion.
    				        habEscala();
    						}
    				});                 
			});