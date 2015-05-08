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
								<?php if(usuarioLogado()){ ?>
									<p class="navbar-text">Bem-vindo, <?=$_SESSION['usuario']?></p>
								<?php } ?>
							</div>
							<?php if(usuarioLogado()){ ?>
							<button name="botao" type="submit" class="btn btn-default navbar-btn navbar-right"><a href="sair.php">Sair</a></button>
							<?php } ?>
						</div>
					</nav>
				</div>
