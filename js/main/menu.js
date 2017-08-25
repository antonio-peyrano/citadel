/***************************************************************************************************
 * Este archivo de script contiene las funciones de soporte para la interfaz principal del usuario *
 ***************************************************************************************************/

	$(document).ready(function()
		{ 
			//Rutina jquery para el comportamiento de despliegue del menu al hacer
			//click por parte del usuario en la interfaz.
			$("ul.subnavegador").not('.selected').hide();
			$("a.desplegable").click(function(e)
				{
					var desplegable = $(this).parent().find("ul.subnavegador");
					$('.desplegable').parent().find("ul.subnavegador").not(desplegable).slideUp('slow');
					desplegable.slideToggle('slow');
					e.preventDefault();
					})
        	});

	$(document).ready(function()
		{				
			//Rutina jquery para el comportamiento de visualizacion del menu, quedando
			//a solicitud del usuario su visualizacion u ocultamiento.
			$("div").click(function(e)
				{
					e.stopPropagation();
					if(("menu-lateral-toggle"==e.target.id.toString())||("menu-icono"==e.target.id.toString()))
						{
							if($("#Contenedor").css('left') == '0px')
								{
									$("#Contenedor").css('left', '240px');
									}
							else
								{
									$("#Contenedor").css('left', '0px');
									}
							}					    
					});
			});

	function bigImg(x)
		{
			/*
			 * Esta funcion redimensiona el elemento de imagen a un aspecto tipo zoom.
			 */
			x.style.height = "39px";
			x.style.width = "39px";
			}

	function normalImg(x)
		{
			/*
			 * Esta funcion reestablece a su estado original a la imagen afectada.
			 */
			x.style.height = "32px";
			x.style.width = "32px";
			}	