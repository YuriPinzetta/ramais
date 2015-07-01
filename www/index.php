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
		return $app->json(array('success' => true));
	}
	return $app->json(array(
		'success' => false,
		'message' => 'Usuário e senha não existe'
	));
});

$app->get('/', function () use ($app) { 
	return $app->redirect('/home');
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
	return $app->json(array('success' => true));
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
		return $app->json(array('success' => true));
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
	$ramais_id = $request->request->get('ramais_id'); 
	$id_contato = $request->query->get('id_contato'); 
	$contato = $request->request->get('contato');
	$cargos = $request->request->get('cargos');
	$tipos = $request->request->get('tipos');
	$numeros = $request->request->get('numeros');
	$ramalDao = new RamalDAO($pdo);
	$contatoDao = new ContatoDAO($pdo, $ramalDao);
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
	return $app->json(array('success' => true));
})->before($temUsuario);

$app->delete('/contato', function (Request $request) use ($app) {
	$pdo = db();
	$id_contato = $request->query->get('id_contato'); 
	$ramalDao = new RamalDAO($pdo);
	$contatoDao = new ContatoDAO($pdo, $ramalDao);
	$contato = array(
		'id_contato' => $id_contato
	);
	try {
			$pdo->beginTransaction();
			$ramalDao->deleta($contato);
			$contatoDao->deleta($contato);
			$pdo->commit();
			return $app->json(array('success' => true));
	} catch (Exception $e) {
			$pdo->rollBack();
			return $app->json(array('success' => false, 'message' => $e->getMessage()));
	}
})->before($temUsuario);

$app->delete('/ramais', function (Request $request) use ($app) {
	$pdo = db();
	$id_contato = $request->query->get('id_contato'); 
	$ramalDao = new RamalDAO($pdo);
	$contato =  array(
		'id_contato' => $id_contato
	);
	if ($ramalDao->deleta($contato)) {
		return $app->json(array('success' => true));
	}
	return $app->json(array('success' => false));
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

$app->get('/cadastro/usuario/form', function (Request $request) use ($app) {
	$id = $request->query->get('id');
	$pdo = db();
	$usuarioDao = new UsuarioDAO($pdo);
	$params = array();
	$usuario = $usuarioDao->consulta($id);
	if ($usuario) {
		$params['usuario'] = $usuario;
	}
	return $app['twig']->render('form.twig', $params);
})->before($temUsuario);

$app->post('/cadastro/usuario/form', function (Request $request) use ($app) {
	$usuario = $request->request->get('usuario'); 
	$senha = $request->request->get('senha'); 
	$pusuario = array_sum($request->request->get('pusuario')); 
	$pcontato = array_sum($request->request->get('pcontato')); 
	$cadastro = array(
		'usuario' => $usuario,
		'senha' => $senha,
		'pusuario' => $pusuario,
		'pcontato' => $pcontato
	);
	$pdo = db();
  $usuarioDao = new UsuarioDAO($pdo);
	if (!$usuarioDao->verifica($cadastro)) {
		$usuarioDao->cadastra($cadastro);
		return $app->json(array('success' => true));
	}
	return $app->json(array(
		'success' => false,
		'message' => 'Usuário já existe.'
	));
})->before($temUsuario);

$app->get('/cadastro/usuario/table', function (Request $request) use ($app) {
	$data = $request->query->all();
	$pdo = db();
	$usuarioDao = new UsuarioDAO($pdo);
	$usuarios = $usuarioDao->listar($data);
	return $app['twig']->render('table.twig', array(
		'usuarios' => $usuarios
	));
})->before($temUsuario);

$app->get('/cadastro/usuario', function (Request $request) use ($app) {
  return $app['twig']->render('usuario.twig');
})->before($temUsuario);

$app->post('/cadastro/usuario', function (Request $request) use ($app) {
	$usuario = $request->request->get('usuario'); 
	$senha = $request->request->get('senha'); 
	$pusuario = array_sum($request->request->get('pusuario')); 
	$pcontato = array_sum($request->request->get('pcontato')); 
	$cadastro = array(
		'usuario' => $usuario,
		'senha' => $senha,
		'pusuario' => $pusuario,
		'pcontato' => $pcontato
	);
	$pdo = db();
  $usuarioDao = new UsuarioDAO($pdo);
	if (!$usuarioDao->verifica($cadastro)) {
		$usuarioDao->cadastra($cadastro);
		return $app->json(array('success' => true));
	}
	return $app->json(array(
		'success' => false,
		'message' => 'Usuário já existe.'
	));
})->before($temUsuario);

$app->put('/cadastro/usuario', function (Request $request) use ($app) {
	$id = $request->request->get('id'); 
	$usuario = $request->request->get('usuario'); 
	$senha = $request->request->get('senha');
	if($senha == ""){
		$senha = null;
	}
	$pusuario = array_sum($request->request->get('pusuario')); 
	$pcontato = array_sum($request->request->get('pcontato')); 
	$cadastro = array(
		'id' => $id,
		'usuario' => $usuario,
		'senha' => $senha,
		'pusuario' => $pusuario,
		'pcontato' => $pcontato
	);
	$pdo = db();
  $usuarioDao = new UsuarioDAO($pdo);
	if ($usuarioDao->verifica($cadastro)) {
		$usuarioDao->altera($cadastro);
		return $app->json(array('success' => true));
	}
	return $app->json(array(
		'success' => false,
		'message' => 'Usuário não existe.'
	));
})->before($temUsuario);

$app->delete('/cadastro/usuario', function (Request $request) use ($app) {
	$id = $request->query->get('id');
	$pdo = db();
  $usuarioDao = new UsuarioDAO($pdo);
	$usuarioDao->deleta($id);

	return $app->json(array('success' => true));
})->before($temUsuario);


$app->run();
