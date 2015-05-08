<?php
	include "../lib/functions.php";
	$conn = db();
	if(isset($_POST['cadastrar']) && $_POST['cadastrar'] == 'cadastrar'){
		verificaUsuario($_POST, $conn);
	}
?>
<!DOCTYPE html>
	<html>
		<head>
			<?php
				include 'head.php';
			?>
			<script>
				window.onload = function(){
					var inputCo = document.getElementById("login");
					var bloqueiaTecla1 = new BloqueadoTecla();
					inputCo.addEventListener('keyup', function (event) { bloqueiaTecla1.bloqueia(event); }, false);
				};
			</script>
		</head>
		<body>
			<?php include "menu.php";?>
			<form action="" method="post">
				<div class="container">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Login :</label>
								<input id="login" type="text" name="usuario" placeholder="Usuário" class="form-control"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Senha :</label>
								<input type="password" name="senha" placeholder="Senha" class="form-control"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<input name="cadastrar" type="submit" value="cadastrar" class="form-control botao">
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</body>
<html>

