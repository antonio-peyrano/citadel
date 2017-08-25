/*********************************************************************************************
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del *
 * catálogo de cedulas en el sistema.                                                       *
 *********************************************************************************************/

    function guardarEvaluacion(url,parametro)
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
            
            if(document.getElementById("idUsuario").value.toString() == "-1")
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

//DECLARACION DE FUNCIONES A EJECUTARSE SOBRE FORMULARIO DE CATALOGO.    
/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id eva_evaluar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
    $(document).ready(function()
    	{
        	$("div").click(function(e)
        		{
        			e.stopPropagation();
        			if(e.target.id.substring(0,11) == "eva_evaluar")
        				{
        					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
        					cargar('./php/frontend/instrumentos/evaluaciones/opEvaluaciones.php','?idevaluacion='+e.target.id.substring(12)+'&idusuario='+document.getElementById('idUsuario').value.toString(),'busRes');
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
    				if(e.target.id == "eva_Previous_10")
    					{
    						//En caso de coincidir con el control de retroceso.
    						if((document.getElementById('pagina').value-1)!=0)
    							{
    								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
    								}							
    						cargar('./php/frontend/instrumentos/evaluaciones/catEvaluaciones.php','?busidusuario='+document.getElementById('idUsuario').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    				if(e.target.id == "eva_Next_10")
    					{
    						//En caso de coincidir con el control de avance.
    						document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
    						cargar('./php/frontend/instrumentos/evaluaciones/catEvaluaciones.php','?busidusuario='+document.getElementById('idUsuario').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
    						}
    				});                 
			});

	/*
	 * El presente segmento de codigo evalua la accion de click sobre el elemento de evaluacion
	 * de la cedula seleccionada por el usuario.
	 */
		$(document).ready(function()
			{
		    	$("div").click(function(e)
		    		{
		    			e.stopPropagation();
		    			if(e.target.id == "eva_Evaluar")
		    				{
		    			     	//En caso de coincidir el id con la accion edicion.
		    					if(document.getElementById("eva_busidcedula").value.toString() != "-1")
		    						{
		    							guardarEvaluacion('./php/backend/dal/instrumentos/evaluaciones/dalEvaluaciones.class.php','?id='+document.getElementById('idEvaluacion').value.toString()+'&folio='+document.getElementById('Folio').value.toString()+'&idusuario='+document.getElementById('idUsuario').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&fecha='+document.getElementById('Fecha').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&accion=CoER&view=8');	    						
		    							}
		    					else
		    						{
		    							bootbox.alert("Seleccione una cedula primero");
		    							}
		    					}
		    			});                 
				});

	/*
	 * El presente segmento de codigo evalua la accion de change sobre el elemento de combobox
	 * del id de la cedula a evaluar.
	 */
		$(document).ready(function() {
		    $("div").change(function(e){
		    	e.stopPropagation();
		    	if(e.target.id == "eva_busidcedula")
		    		{
		    			//Se confirma la carga de actualizacion sobre fecha y folio.
		    			document.getElementById("idCedula").value = document.getElementById("eva_busidcedula").value;
		    			cargarSync('./php/frontend/instrumentos/evaluaciones/comp/folio.php','','divFolio');
		    			cargarSync('./php/frontend/instrumentos/evaluaciones/comp/fecha.php','','divFecha');
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
    		        if(e.target.id == "eva_Volver")
    		        	{
    		            	//En caso de coincidir el id con la accion volver.
    		            	cargar('./php/frontend/instrumentos/evaluaciones/busEvaluaciones.php','','sandbox');
    		            	}
    				});                 
			});

	/*
	 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id Escala_#
	 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
	 */
	$(document).ready(function() {
	    $("div").change(function(e){
	    	e.stopPropagation();      	    	
	    	if(e.target.id.substring(0,7) == "Escala_")
	    		{
	    			var radio = $('.radio');
	    			//En caso de coincidir el id con la accion visualizar.    		    			
	    			if(document.getElementById("Res_"+e.target.id.substring(7)).value.toString() == "NP")    							
	    				{    							
	    					document.getElementById("Contestados").value = parseInt(document.getElementById("Contestados").value.toString()) + 1;
	    					for (var x=0; x < radio.length; x++)
	    						{
	    							if((radio[x].id == e.target.id)&&(radio[x].checked))
	    								{
	    									document.getElementById("Res_"+e.target.id.substring(7)).value=radio[x].value;
	    									}
	    							}    					
	    					}
	    			else
	    				{
							for (var x=0; x < radio.length; x++)
								{
									if((radio[x].id == e.target.id)&&(radio[x].checked))
										{
											document.getElementById("Res_"+e.target.id.substring(7)).value=radio[x].value;
											}
									}
	    					}    							

	    			}
	    });                 
	});

	/*
	 * Este segmento de codigo efectua el respaldo de los avances sobre la evaluacion contestada por el usuario.
	 */
	$(document).ready(function() {
	    $("div").click(function(e){
	    	e.stopPropagation();      	
	    	if(e.target.id == "guardarEncuesta")
	    		{
	    			var txtfactores = $('.txtfactor');
	    			var txtescalas = $('.txtescala');
	    			var radio = $('.radio');
	    			
	    			var idfactores = "-1";
	    			var idescalas = "-1";
	    			
	    			for(var x=0; x < txtfactores.length; x++)
	    				{
	    					if(document.getElementById("Res_"+(x+1)).value.toString()!="NP")
	    						{
	        						idfactores+='%'+txtfactores[x].value.toString();    						
	    							}
	    					}

	    			for(var x=0; x < txtescalas.length; x++)
						{
							if(radio[x].checked)
								{
									idescalas+='%'+txtescalas[x].value.toString();
									}
							}
	    			if(document.getElementById('Reactivos').value.toString()==document.getElementById('Contestados').value.toString())
	    				{
	    					cargar('./php/backend/dal/instrumentos/evaluaciones/dalEvaluaciones.class.php','?id='+document.getElementById('idEvaForm').value.toString()+'&idfactor='+idfactores+'&idescala='+idescalas+'&status='+document.getElementById('Status').value.toString()+'&idusuario='+document.getElementById('idUsuario').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&accion=CoER&view=9','sandbox');
	    					}
	    			else
	    				{
	    					cargar('./php/backend/dal/instrumentos/evaluaciones/dalEvaluaciones.class.php','?id='+document.getElementById('idEvaForm').value.toString()+'&idfactor='+idfactores+'&idescala='+idescalas+'&status='+document.getElementById('Status').value.toString()+'&idusuario='+document.getElementById('idUsuario').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&accion=CoER&view=7','sandbox');
	    					}    			
	    			}
	    });                 
	}); 

	/*
	 * Este segmento de codigo efectua el almacenamiento de la evaluacion contestada por el usuario.
	 */
	$(document).ready(function() {
	    $("div").click(function(e){
	    	e.stopPropagation();      	
	    	if(e.target.id == "enviarEncuesta")
	    		{
	    			var txtfactores = $('.txtfactor');
	    			var txtescalas = $('.txtescala');
	    			var radio = $('.radio');
	    			
	    			var idfactores = "-1";
	    			var idescalas = "-1";
	    			
	    			for(var x=0; x < txtfactores.length; x++)
	    				{
	    					idfactores+='%'+txtfactores[x].value.toString();
	    					}

	    			for(var x=0; x < txtescalas.length; x++)
						{
							if(radio[x].checked)
								{
									idescalas+='%'+txtescalas[x].value.toString();
									}
							}
	    			if(document.getElementById('Reactivos').value.toString()==document.getElementById('Contestados').value.toString())
	    				{
    						cargar('./php/backend/dal/instrumentos/evaluaciones/dalEvaluaciones.class.php','?id='+document.getElementById('idEvaForm').value.toString()+'&idfactor='+idfactores+'&idescala='+idescalas+'&status='+document.getElementById('Status').value.toString()+'&idusuario='+document.getElementById('idUsuario').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&accion=CoER&view=9','sandbox');
	    					}
	    			else
	    				{
	                    	bootbox.alert("Aun restan reactivos por completar");
	    					}    			
	    			}
	    });                 
	}); 