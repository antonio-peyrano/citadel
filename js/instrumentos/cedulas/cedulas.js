/*********************************************************************************************
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del *
 * catálogo de cedulas en el sistema.                                                       *
 *********************************************************************************************/

    function guardarCedula(url,parametro)
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

            if(document.getElementById("Fecha").value.toString() == "")
            	{
                	//En caso de no ocurrir un error de validación, se asigna el valor de paso.
                	error = error+1;			
                	}

            if(document.getElementById("Horizonte").value.toString() == "")
        		{
            		//En caso de no ocurrir un error de validación, se asigna el valor de paso.
            		error = error+1;			
            		}

            if(document.getElementById("Descripcion").value.toString() == "")
    			{
        			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
        			error = error+1;			
        			}
            
            if(document.getElementById("idEntidad").value.toString() == "-1")
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
    
    function habCedula()
		{
			/*
			 * Esta función habilita los controles del formulario de cedulas.
			 */
			document.getElementById('Horizonte').disabled = false;
			document.getElementById('idEntidad').disabled = false;
			document.getElementById('Descripcion').disabled = false;
			document.getElementById('ced_Guardar').style.display="block";
			document.getElementById('ced_Borrar').style.display="none";
			document.getElementById('ced_Editar').style.display="none";
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
        			if(e.target.id.substring(0,10) == "ced_buscar")
        				{
        					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
        					document.getElementById('pgfolio').value = document.getElementById('busfolio').value.toString();
        					document.getElementById('pgfecha').value = document.getElementById('busfecha').value.toString();
        					document.getElementById('pgidentidad').value = document.getElementById('busidentidad').value.toString();
        					cargar('./php/frontend/instrumentos/cedulas/catCedulas.php','?busfolio='+document.getElementById('busfolio').value.toString()+'&busfecha='+document.getElementById('busfecha').value.toString()+'&busidentidad='+document.getElementById('busidentidad').value.toString(),'busRes');
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
        			if(e.target.id.substring(0,10) == "ced_delete")
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
    				            					cargar('./php/backend/dal/instrumentos/cedulas/dalCedulas.class.php','?id='+e.target.id.substring(11)+'&accion=EdRS','sandbox');
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
        			if(e.target.id.substring(0,7) == "ced_add")
        				{
        					//En caso de coincidir el id con la accion agregar.
        					cargar('./php/frontend/instrumentos/cedulas/opCedulas.php','?id=-1&view=0','sandbox');
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
        			if(e.target.id.substring(0,14) == "ced_visualizar")
        				{
        					//En caso de coincidir el id con la accion visualizar.
        					cargar('./php/frontend/instrumentos/cedulas/opCedulas.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
        			if(e.target.id.substring(0,8) == "ced_edit")
        				{
        					//En caso de coincidir el id con la accion editar.
        					cargar('./php/frontend/instrumentos/cedulas/opCedulas.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
    				if(e.target.id == "ced_Previous_10")
    					{
    						//En caso de coincidir con el control de retroceso.
    						if((document.getElementById('pagina').value-1)!=0)
    							{
    								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
    								}							
    						cargar('./php/frontend/instrumentos/cedulas/catCedulas.php','?busfolio='+document.getElementById('pgfolio').value.toString()+'&busfecha='+document.getElementById('pgfecha').value.toString()+'&busidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    				if(e.target.id == "ced_Next_10")
    					{
    						//En caso de coincidir con el control de avance.
    						document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
    						cargar('./php/frontend/instrumentos/cedulas/catCedulas.php','?busfolio='+document.getElementById('pgfolio').value.toString()+'&busfecha='+document.getElementById('pgfecha').value.toString()+'&busidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    		        if(e.target.id == "ced_Volver")
    		        	{
    		            	//En caso de coincidir el id con la accion volver.
    		            	cargar('./php/frontend/instrumentos/cedulas/busCedulas.php','','sandbox');
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
    			    if(e.target.id == "ced_Borrar")
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
    				            					cargar('./php/backend/dal/instrumentos/cedulas/dalCedulas.class.php','?id='+document.getElementById('idCedula').value.toString()+'&accion=EdRS','sandbox');
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
    				if(e.target.id == "ced_Guardar")
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
    				            					guardarCedula('./php/backend/dal/instrumentos/cedulas/dalCedulas.class.php','?id='+document.getElementById('idCedula').value.toString()+'&folio='+document.getElementById('Folio').value.toString()+'&descripcion='+document.getElementById('Descripcion').value.toString()+'&identidad='+document.getElementById('idEntidad').value.toString()+'&fecha='+document.getElementById('Fecha').value.toString()+'&horizonte='+document.getElementById('Horizonte').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&accion=CoER');
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
    				if(e.target.id == "ced_Editar")
    					{
    				     	//En caso de coincidir el id con la accion edicion.
    				        habCedula();
    						}
    				});                 
			});