<?php
namespace amixsi;

class ConstrutorDestrutor{
	private $varMethod;
	function __construct()
	{
		$this->varMethod = "Construtor()";
		echo "Método {this->varMethod}<br>";
	}
}
$construtorDestrutor = new ConstrutorDestrutor();
unset($construtorDestrutor);

class ConstrutorDestrutorFilho extends ConstrutorDestrutor{
	function __construct()
	{
		parent::__construct();
		echo "Metodo filho construtor";
	}
	function __destruct()
	{
		parent::__destruct();
		echo "Metodo filho destrutor";
	}
}
$construtorDestrutorFilho = new ConstrutorDestrutorFilho();
