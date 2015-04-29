 <?php
 /* Este arquivo conecta um banco de dados MySQL - Servidor = localhost*/
 $dbname="ramais"; // Indique o nome do banco de dados que serÃ¡ aberto
 $usuario="yuri"; // Indique o nome do usuÃ¡rio que tem acesso
 $password="Pinzetta"; // Indique a senha do usuÃ¡rio
 //1Âº passo - Conecta ao servidor MySQL

 //mysql_connect("localhost",$usuario,$password);

 if(!($id = mysql_connect("localhost",$usuario,$password))) {
    echo "Não foi possível estabelecer uma conexão com o gerenciador MySQL. Favor Contactar o Administrador.";
		exit;
		}

//2Âº passo - Seleciona o Banco de Dados
if(!($con=mysql_select_db($dbname,$id))) {
			echo "Não foi possível estabelecer uma conexão com o gerenciador MySQL. Favor Contactar o Administrador.";
			exit;
			}
?>

