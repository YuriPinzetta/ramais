<?php
include("../lib/functions.php");
include("../lib/Usuario.php");
include("../lib/UsuarioDAO.php");

use amixsi\Usuario;
use amixsi\UsuarioDAO;

session_start();

$pdo =db();
$usuarioDao = new UsuarioDAO($pdo);
$ulog = $usuarioDao->logado();

if (!$ulog) {
    return header("Location: login.php");
}
?>
<!DOCTYPE html>
	<html>
		<head>
			<?php include 'head.php';?>
		</head>
		<body>
			<?php include 'menu.php'?>
				<div class="row">
					<div class="form-group">
						<div class="col-md-4">
							<a href="contato.php">
								<button class="btn btn-default form-control">
									Contato
								</button>
							</a>
						</div>
						<div class="col-md-4">
							<a href="ramais.php">
								<button type="button" class="btn btn-default form-control">
									<span>Ramais</span>
								</button>
							</a>	
						</div>
						<div class="col-md-4">
							<a href="consulta.php">
								<button type="button" class="btn btn-default form-control">
									<span> Consultar Ramais</span>
								</button>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
<html>
