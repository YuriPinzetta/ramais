<?php
include "../lib/functions.php";
include "../lib/Contato.php";
include "../lib/ContatoDAO.php";
include "../lib/Ramal.php";

use amixsi\ContatoDAO;
use amixsi\Contato;

session_start();
$ulog = usuarioLogado();
if (!$ulog) {
    return header("Location: login.php");
}
if (isset($_POST['Enviar'])) {
	try {
		$contato = Contato::fromArray($_POST);
	} catch (Exception $ex) {
		header("HTTP/1.1 404 Bad Request");
		echo $ex->getMessage();
		return;
	}
	$pdo = db();
	$contatoDao = new ContatoDAO($pdo);
	$contatoDao->inserir($contato);
    return header("Location: index.php");
}
?>
<!DOCTYPE html>
	<html>
		<head>
			<?php include 'head.php'; ?>
			<script>
				window.onload = function(){
					var inputCa = document.getElementById("input_cargos");
					var total = document.getElementById("total");
					var inputCo = document.getElementById("input_contato");
					var salva_form = document.getElementById('salva_form');
					maxLength = parseInt(inputCa.getAttribute('max-length'), 10);
					total.innerHTML = maxLength;
					inputCa.addEventListener('keyup', contador, false);
					salva_form.addEventListener('submit', submit, false);
					var bloqueiaTecla1 = new BloqueadoTecla();
					inputCo.addEventListener('keyup', function (event) { bloqueiaTecla1.bloqueia(event); }, false);
				};
			</script>
		</head>
		<body>
			<?php include "menu.php"; ?>
				<form action="" method="post" id="salva_form">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Cargos :</label>
								<input type="text" name="cargos" max-length="50" class="form-control" id="input_cargos" 
                                   autofocus autocomplete="off"><br>
								<div><span id="total"></span> Restantes</div>
							</div>
						</div>
						<div class="col-md-4" id="coluna_contato">
							<div class="form-group">
								<label>Nome do Contato :</label>
								<input type="text" name="contato" id="input_contato" value="" class="form-control"
                                   autocomplete="off" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<input name="Enviar" type="submit" value="Enviar" class="form-control botao">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
<html>
