<?php
	include "../lib/functions.php";
	$conn = db();
  $res = mysql_query("
		select id, contato
		from contato", $conn
		);
	$contatos = array();
		while ($contato = mysql_fetch_assoc($res)) {
		$contatos[] = $contato;
	}
?>
<!DOCTYPE html>
	<html>
		<head>
			<?php
				include 'head.php';
			?>
			<script>
				window.onload = function () {
					var input = document.getElementById('input-ramal');
					input.addEventListener('keypress', apenasNumero, false);
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
				<form action="salva_form_ramal.php" method="post">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Tipos :</label>
								<select class="form-control" name="tipos">
									<option value="Interno">Interno</option>
									<option value="Casa">Casa</option>
									<option value="Celular">Celular</option>
									<option value="Notebook">Notebook</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Contato :</label>
								<select class="form-control" name="id_contato">
								<?php foreach ($contatos as $contato) { ?>
									<option value="<?php echo $contato['id'] ?>"><?php echo $contato['contato'] ?></option>
							<?php }?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Ramal :</label>
								<input type="text" id="input-ramal" name="ramal" value"" class="form-control" autofocus autocomplete="off"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<input id="enviar" name="Enviar" type="submit" value="Enviar" class="MeuInput form-control"/>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
<html>
