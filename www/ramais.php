<?php
include "../lib/functions.php";
include "../lib/Ramal.php";
include "../lib/RamalDAO.php";

use amixsi\RamalDAO;
use amixsi\Ramal;

session_start();
$ulog = usuarioLogado();
if (!$ulog) {
    return header("Location: login.php");
}
$pdo = db();
$contatoDao = new RamalDAO($pdo);
$contatoDao->listarContatos(array());
$contatos = $contatoDao;
if (isset($_POST['Enviar'])) {
    try {
        $ramal = Ramal::fromArray($_POST);
    } catch (Exception $ex) {
        header("HTTP/1.1 404 Bad Request");
        echo $ex->getMessage();
        return;
    }
    $ramalDAO = new RamalDAO($pdo);
    $ramalDAO->inserir($ramal);
}
?>
<!DOCTYPE html>
    <html>
        <head>
            <?php include 'head.php'; ?>
            <script>
                window.onload = function () {
                    var input = document.getElementById('input-ramal');
                    input.addEventListener('keypress', apenasNumero, false);
                };
            </script>
        </head>
        <body>
            <?php include "menu.php"?>
                <form action="" method="post">
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
                                    <option value="<?php echo $contato['id'] ?>"><?php echo $contato['contato'] ?>
                                    </option>
                            <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ramal :</label>
                                <input type="text" id="input-ramal" name="ramal" value"" class="form-control"
                                    autofocus autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input id="enviar" name="Enviar" type="submit" value="Enviar"
                                    class="MeuInput form-control"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
<html>
