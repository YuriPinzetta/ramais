//funçao que deixa colocar apenas numeros.
function apenasNumero(event){
	var numeros = event.which || event.keyCode;
	if (numeros >= '0'.charCodeAt(0) && numeros <= '9'.charCodeAt(0))
		return true;
	event.preventDefault();
	return false;
}
//funçao que bloqueia qualquer tecla
function BloqueadoTecla(){
	this.valor_anterior = null;
}
BloqueadoTecla.prototype.bloqueia = function (event) {
	var input = event.target;
	var value = input.value;
	var re = /[3-9]/;
	var match = value.match(re);
	if (match) {
		alert('Letra proibida: ' + match[0]);
		this.valor_anterior = this.valor_anterior || input.defaultValue;
		input.value = this.valor_anterior;
	} else {
		this.valor_anterior = input.value;
	}
};

var valor_anterior, maxLength = 0;

function bloqueiaTecla(event) {
	var input = event.target;
	var value = input.value;
	var re = /[3-9]/;
	var match = value.match(re);
	if (match) {
		alert('Letra proibida: ' + match[0]);
		valor_anterior = valor_anterior || input.defaultValue;
		input.value = valor_anterior;
	} else {
		valor_anterior = input.value;
	}
}

//funcao que conta quantos caracteres foram apertados.
function contador(event) {
	var input = event.target;
	var total = input.value.length;
	var quantidade = maxLength;
	var resto;
	if(total <= quantidade){
		resto = quantidade - total;
	}
	else{
		resto = 0;
		value = input.value.substr(0, 50);
		input.value = value;
	}
	document.getElementById("total").innerHTML = resto;
}
function submit(event) {
	var empty = /^\s*$/;
	var input1 = document.getElementById("input_cargos");
	var input2 = document.getElementById("input_contato");
	if (empty.test(input1.value) || empty.test(input2.value)) {
		event.preventDefault();
	}
}
