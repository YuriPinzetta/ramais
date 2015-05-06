<?php
	include "../lib/functions.php";
	$conn = db();
	$ramais = array();
	if (isset($_POST['Alterar'])) {
		$contato = array(
			'id_contato' => $_GET['id_contato'],
			'contato' => $_POST['contato'],
			'cargos' => $_POST['cargos']
		);
		if (isset($_POST['ramal_id']) && is_array($_POST['ramal_id'])) {
			foreach ($_POST['ramal_id'] as $index => $id) {
				$ramal = array(
					'id' => $id,
					'tipos' => $_POST['tipos'][$index],
					'ramal' => $_POST['ramal'][$index]
				);
				$ramais[] = $ramal;
			}
		}
		alteraContato($contato, $ramais, $conn);
	}
	if (isset($_POST['Deletar'])) {
		$contato = array(
			'id_contato' => $_GET['id_contato']
		);
		if (deletaRamal($contato, $conn) &&	deletaContato($contato, $conn)) {
			return header('Location: index.php');
		}
	}
	$contato = consultaContato($_GET, $conn);
	$ramais = listarRamais($_GET,$conn);
	$todos_tipos = array(
		"Interno",
		"Casa",
		"Celular",
		"Notebook"
	);
?>
<!DOCTYPE html>
	<html>
		<head>
			<?php
				include 'head.php';
			?>
			<script>
			window.onload = function () {
				var form = document.getElementById('form');
				form.addEventListener('keypress', function (event) {
					var target = event.target;
					if (target.name == 'ramal[]') {
						apenasNumero(event);
					}
				});
				// ou
				/*
				var ramais = document.getElementsByName('ramal[]');
				for (var i = 0; i < ramais.length; i++) {
					var ramal = ramais[i];
					ramal.addEventListener('keypress', apenasNumero, false);
				}
				*/
				var inputCo = document.getElementById("input_contato");
				var bloqueiaTecla1 = new BloqueadoTecla();
				inputCo.addEventListener('keypress', function (event) { bloqueiaTecla1.bloqueia(event); }, false);
			};
			</script>
		</head>
		<body>
			<div class="container">
				<div class="row">
					<nav class="navbar navbar-default">
						<div class="container-fluid">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="index.php">AMIX</a>
							</div>
						</div>
					</nav>
				</div>
				<form id="form" action="<?=$_SERVER['REQUEST_URI']?>" method="post">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Nome do Contato :</label>
										<input type="text"  class="form-control" autocomplete="off" name="contato" id="input_contato" value="<?php echo $contato['contato'] ?>"></input>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Cargos :</label>
								<input type="text" name="cargos" max-length="50" value="<?php echo $contato['cargos']?>"class="form-control" id="input_cargos" autocomplete="off"/>
							</div>
						</div>
					</div>
					<?php foreach ($ramais as $ramal) { ?>
					<input type="hidden" name="ramal_id[]" value="<?php echo $ramal['id']?>" />
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Tipos :</label>
								<select class="form-control" name="tipos[]">
									<?php foreach ($todos_tipos as $tipos) { ?>
										<option value="<?=$tipos?>" <?=($ramal['tipo'] == $tipos ? 'selected' : '')?>><?=$tipos?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Ramal :</label>
								<input type="text" id="input-ramal" name="ramal[]" value="<?php echo $ramal['ramal']?>" class="form-control" autofocus autocomplete="off"/>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<input name="Alterar" type="submit" value="Alterar" class="MeuInput form-control">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<input name="Deletar" type="submit" value="Deletar" class="MeuInput form-control">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
<html>
