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
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/../views',
));


$app->get('/home', function (Request $request) use ($app) { 
		return $app['twig']->render('home.twig', array(
			'error' => $request->get('error')
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

$app->get('/logout', function () use ($app) { 
	$app['session']->clear();
	return $app->redirect('/login');
});

$app->get('/contato', function (Request $request) use ($app) { 
	return $app['twig']->render('contato.twig', array(
		'message' => $app['session']->getFlashBag()->get('message')
	));
});

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
});


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
});

$app->get('/ramais', function (Request $request) use ($app) { 
	$pdo = db();
	$ramalDao = new RamalDAO($pdo);
	$contatoDao = new ContatoDAO($pdo, $ramalDao);
	$contatos = $contatoDao->listar(array());
	return $app['twig']->render('ramais.twig', array(
		'message' => $app['session']->getFlashBag()->get('message'),
		'contatos' => $contatos
	));
});

$app->run();
