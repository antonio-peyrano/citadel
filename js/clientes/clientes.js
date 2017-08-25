/*********************************************************************************************
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del *
 * catálogo de clientes en el sistema.                                                       *
 *********************************************************************************************/

    function guardarCliente(url,parametro)
        {
            /*
             * Esta función valida que los datos para ser almacenados en el registro sean correctos.
             */	
            var error= 0;
		
            if(document.getElementById("Paterno").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;			
                    }
		
            if(document.getElementById("Materno").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;
                    }
		
            if(document.getElementById("Nombre").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;			
                    }
            
            if(document.getElementById("curp").value.toString() == "")
    			{
        			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
        			error = error+1;			            	
    				}
            
            if(document.getElementById("fNacimiento").value.toString() == "")
            	{
                	//En caso de no ocurrir un error de validación, se asigna el valor de paso.
                	error = error+1;			            	
            		}

            if(document.getElementById("telFijo").value.toString() == "")
        		{
            		//En caso de no ocurrir un error de validación, se asigna el valor de paso.
            		error = error+1;			            	
        			}

            if(document.getElementById("telCel").value.toString() == "")
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
    
    function habCliente()
		{
			/*
			 * Esta función habilita los controles del formulario de cliente.
			 */
			document.getElementById('Paterno').disabled = false;
			document.getElementById('Materno').disabled = false;
			document.getElementById('Nombre').disabled = false;
			document.getElementById('fNacimiento').disabled = false;
			document.getElementById('curp').disabled = false;
			document.getElementById('telFijo').disabled = false;
			document.getElementById('telCel').disabled = false;
			document.getElementById('cli_Guardar').style.display="block";
			document.getElementById('cli_Borrar').style.display="none";
			document.getElementById('cli_Editar').style.display="none";
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
        			if(e.target.id.substring(0,10) == "cli_buscar")
        				{
        					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
        					document.getElementById('pgpaterno').value = document.getElementById('buspaterno').value.toString();
        					document.getElementById('pgmaterno').value = document.getElementById('busmaterno').value.toString();
        					document.getElementById('pgnombre').value = document.getElementById('busnombre').value.toString();
        					document.getElementById('pgcurp').value = document.getElementById('buscurp').value.toString();
        					cargar('./php/frontend/clientes/catClientes.php','?buspaterno='+document.getElementById('buspaterno').value.toString()+'&busmaterno='+document.getElementById('busmaterno').value.toString()+'&busnombre='+document.getElementById('busnombre').value.toString()+'&buscurp='+document.getElementById('buscurp').value.toString(),'busRes');
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
        			if(e.target.id.substring(0,10) == "cli_delete")
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
    				            					cargar('./php/backend/dal/clientes/dalClientes.class.php','?id='+e.target.id.substring(11)+'&accion=EdRS','sandbox');
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
        			if(e.target.id.substring(0,7) == "cli_add")
        				{
        					//En caso de coincidir el id con la accion agregar.
        					cargar('./php/frontend/clientes/opClientes.php','?id=-1&view=0','sandbox');
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
        			if(e.target.id.substring(0,14) == "cli_visualizar")
        				{
        					//En caso de coincidir el id con la accion visualizar.
        					cargar('./php/frontend/clientes/opClientes.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
        			if(e.target.id.substring(0,8) == "cli_edit")
        				{
        					//En caso de coincidir el id con la accion editar.
        					cargar('./php/frontend/clientes/opClientes.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
    				if(e.target.id == "cli_Previous_10")
    					{
    						//En caso de coincidir con el control de retroceso.
    						if((document.getElementById('pagina').value-1)!=0)
    							{
    								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
    								}							
    						cargar('./php/frontend/clientes/catClientes.php','?buspaterno='+document.getElementById('pgpaterno').value.toString()+'&busmaterno='+document.getElementById('pgmaterno').value.toString()+'&busnombre='+document.getElementById('pgnombre').value.toString()+'&buscurp='+document.getElementById('pgcurp').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    				if(e.target.id == "cli_Next_10")
    					{
    						//En caso de coincidir con el control de avance.
    						document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
    						cargar('./php/frontend/clientes/catClientes.php','?buspaterno='+document.getElementById('pgpaterno').value.toString()+'&busmaterno='+document.getElementById('pgmaterno').value.toString()+'&busnombre='+document.getElementById('pgnombre').value.toString()+'&buscurp='+document.getElementById('pgcurp').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    		        if(e.target.id == "cli_Volver")
    		        	{
    		            	//En caso de coincidir el id con la accion volver.
    		            	cargar('./php/frontend/clientes/busClientes.php','','sandbox');
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
    			    if(e.target.id == "cli_Borrar")
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
    				            					cargar('./php/backend/dal/clientes/dalClientes.class.php','?id='+document.getElementById('idCliente').value.toString()+'&accion=EdRS','sandbox');
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
    				if(e.target.id == "cli_Guardar")
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
    				            					guardarCliente('./php/backend/dal/clientes/dalClientes.class.php','?id='+document.getElementById('idCliente').value.toString()+'&paterno='+document.getElementById('Paterno').value.toString()+'&materno='+document.getElementById('Materno').value.toString()+'&nombre='+document.getElementById('Nombre').value.toString()+'&fnacimiento='+document.getElementById('fNacimiento').value.toString()+'&curp='+document.getElementById('curp').value.toString()+'&telfijo='+document.getElementById('telFijo').value.toString()+'&telcel='+document.getElementById('telCel').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&accion=CoER');
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
    				if(e.target.id == "cli_Editar")
    					{
    				     	//En caso de coincidir el id con la accion edicion.
    				        habCliente();
    						}
    				});                 
			});