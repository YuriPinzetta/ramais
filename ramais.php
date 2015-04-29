<?php
include "./mysqlconecta.php"; // Conecta ao banco de dados
$res = mysql_query("
	select id, contato
	from contato
", $id);

$contatos = array();
while ($contato = mysql_fetch_assoc($res)) {
	$contatos[] = $contato;
}
?>
<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title>Ramais</title>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, user-scalable=no">
			<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
			<script src="bootstrap/js/bootstrap.min.js"></script>
			<script src="jquery/jquery.js"></script>
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
								<a class="navbar-brand" href="#">AMIX</a>
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
								<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Ramal :</label>
								<input type="text" name="ramal" valus"" class="form-control" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<input name="Enviar" type="submit" value="Enviar" class="MeuInput form-control">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
<html>
