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
				<form action="salva_form.php" method="post">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Cargos :</label>
								<select class="form-control" name="cargos">
									<option value="Estagiario">Estágiario</option>
									<option value="Desenvolvedor JR">Desenvolvedor JR</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Nome do Contato :</label>
								<input type="text" name="contato" value="" class="form-control" />
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
