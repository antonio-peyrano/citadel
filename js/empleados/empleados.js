/*********************************************************************************************
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del *
 * catálogo de empleados en el sistema.                                                       *
 *********************************************************************************************/

    function guardarEmpleado(url,parametro)
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

            if(document.getElementById("rfc").value.toString() == "")
				{
    				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
    				error = error+1;			            	
					}
            
            if(document.getElementById("Calle").value.toString() == "")
				{
    				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
    				error = error+1;			            	
					}
            
            if(document.getElementById("nExt").value.toString() == "")
				{
    				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
    				error = error+1;			            	
					}

            if(document.getElementById("emp_cbidColonia").value.toString() == "")
				{
					//En caso de no ocurrir un error de validación, se asigna el valor de paso.
					error = error+1;			            	
					}

            if(document.getElementById("idEntidad").value.toString() == "-1")
				{
					//En caso de no ocurrir un error de validación, se asigna el valor de paso.
					error = error+1;			            	
					}

            if(document.getElementById("idPuesto").value.toString() == "-1")
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

            if(document.getElementById("esRAC").value.toString() == "-1")
				{
					//En caso de no ocurrir un error de validación, se asigna el valor de paso.
					error = error+1;			            	
					}
            
            if(!validarCorreo(document.getElementById("Correo").value.toString()))
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
    
    function validarCorreo(correo) 
		{
			/*
			 * Esta funcion evalua una cadena de texto apartir de una expresion regular
			 * y verifica si el formato es consistente con una direccion de correo.
			 */
			expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			return expr.test(correo);        		
			}
    
    function habEmpleado()
		{
			/*
			 * Esta función habilita los controles del formulario de empleado.
			 */
			document.getElementById('Paterno').disabled = false;
			document.getElementById('Materno').disabled = false;
			document.getElementById('Nombre').disabled = false;
			document.getElementById('fNacimiento').disabled = false;
			document.getElementById('curp').disabled = false;
			document.getElementById('rfc').disabled = false;
			document.getElementById('Calle').disabled = false;
			document.getElementById('nInt').disabled = false;
			document.getElementById('nExt').disabled = false;
			document.getElementById('emp_cbidColonia').disabled = false;
			document.getElementById('idEntidad').disabled = false;
			document.getElementById('idPuesto').disabled = false;
			document.getElementById('telFijo').disabled = false;
			document.getElementById('telCel').disabled = false;
			document.getElementById('Correo').disabled = false;
			document.getElementById('esRAC').disabled = false;
			document.getElementById('emp_Guardar').style.display="block";
			document.getElementById('emp_Borrar').style.display="none";
			document.getElementById('emp_Editar').style.display="none";
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
        			if(e.target.id.substring(0,10) == "emp_buscar")
        				{
        					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
        					document.getElementById('pgpaterno').value = document.getElementById('buspaterno').value.toString();
        					document.getElementById('pgmaterno').value = document.getElementById('busmaterno').value.toString();
        					document.getElementById('pgnombre').value = document.getElementById('busnombre').value.toString();
        					document.getElementById('pgcurp').value = document.getElementById('buscurp').value.toString();
        					document.getElementById('pgrfc').value = document.getElementById('busrfc').value.toString();
        					cargar('./php/frontend/empleados/catEmpleados.php','?buspaterno='+document.getElementById('buspaterno').value.toString()+'&busmaterno='+document.getElementById('busmaterno').value.toString()+'&busnombre='+document.getElementById('busnombre').value.toString()+'&buscurp='+document.getElementById('buscurp').value.toString()+'&busrfc='+document.getElementById('busrfc').value.toString(),'busRes');
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
        			if(e.target.id.substring(0,10) == "emp_delete")
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
    				            					cargar('./php/backend/dal/empleados/dalEmpleados.class.php','?id='+e.target.id.substring(11)+'&accion=EdRS','sandbox');
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
        			if(e.target.id.substring(0,7) == "emp_add")
        				{
        					//En caso de coincidir el id con la accion agregar.
        					cargar('./php/frontend/empleados/opEmpleados.php','?id=-1&view=0','sandbox');
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
        			if(e.target.id.substring(0,14) == "emp_visualizar")
        				{
        					//En caso de coincidir el id con la accion visualizar.
        					cargar('./php/frontend/empleados/opEmpleados.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
        			if(e.target.id.substring(0,8) == "emp_edit")
        				{
        					//En caso de coincidir el id con la accion editar.
        					cargar('./php/frontend/empleados/opEmpleados.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
    				if(e.target.id == "emp_Previous_10")
    					{
    						//En caso de coincidir con el control de retroceso.
    						if((document.getElementById('pagina').value-1)!=0)
    							{
    								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
    								}							
    						cargar('./php/frontend/empleados/catEmpleados.php','?buspaterno='+document.getElementById('pgpaterno').value.toString()+'&busmaterno='+document.getElementById('pgmaterno').value.toString()+'&busnombre='+document.getElementById('pgnombre').value.toString()+'&buscurp='+document.getElementById('pgcurp').value.toString()+'&busrfc='+document.getElementById('pgrfc').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    				if(e.target.id == "emp_Next_10")
    					{
    						//En caso de coincidir con el control de avance.
    						document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
    						cargar('./php/frontend/empleados/catEmpleados.php','?buspaterno='+document.getElementById('pgpaterno').value.toString()+'&busmaterno='+document.getElementById('pgmaterno').value.toString()+'&busnombre='+document.getElementById('pgnombre').value.toString()+'&buscurp='+document.getElementById('pgcurp').value.toString()+'&busrfc='+document.getElementById('pgrfc').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    		        if(e.target.id == "emp_Volver")
    		        	{
    		            	//En caso de coincidir el id con la accion volver.
    		            	cargar('./php/frontend/empleados/busEmpleados.php','','sandbox');
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
    			    if(e.target.id == "emp_Borrar")
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
    				            					cargar('./php/backend/dal/empleados/dalEmpleados.class.php','?id='+document.getElementById('idEmpleado').value.toString()+'&accion=EdRS','sandbox');
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
    				if(e.target.id == "emp_Guardar")
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
    				            					guardarEmpleado('./php/backend/dal/empleados/dalEmpleados.class.php','?id='+document.getElementById('idEmpleado').value.toString()+'&paterno='+document.getElementById('Paterno').value.toString()+'&materno='+document.getElementById('Materno').value.toString()+'&nombre='+document.getElementById('Nombre').value.toString()+'&fnacimiento='+document.getElementById('fNacimiento').value.toString()+'&curp='+document.getElementById('curp').value.toString()+'&rfc='+document.getElementById('rfc').value.toString()+'&calle='+document.getElementById('Calle').value.toString()+'&nint='+document.getElementById('nInt').value.toString()+'&next='+document.getElementById('nExt').value.toString()+'&idcolonia='+document.getElementById('emp_cbidColonia').value.toString()+'&identidad='+document.getElementById('idEntidad').value.toString()+'&idpuesto='+document.getElementById('idPuesto').value.toString()+'&telfijo='+document.getElementById('telFijo').value.toString()+'&telcel='+document.getElementById('telCel').value.toString()+'&correo='+document.getElementById('Correo').value.toString()+'&esrac='+document.getElementById('esRAC').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&accion=CoER');
    				            					}			            					
    				            			}
    				        		});			        		
    						}
    				});                 
			});

/*
 * El presente segmento de codigo evalua la accion keyup sobre el elemento de consulta
 * pulsado sobre el formulario operativo.
 */
	$(document).ready(function()
		{
    		$("div").click(function(e)
    			{
    				e.stopPropagation();
    				if(e.target.id == "emp_Editar")
    					{
    				     	//En caso de coincidir el id con la accion edicion.
    				        habEmpleado();
    						}
    				});                 
			});
	
	$(document).ready(function()
			{
				$("div").keyup(function(e)
					{
						e.stopPropagation();
						if(e.target.id == "emp_cbidColonia")
							{
								//En caso de coincidir el id con la accion edicion.
								$("#emp_cbidColonia").autocomplete({source:"./php/frontend/empleados/comp/idColonias.class.php?parametro="+document.getElementById('emp_cbidColonia').value});
								}	    			
						});                 
				});
	
/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
	$(document).ready(function()
		{
	    	$("div").change(function(e){
	    	e.stopPropagation();
	    	if(e.target.id == "idEntidad")
	    		{
	    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.    			
	    			cargarSync('./php/frontend/empleados/comp/idPuesto.class.php','?identidad='+document.getElementById('idEntidad').value.toString()+'&habilitador=disabled','divCBPuestos');
	    			}
	    });                 
	});	