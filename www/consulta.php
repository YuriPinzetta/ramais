<?php
	include "../lib/functions.php";
	session_start();
	$ulog = usuarioLogado();
	if(!$ulog){
		//return header("Location: login.php");
	}
	$pdo = db();
	$todos_contatos = listarContatos(array(), $pdo);
	$contato_selecionado = null;
	if(isset($_GET['id_contato'])){
		$contatos = listarContatos($_GET, $pdo);
		$contato_selecionado = $_GET['id_contato'];
	}
	$todos_cargos = listarCargos(array(), $pdo);
	$cargo_selecionado = null;
	if(isset($_GET['cargos'])){
		$cargo_selecionado = $_GET['cargos'];
	}
	$tipos_selecionado = null;
	if(isset($_GET['tipos'])){
		$tipos_selecionado = $_GET['tipos'];
	}
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
		</head>
		<body>
			<?php include "menu.php"?>
				<form>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Contato :</label>
								<select class="form-control" name="id_contato">
									<option value="">Todos</option>
									<?php foreach ($todos_contatos as $contato) { ?>
										<option value="<?php echo $contato['id'] ?>" <?=$contato['id'] == $contato_selecionado ? 'selected' : ''?>><?php echo $contato['contato'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Cargos :</label>
								<select class="form-control" name="cargos">
									<option value="">Todos</option>
									<?php foreach ($todos_cargos as $cargo) { ?>
										<option value="<?php echo $cargo['cargos'] ?>" <?=$cargo['cargos'] == $cargo_selecionado ? 'selected' : ''?>><?php echo $cargo['cargos'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Tipos :</label>
								<select class="form-control" name="tipos">
									<option value="">Todos</option>
									<?php foreach ($todos_tipos as $tipos) { ?>
										<option value="<?=$tipos?>" <?=($tipos_selecionado == $tipos ? 'selected' : '')?>><?=$tipos?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<input id="enviar" name="Enviar" type="submit" value="Enviar" class="MeuInput form-control">
							</div>
						</div>
					</div>
				</form>
				<div class="table-responsive">
					<?php if(isset($contatos)) { ?>
					<table class="table table-striped table-bordered">
						<tr>
							<th>Contato</th>
							<th>Cargo</th>
							<th>Tipo / Ramal</th>
						</tr>
						<?php foreach ($contatos as $contato) {
							$href = 'edicao.php?id_contato=' . $contato['id'];
						?>
						<tr>
							<td>
								<a href="<?=$href?>">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"> <?=$contato['contato']?></span>
								</a>
							</td>
							<td><?=$contato['cargos']?></td>
							<td>
								<ul>
								<?php foreach ($contato['ramais'] as $ramal) { ?>
									<li><?=$ramal['tipo']?> - <?=$ramal['ramal']?></li>
								<?php } ?>
								</ul>
							</td>
						</tr>
						<?php } ?>
					</table>
					<?php } ?>
				</div>
			</div>
		</div>
	</body>
<html>