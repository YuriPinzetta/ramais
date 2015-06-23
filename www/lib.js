//funçao que deixa colocar apenas numeros.
function apenasNumero(event){
	var numeros = event.which || event.keyCode;
	if (numeros >= '0'.charCodeAt(0) && numeros <= '9'.charCodeAt(0))
		return true;
	event.preventDefault();
	return false;
}
//funçao que bloqueia tecla contato qualquer tecla
function BloqueadoTecla(){
	this.valor_anterior = null;
}
BloqueadoTecla.prototype.bloqueia = function (event) {
	var input = event.target;
	var value = input.value;
	var re = /[^0-9a-zA-Z\s]/;
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

function ComeComeLoader($loader, $btn){
	return {
		load: function (on) {
			if (on) {
				$loader.addClass('loader-show');
				if ($btn) {
					$btn.prop('disabled', true);
				}
			} else {
				$loader.removeClass('loader-show');
				if ($btn) {
					$btn.prop('disabled', false);
				}
			}
		}
	};
}

function trataSubmitAjax(url, loader) {
	if (loader === undefined) {
		loader = ComeComeLoader($('.loader'));
	}
	return function (e) {
		var $form = $(this);
		var action = $form.prop('action');
		var formData = $form.serialize();

		// Previne submit
		e.preventDefault();

		loader.load(true);
		$.post(action, formData, function (data) {
			loader.load(false);
			if (data.message) {
				alert(data.message);
			}
			if (data.success) {
				window.location = url;
			}
		});
	};
}
