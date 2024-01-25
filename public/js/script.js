/**
 * @author Saatsa franklin Blerio <saatsafranklin@gmail.com>
 * @description Analyste programmeur/developpeur full-strack
 *
 */
/**
 * Constantes nessessaire a la manipulation dynamique de l'arrosage de la plante
 *
 */
let marquer = document.querySelector(".marquer"),
	BASE_URI_ROUTE = "https://127.0.0.1:8000/user/plante";
	//Gestion des evenements
$(document).ready(function () {
	//Evenement permettant de marquer et d'arroser la plante
	marquer.addEventListener("click", (event) => {
		event.preventDefault();
		let p = event.target.getAttribute("data-p");
		let q = document.querySelector('#quantiteEau').value;
		let f = document.querySelector('#frecArrosage').value;
		try {
			let option = {
				url: `${BASE_URI_ROUTE}/p/${p}/q/${q}/f/${f}`,
				type: "POST",
				dataType: "json",
				CORS: true,
				contentType: "application/json",
				secure: true,
				headers: {
					"Access-Control-Allow-Origin": "https://127.0.0.1:8000/",
				},
			};
			$.ajax(option).done(function (data) {
				console.log(`MESSAGE: ${data.message}`);
				//location.reload();
			});
		} catch (error) {
			console.log(`MARQUER_PLANTE: ${error.message}`);
		}
	});
});
