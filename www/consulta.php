<?php
include "../lib/functions.php";
include "../lib/Contato.php";
include "../lib/ContatoDAO.php";
include "../lib/Ramal.php";
include "../lib/RamalDAO.php";
include "../lib/Usuario.php";
include "../lib/UsuarioDAO.php";

use amixsi\Usuario;
use amixsi\UsuarioDAO;
use amixsi\ContatoDAO;
use amixsi\Contato;
use amixsi\RamalDAO;
use amixsi\Ramal;

session_start();
$pdo = db();

$usuarioDao = new UsuarioDAO($pdo);
$ulog = $usuarioDao->logado();

if (!$ulog) {
    return header("Location: login.php");
}

$ramalDao = new RamalDAO($pdo);
$contatoDao = new ContatoDAO($pdo, $ramalDao);
$todos_contatos = $contatoDao->listar(array());

//$todos_contatos = $contatoDAO->listarContatos(array());
$contato_selecionado = null;
if (isset($_GET['id_contato'])) {
    $contatos = $contatoDao->listar($_GET);
    $contato_selecionado = $_GET['id_contato'];
}
$todos_cargos = $contatoDao->listarCargos(array());
$cargo_selecionado = null;
if (isset($_GET['cargos'])) {
    $cargo_selecionado = $_GET['cargos'];
}
$tipos_selecionado = null;
if (isset($_GET['tipos'])) {
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
										<option value="<?php echo $contato->getId() ?>" <?=$contato->getId() == $contato_selecionado ? 'selected' : ''?>><?php echo $contato->getNome() ?></option>
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
							$href = 'edicao.php?id_contato=' . $contato->getId();
						?>
						<tr>
							<td>
								<a href="<?=$href?>">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"> <?=$contato->getNome()?></span>
								</a>
							</td>
							<td><?=$contato->getCargo()?></td>
							<td>
								<ul>
								<?php foreach ($contato->getRamais() as $ramal) {	?>
									<li><?=$ramal->getTipo() ?> - <?=$ramal->getNumero() ?></li>
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
