/**************************************************************************************************
 * Este archivo de script contiene las funciones de soporte de carga de la interfaz e intercambio *
 * de datos por URL mediante ajax.                                                                *
 **************************************************************************************************/

	function nuevoAjax()
		{
			//Esta función crea las instancias necesarias para el manejo de objetos Ajax.
			var xmlhttp = false; //Se define el objeto de plantilla en falso.
    
			try
				{
					//Se carga una referencia de objeto ActiveX para la plantilla mediante instancia Msxml2.
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
					} 
			catch (e)
				{
					try
						{
							//En caso de ocurrir un error de instanciamiento de plantilla,
							//se carga una referencia de objeto ActiveX para la plantilla mediante instancia Microsoft.
							xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
							} 
					catch (E)
						{
							//En caso de ocurrir un error no controlable, se establece la propiedad a falso.
							xmlhttp = false;
							}
					}

			if (!xmlhttp && typeof XMLHttpRequest!='undefined')
				{
					//En caso de ocurrir la excepción no controlable, se define el parametro con referencia
					//al navegador origen de la solicitud.
					xmlhttp = new XMLHttpRequest();
					}
    
			return xmlhttp;    
			} 
            
	function cargar(url,parametro,objetivo)
		{
			//Esta función establece los elementos de carga de la nueva url
			//sobre el div de contenido.
			var contenido = document.getElementById(objetivo);

			ajax = nuevoAjax(); //Se crea la instancia del nuevo objeto Ajax.
			ajax.open("GET", url + parametro,true); //Se carga la referencia del url sobre el objeto Ajax.
			
			ajax.onreadystatechange = function()
				{
					//Se valida que el estado de llamada del nuevo objeto Ajax corresponda
					//a un estado valido de carga.
					if(ajax.readyState == 4)
						{
							contenido.innerHTML = ajax.responseText; //Se carga en el contenido HTML del div destino el resultado del Ajax.
							}
					}			
			ajax.send(null); //Aqui se determina que valores se envian como parametro a la pagina.	
			}

	function cargarSync(url,parametro,objetivo)
		{
			/*
			 * Esta función establece los elementos de carga de la nueva url
			 * sobre el div de contenido.
			 * ADVERTENCIA: DEBIDO A QUE ESTA FUNCION ESTABLECE CARGA SINCRONA DE LOS ELEMENTOS INVOCADOS,
			 * SE RECOMIENDA SOLO SU USO PARA LOS CASOS DE CARGAS MULTIPLES EN SECUENCIA SI NO HAY OTRA
			 * ALTERNATIVA.
			 */
			var contenido = document.getElementById(objetivo);

			ajax = nuevoAjax(); //Se crea la instancia del nuevo objeto Ajax.
			ajax.open("GET", url + parametro,false); //Se carga la referencia del url sobre el objeto Ajax.
	
			ajax.onreadystatechange = function()
				{
					//Se valida que el estado de llamada del nuevo objeto Ajax corresponda
					//a un estado valido de carga.
					if(ajax.readyState == 4)
						{
							contenido.innerHTML = ajax.responseText; //Se carga en el contenido HTML del div destino el resultado del Ajax.
							}
					}
     
			ajax.send(null); //Aqui se determina que valores se envian como parametro a la pagina.	
			}


	$(document).ready(function()
			{
	    		$("div").click(function(e)
	        		{
	        			e.stopPropagation();
	        			$(".datepicker").datepicker(
	        				{
	        					changeMonth: true,
	        					changeYear: true,
	        					yearRange: '1910:2050'
	        					});
	        			});
			  	});