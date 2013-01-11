/**
 * Validador de formularios
 * 
 * se valida según la etiqueta class
 * 
 * <input id="nombre" name="nombre" type="text" class="required numeric show-error"/>
 * <span class="help-error" id="err_nombre"></span>
 * 
 * Muestra los errores en una etiqueta con el id 
 * de la manera err_IdDelInput
 *
 * Copyright (c) 2013 Dailyscript - Team. http://dailyscript.com.co
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

//Array.prototype.inToArray = function(valor) { var i; for (i = 0; i < this.length; i++) { if (this[i] === valor) return true;} return false;};
/*
var elementosErr = []; 
//Tipo de validaciones, cada validación en el array corresponde a un método
var validaciones = ["requerido",'numeric','integer','ipv4'];
*/
var elemErr = [];
var tagsForm = ["input", "select", "textarea", "button"];
var validators = ["required", "numeric", "integer"];
function validForm(form, confirmation) { 
    if(confirmation!= false) {        
        if(!confirm('Está seguro de continuar con la operación?')) {
            return false;
        }        
    }    
    var enviar = true
    var form = $('form[name="'+form+'"]');
    var cont = 0;
    var campos = tagsForm.length;
    var first_input;    
    for(i = 0 ; i < campos ; i++) {                 
        form.find(tagsForm[i]).each(function(k) {
            var input = $(this);
            if(input.attr('class') == undefined) {
                return false;
            }            
            var clases = input.attr('class').split(' ');
            for(c = 0 ; c < clases.length ; c++) {
                if($.inArray(clases[c], validators) >= 0) {
                    fn = clases[c]+'("'+input.attr("id")+'")';                         
                    contenedor = input.parent();
                    if(contenedor.hasClass('controls')) {
                        contenedor = contenedor.parent();
                    }
                    if (!eval(fn)) {
                        elemErr.push("err_" + input.attr("id"));                     
                        if(contenedor.hasClass('controls') || contenedor.hasClass('control-group')) {
                            contenedor.addClass('control-error');
                        }                        
                        if(cont == 0) {
                            first_input = input;
                        }
                        cont++;
                    } else {                        
                        if(contenedor.hasClass('controls') || contenedor.hasClass('control-group')) {
                            contenedor.removeClass('control-error'); 
                        }                                                        
                    }
                }
            }            
        });        
    }    
    if (cont > 0) {
        enviar = false;
        try {
            errorForm();
        } catch(e) {
            alert('Se han encontrado errores al procesar el formulario. Por favor verifique los datos e intente nuevamente.');            
        }
        try { 
            limpiarClaves(); 
        } catch(e) { }
        setTimeout(function(){ first_input.focus(); }, 2500);
        return false;
    }    
    if(enviar) {
        //@see var,js
        DwSpinner('show');
    }    
    return enviar;
}

function required(input) {      
    field = $('#'+input);
    if (field.val() == null || field.val().length == 0 || /^\s+$/.test(field.val()) ) {
        $("#err_"+input).html('Campo requerido');
        return false;
    }
    $("#err_"+input).html('');
    return true;
}

function requerido(valor, idEtiqueta) { if (valor == null || valor.length == 0 || /^\s+$/.test(valor) ) { 
        document.getElementById(idEtiqueta).innerHTML = 'Campo requerido'; return false; }else { document.getElementById(idEtiqueta).innerHTML = '&nbsp;';return true;}}

/*
function alfabetico(valor, idEtiqueta) {
    if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) {
        if (!(/^[a-zA-ZüñÑáéíóúÁÉÍÓÚÜ\s]+$/.test(valor))) {
            document.getElementById(idEtiqueta).innerHTML = 'Introduzca solo valores alfabéticos';
            return false;
        } else {
            document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
            return true;
        }
    } else { return true; }
}

function alfanumerico(valor, idEtiqueta) {
    if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) {
        if (!(/^[a-zA-Z0-9-ZüñÑáéíóúÁÉÍÓÚÜ._\s]+$/.test(valor))) {
            document.getElementById(idEtiqueta).innerHTML = 'Introduzca solo valores alfanuméricos';
            return false;
        } else {
            document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
            return true;
        }
    } else { return true; }
}

function texto(valor, idEtiqueta) {
    if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) {
	if (!(/^[a-z0-9\sÁÉÍÓÚÑáéíóúñ.,_:;\-\&\=\*\+\/\#\%\$\"\(\)\@\/]+$/i.test(valor))) {
            document.getElementById(idEtiqueta).innerHTML = 'Ha introducido un caracter no válido';
            return false;
	} else {
            document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
            return true;
	}
    } else {        
        return true;
    }
}

function slug(valor, idEtiqueta) {
    return texto(valor,idEtiqueta);
}

function fecha(valor, idEtiqueta) {    
    if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) {                         
        if ( !(/^[-]?\d+(\.\d+)?$/.test(valor)) && !(/^\d{2,4}\/\d{1,2}\/\d{1,2}$/.test(valor)) ){
            document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
            return true;
	} else {
            document.getElementById(idEtiqueta).innerHTML = 'Fecha incorrecta. AAAA-MM-DD';
            return false;
	}
    } else { return true; }
}





function lista(valor, idEtiqueta) {
    if (valor == '') {
        document.getElementById(idEtiqueta).innerHTML = 'Seleccione un elemento de la lista';
	return false;
    } else {
        document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
        return true;
    }
}



function usuario(valor, idEtiqueta) {
    var limiteMenor = 4;
    var limiteMayor = 10;
    //^[a-z0-9ü][a-z0-9ü_]{3,9}$
    if (!(valor == null || valor.length == 0 || /^[a-z0-9_]$/.test(valor))) {
        if ( alfanumerico(valor,idEtiqueta) ){
            if ((valor.length >= limiteMenor) && (valor.length <= limiteMayor)) {
                document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
		return true;
            } else {
                document.getElementById(idEtiqueta).innerHTML = 'El usuario debe tener entre '+limiteMenor+' o '+limiteMayor+' caracteres';
		return false;
            }
	} else {
            document.getElementById(idEtiqueta).innerHTML = 'Ha introducido un caracter no válido';
            return false;
	}
    } else { return true; }
}

function pass(valor, idEtiqueta) {
    var limite = 6;
    if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) {
        if ( alfanumerico(valor,idEtiqueta) ){
            if ((valor.length >= limite) ) {
                document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
		return true;
            } else {
                document.getElementById(idEtiqueta).innerHTML = 'La contraseña debe tener entre mínimo '+limite+' caracteres';
		return false;
            }
	} else {
            document.getElementById(idEtiqueta).innerHTML = 'Haz introducido un caracter no válido';
            return false;
	}
    } else { return true; }
}

function numerico(valor, idEtiqueta) {
    if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) {
        if (! (/^[-]?\d+(\.\d+)?$/.test(valor)) ) {
            document.getElementById(idEtiqueta).innerHTML = 'Introduzca solo valores numéricos';
            return false;
        } else {            
            document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
            return true;
        }
    } else {
        return true;
    }
}

function entero(valor, idEtiqueta) { 
    if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) {
        if (!(/^(?:\+|-)?\d+$/.test(valor))) {
            document.getElementById(idEtiqueta).innerHTML = 'El número debe ser entero';
            return false;
        } else {
            document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
            return true;
        }
    } else {
        return true;
    }
}

function telefono(valor, idEtiqueta) {
    var limiteMenor = 7;
    var limiteMayor = 10;
    if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) {        
        if(numerico(valor,idEtiqueta) && entero(valor, idEtiqueta)) {
            if ((valor.length == limiteMenor) || (valor.length == limiteMayor)) {
                document.getElementById(idEtiqueta).innerHTML = '&nbsp;';
                return true;
            } else {
                document.getElementById(idEtiqueta).innerHTML = 'El número debe tener de 7 o 10 dígitos';
                return false;
            }
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function url(valor, idEtiqueta) { if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) { if (!(/^(ht|f)tp(s?)\:\/\/[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*(:(0-9)*)*(\/?)( [a-zA-Z0-9\-\.\?\,\'\/\\\+&%\$#_]*)?$/.test(valor))) { document.getElementById(idEtiqueta).innerHTML = 'La página web no es válida'; return false; } else { document.getElementById(idEtiqueta).innerHTML = '&nbsp;'; return true; } } else { return true; } }
function email(valor, idEtiqueta) { if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) { if (!(/^([a-zA-Z0-9_\.\-])+(\+[a-zA-Z0-9]+)*\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(valor))) { document.getElementById(idEtiqueta).innerHTML = 'El formato del e-mail no es válido'; return false; } else { document.getElementById(idEtiqueta).innerHTML = '&nbsp;'; return true; } } else { return true; } }


function celular(valor, idEtiqueta) { var tamano = 10; if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) { if(numerico(valor,idEtiqueta) && entero(valor, idEtiqueta)) { if (valor.length == tamano) { document.getElementById(idEtiqueta).innerHTML = '&nbsp;'; return true; } else { document.getElementById(idEtiqueta).innerHTML = 'El número debe tener 10 dígitos'; return false; } } else { return false; } } else { return true; } }
function fotografia(file, idEtiqueta) {var ext;if (!(file == null || file.length == 0 || /^\s+$/.test(file))) {ext = getFileExtension(file);if(ext != "jpeg" && ext != "jpg" && ext != "png" && ext != "gif") {document.getElementById(idEtiqueta).innerHTML = 'Formato de imagen no válido.';return false;} else {document.getElementById(idEtiqueta).innerHTML = '&nbsp;';return true;}} else { return true; }}
function getFileExtension(filename) {var i = filename.lastIndexOf(".");return (i > -1) ? filename.substring(i + 1, filename.length).toLowerCase() : "";}
function ipv4(valor, idEtiqueta) { var patronIp = new RegExp("^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$"); if (!(valor == null || valor.length == 0 || /^\s+$/.test(valor))) { if (!(patronIp.test(valor))) { document.getElementById(idEtiqueta).innerHTML = 'Dirección Ipv4 no válida'; return false; } else { valores =   valor.split("."); if(valores[0]<=255 && valores[1]<=255 && valores[2]<=255 && valores[3]<=255) { document.getElementById(idEtiqueta).innerHTML = '&nbsp;'; return true; } else { document.getElementById(idEtiqueta).innerHTML = 'Rango de dirección no válido'; return false; } } } else { return true; } }
*/
function limpiarErr() { var total = elementosErr.length;for (var i = 0; i < total; i++)document.getElementById(elementosErr.shift()).innerHTML = '&nbsp;';}
