<?php

require_once __DIR__.'/../vendor/autoload.php'; 

use Symfony\Component\HttpFoundation\Request;
use amixsi\Contato;
use amixsi\ContatoDAO;
use amixsi\Ramal;
use amixsi\RamalDAO;
use amixsi\Usuario;
use amixsi\UsuarioDAO;

$app = new Silex\Application(); 

$app['debug'] = true;

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/../views',
));

$temUsuario =	function (Request $request) use ($app) {
	$session = $app['session'];
	if (!$session->has('usuario')) {
		return $app->redirect('/login');
	}
};

$app->get('/test-jquery', function (Request $request) use ($app) { 
	return $app['twig']->render('test-jquery.twig');
});

$app->get('/test-message', function (Request $request) use ($app) { 
  $pdo = db();
  $ramalDao = new RamalDAO($pdo);
  $contatoDao = new ContatoDAO($pdo, $ramalDao);
	$contatos = $contatoDao->listar(array());
	$contatos2 = array();
	foreach ($contatos as $contato) {
		$contatos2[] = array(
			'id' => $contato->getId(),
			'nome' => $contato->getNome()
		);
	}
	return $app->json(array(
		'message' => 'Hello world!',
		'contatos' => $contatos2
	));
});

$app->get('/login', function (Request $request) use ($app) { 
	return $app['twig']->render('login.twig', array(
		'message' => $app['session']->getFlashBag()->get('message')
	));
});

$app->post('/login', function (Request $request) use ($app) { 
	$data = $request->request->all();
  $pdo = db();
  $usuarioDao = new UsuarioDAO($pdo);
  $usuario = $usuarioDao->valida($data);
	if ($usuario !== null) {
		$app['session']->set('usuario', $usuario);
		return $app->redirect('/home');
	}
	$app['session']->getFlashBag()->add('message', 'Usuário e senha não existe');
	return $app->redirect('/login');
});

$app->get('/home', function (Request $request) use ($app) { 
		return $app['twig']->render('home.twig', array(
			'error' => $request->get('error')
		));
})->before($temUsuario);


$app->get('/logout', function () use ($app) { 
	$app['session']->clear();
	return $app->redirect('/login');
})->before($temUsuario);

$app->get('/contato', function (Request $request) use ($app) { 
	return $app['twig']->render('contato.twig', array(
		'message' => $app['session']->getFlashBag()->get('message')
	));
})->before($temUsuario);

$app->post('/contato', function (Request $request) use ($app) { 
  try {
		$data = $request->request->all();
  	$contato = Contato::fromArray($data);
  } catch (Exception $ex) {
		$app['session']->getFlashBag()->add('message', $ex->getMessage());
		return $app->redirect('/contato');
  }
	$pdo = db();
  $contatoDao = new ContatoDAO($pdo);
  $contatoDao->inserir($contato);
	return $app->redirect('/home');
})->before($temUsuario);


$app->post('/ramais', function (Request $request) use ($app) {
	$pdo = db();
	$data = $request->request->all();
	$ramalDao = new RamalDAO($pdo);
	$contatoDao = new ContatoDAO($pdo, $ramalDao);
	$contatos = $contatoDao->listar(array());
    try {
        $ramal = Ramal::fromArray($data);
    } catch (Exception $ex) {
				$app['session']->getFlashBag()->add('message', $ex->getMessage());
    }
    $ramalDao->inserir($ramal);
    return $app->redirect('/home');
})->before($temUsuario);

$app->get('/ramais', function (Request $request) use ($app) { 
	$pdo = db();
	$ramalDao = new RamalDAO($pdo);
	$contatoDao = new ContatoDAO($pdo, $ramalDao);
	$contatos = $contatoDao->listar(array());
	return $app['twig']->render('ramais.twig', array(
		'message' => $app['session']->getFlashBag()->get('message'),
		'contatos' => $contatos
	));
})->before($temUsuario);

$app->get('/consulta', function (Request $request) use ($app) {
	$data = $request->query->all();
	$pdo = db();
	$ramalDao = new RamalDAO($pdo);
	$contatoDao = new ContatoDAO($pdo, $ramalDao);
	$todos_contatos = $contatoDao->listar(array());
	$contato_selecionado = null;
  $contatos = array();
	if (count($data)) {
  	$contatos = $contatoDao->listar($data);
	}
	$todos_cargos = $contatoDao->listarCargos(array());
	$cargo_selecionado = null;
	$todos_tipos = array(
  	"Interno",
    "Casa",
    "Celular",
    "Notebook"
	);
	$tipos_selecionado = null;
	return $app['twig']->render('consulta.twig', array(
		'todos_contatos' => $todos_contatos,
		'contato_selecionado' => $contato_selecionado,
		'contatos' => $contatos,
		'todos_cargos' => $todos_cargos,
		'cargo_selecionado' => $cargo_selecionado,
		'todos_tipos' => $todos_tipos,
		'tipos_selecionado' => $tipos_selecionado
	));
})->before($temUsuario);

$app->post('/edicao', function (Request $request) use ($app) {
	$pdo = db();
	$alterar = $request->request->get('Alterar'); 
	$ramais_id = $request->request->get('ramais_id'); 
	$id_contato = $request->query->get('id_contato'); 
	$contato = $request->request->get('contato');
	$cargos = $request->request->get('cargos');
	$tipos = $request->request->get('tipos');
	$numeros = $request->request->get('numeros');
	$ramalDao = new RamalDAO($pdo);
	$contatoDao = new ContatoDAO($pdo, $ramalDao);
	if ($alterar) {
		$contato = array(
			'id_contato' => $id_contato,
			'contato' => $contato,
			'cargos' => $cargos
		);
		$ramais = array();
		if($ramais_id && is_array($ramais_id)) {
			foreach($ramais_id as $index => $id) {
				$ramal = array(
					'id' => $id,
					'tipos' => $tipos[$index],
					'ramal' => $numeros[$index]
				);
				$ramais[] = $ramal;
			}
		}
    $contatoDao->altera($contato, $ramais);
  	return $app->redirect('/consulta?id_contato='.$id_contato);
	}
	$deletaRamais = $request->request->get('deletaRamais'); 
	if($deletaRamais)	{
		$contato =  array(
			'id_contato' => $id_contato
		);
    if ($ramalDao->deleta($contato)) {
  		return $app->redirect('/home');
    }
	}
	$deletaContato = $request->request->get('deletaContato');
	if ($deletaContato) {
    $contato = array(
            'id_contato' => $id_contato
        );
    try {
        $pdo->beginTransaction();
        $ramalDao->deleta($contato);
        $contatoDao->deleta($contato);
        $pdo->commit();
  			return $app->redirect('/home');
    } catch (Exception $e) {
        $pdo->rollBack();
				$app['session']->getFlashBag()->add('message', $ex->getMessage());
  			return $app->redirect('/home?error=1');
    }
}
})->before($temUsuario);

$app->get('/edicao', function (Request $request) use ($app) { 
	$pdo = db();
	$ramalDao = new RamalDAO($pdo);
	$contatoDao = new ContatoDAO($pdo, $ramalDao);
	$ramais = array();
	$contato = $contatoDao->consulta($request->query->get('id_contato'));
	$ramais = $ramalDao->listar($_GET);
	$todos_tipos = array(
    "Interno",
    "Casa",
    "Celular",
    "Notebook"
	);
	return $app['twig']->render('edicao.twig', array(
		'message' => $app['session']->getFlashBag()->get('message'),
		'contato' => $contato,
		'ramais' => $ramais,
		'todos_tipos' => $todos_tipos
	));
})->before($temUsuario);

$app->post('/cadastro', function (Request $request) use ($app) {
	$pdo = db();
  $usuarioDao = new UsuarioDAO($pdo);
  $usuarioDao->verifica($_POST);
  return $app->redirect('/login');
})->before($temUsuario);

$app->get('/cadastro', function (Request $request) use ($app) {
  return $app['twig']->render('cadastro.twig');
})->before($temUsuario);

$app->run();
