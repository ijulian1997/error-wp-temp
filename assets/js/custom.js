// Filtrar productos home con jQuery, aunque recomiendan usar vanilla
(function($){
	$('#categorias-productos').change(function(){ // id del select
		$.ajax({
			url: objPlatziGift.ajaxurl, // el nombre del obejto definido en functions.php -> assets
			method: "POST",
			data: {
				"action": "pgFiltroProductos", //nombre de la funcion en functions.php
				"categoria": $(this).find(':selected').val() // this hace referencia la opcion escogida en el select
			},
			// Mostrar loading
			beforeSend: function() {
				$("#resultado-productos").html("Cargando...") // id del div donde esta loop, en este caso en front-page.php
			},
			// Que pasa si la funcion se ejecuta correctamente
			success: function(data) {
				let html = "";
				data.forEach(item => {
					html += `
					<div class="cell medium-4">
						<a href="${item.link}">
							<figure>${item.imagen}</figure>
							<h4 class="text-center">${item.titulo}</h4>
						</a>
					</div>`
				})
				$("#resultado-productos").html(html);
			},
			error: function(error) {
				console.log(error)
			}
		})
	})


	// Para custom API
	$(document).ready(function(){
		$.ajax({
			url: objPlatziGift.apiurl+"novedades/2", // el nombre del obejto definido en functions.php -> assets
			method: "GET",
			// Mostrar loading
			beforeSend: function() {
				$("#resultado-novedades").html("Cargando...") // id del div donde esta loop, en este caso en front-page.php
			},
			// Que pasa si la funcion se ejecuta correctamente
			success: function(data) {
				let html = "";
				data.forEach(item => {
					html += `
					<div class="cell medium-4">
						<a href="${item.link}">
							<figure>${item.imagen}</figure>
							<h4 class="text-center">${item.titulo}</h4>
						</a>
					</div>`
				})
				$("#resultado-novedades").html(html);
			},
			error: function(error) {
				console.log(error)
			}
		})
	})
}) (jQuery)