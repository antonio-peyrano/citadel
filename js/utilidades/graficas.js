/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * graficas en el sistema.
 */

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
	$(document).ready(function()
		{
			$("div").click(function(e)
				{
					e.stopPropagation();
					if(e.target.id == "btnConsultar")
						{
							//Si el usuario confirma su solicitud de borrar el registro seleccionado.
							cargarSync('./php/frontend/utilidades/graficas/comp/graficaPasarela.php','?identidad='+document.getElementById('gridEntidad').value.toString(),'graficaPasarela');
							}
					});                 
			});                 