<?php
	include("../lib/functions.php");
	session_start();
	$ulog = usuarioLogado();
	if(!$ulog){
		header("Location: login.php");
	}
	//include "menu.php";
?>
<!DOCTYPE html>
	<html>
		<head>
			<?php
				include 'head.php';
			?>
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

