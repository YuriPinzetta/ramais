<?php
	include "../lib/functions.php";
	session_start();
	if(isset($_POST['logar'])){
		$pdo = db();
		validaUsuario($_POST, $pdo);
	}
?>
<!DOCTYPE html>
	<html>
		<head>
			<?php
				include 'head.php';
			?>
		</head>
		<body>
			<?php include "menu.php";?>
			<form action="" method="post">
				<div class="container">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Login :</label>
								<input type="text" name="usuario" placeholder="Usuário" class="form-control"/>
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
								<input name="logar" type="submit" value="logar" class="form-control botao">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<a href="cadastro.php">
									<input name="cadastre-se" type="button" value="cadastre-se" class="form-control botao"/>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</body>
<html>

