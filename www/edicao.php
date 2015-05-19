<?php
include "../lib/functions.php";
include "../lib/Contato.php";
include "../lib/ContatoDAO.php";
include "../lib/Ramal.php";

use amixsi\ContatoDAO;
use amixsi\Contato;

session_start();
$ulog = usuarioLogado();
if (!$ulog) {
    return header("Location: login.php");
}
$pdo = db();
$ramais = array();
if (isset($_POST['Alterar'])) {
    $contato = array(
        'id_contato' => $_GET['id_contato'],
        'contato' => $_POST['contato'],
        'cargos' => $_POST['cargos']
    );
    if (isset($_POST['ramal_id']) && is_array($_POST['ramal_id'])) {
        foreach ($_POST['ramal_id'] as $index => $id) {
            $ramal = array(
                'id' => $id,
                'tipos' => $_POST['tipos'][$index],
                'ramal' => $_POST['ramal'][$index]
            );
            $ramais[] = $ramal;
        }
    }
    alteraContato($contato, $ramais, $pdo);
}
if (isset($_POST['deletaRamais'])) {
    $contato = array(
        'id_contato' => $_GET['id_contato']
    );
    if (deletaRamal($contato, $pdo)) {
        return header('Location: index.php');
    }
}
if (isset($_POST['deletaContato'])) {
    $contato = array(
        'id_contato' => $_GET['id_contato']
    );
    if (deletaRamal($contato, $pdo) && deletaContato($contato, $pdo)) {
        return header('Location: index.php');
    }
}
$contatoDAO = new ContatoDAO($pdo);
$contato = $contatoDAO->consulta($_GET['id_contato']);
$ramalDAO = new ramalDAO($pdo);
$ramais = $ramalDAO->listar($pdo);
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
			<?php include 'head.php'; ?>
			<script>
			window.onload = function () {
				var form = document.getElementById('form');
				form.addEventListener('keypress', function (event) {
					var target = event.target;
					if (target.name == 'ramal[]') {
						apenasNumero(event);
					}
				});
				// ou
				/*
				var ramais = document.getElementsByName('ramal[]');
				for (var i = 0; i < ramais.length; i++) {
					var ramal = ramais[i];
					ramal.addEventListener('keypress', apenasNumero, false);
				}
				*/
				var inputCo = document.getElementById("input_contato");
				var bloqueiaTecla1 = new BloqueadoTecla();
				inputCo.addEventListener('keypress', function (event) { bloqueiaTecla1.bloqueia(event); }, false);
			};
			</script>
		</head>
		<body>
			<?php include "menu.php"; ?>
				<form id="form" action="<?=$_SERVER['REQUEST_URI']?>" method="post">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Nome do Contato :</label>
										<input type="text"	class="form-control" autocomplete="off" name="contato" 
                                            id="input_contato" value="<?php echo $contato->getNome() ?>">
                                        </input>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Cargos :</label>
								<input type="text" name="cargos" max-length="50" value="<?php echo $contato->getCargo() ?>"
                                    class="form-control" id="input_cargos" autocomplete="off">
                                </input>
							</div>
						</div>
					</div>
					<?php foreach ($ramais as $ramal) { ?>
					<input type="hidden" name="ramal_id[]" value="<?php echo $ramal['id']?>" />
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Tipos :</label>
								<select class="form-control" name="tipos[]">
									<?php foreach ($todos_tipos as $tipos) { ?>
										<option value="<?=$tipos?>" <?=($ramal['tipo'] == $tipos ? 'selected' : '')?>><?=$tipos?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Ramal :</label>
								<input type="text" id="input-ramal" name="ramal[]" value="<?php echo $ramal['ramal']?>" class="form-control" autofocus autocomplete="off"/>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<input name="Alterar" type="submit" value="Alterar" class="form-control botao">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<input name="deletaRamais" type="submit" value="Deletar Ramais" class="form-control botao">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<input name="deletaContato" type="submit" value="Deletar Contato" class="form-control botao">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
<html>
