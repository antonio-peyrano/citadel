/*********************************************************************************************
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del *
 * catálogo de factores en el sistema.                                                       *
 *********************************************************************************************/

    function guardarFactor(url,parametro)
        {
            /*
             * Esta función valida que los datos para ser almacenados en el registro sean correctos.
             */	
            var error= 0;
		
            if(document.getElementById("Factor").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;			
                    }

            if(document.getElementById("Tipo").value.toString() == "-1")
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
    
    function habFactor()
		{
			/*
			 * Esta función habilita los controles del formulario de factores.
			 */
			document.getElementById('Factor').disabled = false;
			document.getElementById('idCedula').disabled = false;
			document.getElementById('Tipo').disabled = false;
			document.getElementById('fac_Guardar').style.display="block";
			document.getElementById('fac_Borrar').style.display="none";
			document.getElementById('fac_Editar').style.display="none";
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
        			if(e.target.id.substring(0,10) == "fac_buscar")
        				{
        					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
        					document.getElementById('pgfactor').value = document.getElementById('busfactor').value.toString();
        					document.getElementById('pgtipo').value = document.getElementById('bustipo').value.toString();
        					document.getElementById('pgidcedula').value = document.getElementById('busidcedula').value.toString();
        					cargar('./php/frontend/instrumentos/factores/catFactores.php','?busfactor='+document.getElementById('busfactor').value.toString()+'&bustipo='+document.getElementById('bustipo').value.toString()+'&busidcedula='+document.getElementById('busidcedula').value.toString(),'busRes');
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
        			if(e.target.id.substring(0,10) == "fac_delete")
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
    				            					cargar('./php/backend/dal/instrumentos/factores/dalFactores.class.php','?id='+e.target.id.substring(11)+'&accion=EdRS','sandbox');
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
        			if(e.target.id.substring(0,7) == "fac_add")
        				{
        					//En caso de coincidir el id con la accion agregar.
        					cargar('./php/frontend/instrumentos/factores/opFactores.php','?id=-1&view=0','sandbox');
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
        			if(e.target.id.substring(0,14) == "fac_visualizar")
        				{
        					//En caso de coincidir el id con la accion visualizar.
        					cargar('./php/frontend/instrumentos/factores/opFactores.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
        			if(e.target.id.substring(0,8) == "fac_edit")
        				{
        					//En caso de coincidir el id con la accion editar.
        					cargar('./php/frontend/instrumentos/factores/opFactores.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
    				if(e.target.id == "fac_Previous_10")
    					{
    						//En caso de coincidir con el control de retroceso.
    						if((document.getElementById('pagina').value-1)!=0)
    							{
    								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
    								}							
    						cargar('./php/frontend/instrumentos/factores/catFactores.php','?busfactor='+document.getElementById('pgfactor').value.toString()+'&bustipo='+document.getElementById('pgtipo').value.toString()+'&busidcedula='+document.getElementById('pgidcedula').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    				if(e.target.id == "fac_Next_10")
    					{
    						//En caso de coincidir con el control de avance.
    						document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
    						cargar('./php/frontend/instrumentos/factores/catFactores.php','?busfactor='+document.getElementById('pgfactor').value.toString()+'&bustipo='+document.getElementById('pgtipo').value.toString()+'&busidcedula='+document.getElementById('pgidcedula').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    		        if(e.target.id == "fac_Volver")
    		        	{
    		            	//En caso de coincidir el id con la accion volver.
    		            	cargar('./php/frontend/instrumentos/factores/busFactores.php','','sandbox');
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
    			    if(e.target.id == "fac_Borrar")
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
    				            					cargar('./php/backend/dal/instrumentos/factores/dalFactores.class.php','?id='+document.getElementById('idFactor').value.toString()+'&accion=EdRS','sandbox');
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
    				if(e.target.id == "fac_Guardar")
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
    				            					guardarFactor('./php/backend/dal/instrumentos/factores/dalFactores.class.php','?id='+document.getElementById('idFactor').value.toString()+'&factor='+document.getElementById('Factor').value.toString()+'&tipo='+document.getElementById('Tipo').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&accion=CoER');
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
    				if(e.target.id == "fac_Editar")
    					{
    				     	//En caso de coincidir el id con la accion edicion.
    				        habFactor();
    						}
    				});                 
			});