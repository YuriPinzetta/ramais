<?php
class Acessos{
		public $variavelPublic = "Variável Pública<br>";
			protected $variavelProtected = "Variável Protegida<br>";
			private $variavelPrivate = "Variável Privada<br>";
			 
				public function getPublic(){
							return $this->variavelPublic; 		 			
								}
			 
				public function getProtected(){
							return $this->variavelProtected; 		 			
								}
			 
				public function getPrivate(){
							return $this->variavelPrivate; 		 			
								}
			 
				public function getMetodoPrivate(){
							return Acessos::getPrivate(); 		 			
								}	
			 
}  
 
$especificacaoAcesso = new Acessos();
echo $especificacaoAcesso->getPublic();
echo $especificacaoAcesso->getMetodoPrivate();
//echo $especificaAcesso->getPrivate(); // Dará um erro fatal
