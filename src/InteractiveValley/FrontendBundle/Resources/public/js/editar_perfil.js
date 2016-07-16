/* 
 * Author Ricardo Alcantara<richpolis@gmail.com>.
 * Twitter: @richpolis
 * 
 * Codigos fuentes
 * 
 * numberFormat: http://www.yoelprogramador.com/formatear-numeros-con-javascript/
 * 
 * Validador de numeros: http://blog.freshware.es/solo-permitir-numeros-en-input-text-html/
 * 
 * Validar email: http://ismaelgsan.com/validar-un-email-con-javascript-de-forma-rapida-y-sencilla/
 * 
 */

var $estado = null;
var $delegacion = null;
var $colonia = null;
var $codigo = null;
var formatNumber = {
    separador: ",", // separador para los miles
    sepDecimal: '.', // separador para los decimales
    formatear: function (num) {
        num += '';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
        }
        return this.simbol + splitLeft + splitRight;
    },
    new : function (num, simbol) {
        this.simbol = simbol || '';
        return this.formatear(num);
    }
}
$(document).on('ready', function () {
    $(".item-perfil").on('click', function () {
        location.href = $(this).data('url');
    });

    $("#interactivevalley_pakmailbundle_perfil_direccionFiscal_pais").on('change', function () {
        if ($(this).val() == "Mexico") {
            bootbox.prompt("¿Cual es tu código postal?", function (codigo) {
                if (codigo === null) {
                    alert("El valor no fue ingresado");
                } else {
                    llamarAMetodosCodigoPostal(codigo, "#interactivevalley_pakmailbundle_perfil_direccionFiscal");
                }
            });
        } else if ($(this).val() == "Otro") {
            agregarPais("#interactivevalley_pakmailbundle_perfil_direccionFiscal");
            activarCampos("#interactivevalley_pakmailbundle_perfil_direccionFiscal");
        } else {
            activarCampos("#interactivevalley_pakmailbundle_perfil_direccionFiscal");
        }
    });

    $("#interactivevalley_pakmailbundle_perfil_direccionRemitente_pais").on('change', function () {
        if ($(this).val() == "Mexico") {
            bootbox.prompt("¿Cual es tu código postal?", function (codigo) {
                if (codigo === null) {
                    alert("El valor no fue ingresado");
                } else {
                    llamarAMetodosCodigoPostal(codigo, "#interactivevalley_pakmailbundle_perfil_direccionRemitente");
                }
            });
        } else if ($(this).val() == "Otro") {
            agregarPais("#interactivevalley_pakmailbundle_perfil_direccionRemitente");
            activarCampos("#interactivevalley_pakmailbundle_perfil_direccionRemitente");
        } else {
            activarCampos("#interactivevalley_pakmailbundle_perfil_direccionRemitente");
        }
    });

    $("#interactivevalley_pakmailbundle_perfil_direccionDestino_pais").on('change', function () {
        if ($(this).val() == "Mexico") {
            bootbox.prompt("¿Cual es tu código postal?", function (codigo) {
                if (codigo === null) {
                    alert("El valor no fue ingresado");
                } else {
                    llamarAMetodosCodigoPostal(codigo, "#interactivevalley_pakmailbundle_perfil_direccionDestino");
                }
            });
        } else if ($(this).val() == "Otro") {
            agregarPais("#interactivevalley_pakmailbundle_perfil_direccionDestino");
            activarCampos("#interactivevalley_pakmailbundle_perfil_direccionDestino");
        } else {
            activarCampos("#interactivevalley_pakmailbundle_perfil_direccionDestino");
        }
    });

    revisarCampos("#interactivevalley_pakmailbundle_perfil_direccionFiscal");
    revisarCampos("#interactivevalley_pakmailbundle_perfil_direccionRemitente");
    revisarCampos("#interactivevalley_pakmailbundle_perfil_direccionDestino");

    var $requeridos = $(".required");
    $requeridos.on("blur", function () {
        var $input = $(this);
        var $parent = $input.parent();
        if ($input.val() == "") {
            $parent.addClass('has-error').removeClass('has-success');
        } else {
            if($input.hasClass('email')){
                if(!validarEmail($input.val())){
                  $parent.addClass('has-error').removeClass('has-success');  
                }else{
                   $parent.addClass('has-success').removeClass('has-error'); 
                }
            }else{
                $parent.addClass('has-success').removeClass('has-error');
            }
        }
    });
    
    $requeridos.parent().addClass('form-group');
    var $asegurarEnvio = $("#interactivevalley_pakmailbundle_perfil_asegurarEnvio");
    var $montoSeguro = $("#interactivevalley_pakmailbundle_perfil_montoSeguro");
    var $importeSeguro = $("#interactivevalley_pakmailbundle_perfil_importeSeguro");
    var $precio = $("#interactivevalley_pakmailbundle_perfil_precio");
    var $valorDeclarado = $("#interactivevalley_pakmailbundle_perfil_valorDeclarado");
    var $generaGastosAduana = $("#interactivevalley_pakmailbundle_perfil_generarGastosAduana");
    
    $asegurarEnvio.on('click',function(e){
        if($(this).is(":checked")){
            $montoSeguro.parent().parent().addClass('has-warning');
            $importeSeguro.parent().parent().addClass('has-warning');
        }else{
            $montoSeguro.parent().removeClass('has-warning');
            $importeSeguro.parent().parent().removeClass('has-warning');
        }
    });
    
    $generaGastosAduana.on('click',function(e){
        if($(this).is(":checked")){
            $valorDeclarado.parent().parent().addClass('has-warning');
        }else{
            $valorDeclarado.parent().parent().removeClass('has-warning');
        }
    });
    
    $('.money,.number').on('keypress', function (e) {
        var keynum = window.event ? window.event.keyCode : e.which;
        
        if ((keynum == 0) || (keynum == 8) || (keynum == 46))
            return true;

        return /\d/.test(String.fromCharCode(keynum));
    });
    
    $precio.on('keyup', function (e) {
        $(".visualizar_precio").html(formatNumber.new($(this).val(), "$"));
    });
    
    $valorDeclarado.on('keyup', function (e) {
        $(".visualizar_valor_declarado").html(formatNumber.new($(this).val(), "$"));
    });
    
    $montoSeguro.on('keyup', function (e) {
        $(".visualizar_monto_seguro").html(formatNumber.new($(this).val(), "$"));
        calcularImporteSeguro();
    });
    
    $montoSeguro.on('change', function (e) {
        calcularImporteSeguro();
    });
    
    $importeSeguro.attr('readonly','readonly');
    
    function calcularImporteSeguro(){
        var monto = $montoSeguro.val();
        if (monto > 0) {
            $importeSeguro.val(monto * .03);
            $(".visualizar_importe_seguro").html(formatNumber.new($importeSeguro.val(), "$"));
        }
    }
    
    $precio.keyup();
    $montoSeguro.keyup();
    $valorDeclarado.keyup();
    
    $("form").on('submit',function(e){
       var liberar = true;
       var $emails = $(".email");
       for(var cont=0;cont<$emails.length;cont++){
           if(!validarEmail($emails[cont].value)){
              alert("El email: " + $emails[cont].value+' es incorrecto');
              var offset = $($emails[cont]).offset().top - 180;
               $('html, body').animate({ scrollTop : offset }, 'slow');
              liberar = false;
              break;
          }
       }
       for(var cont=0;cont<$requeridos.length;cont++){
           if($requeridos[cont].value == ""){
              alert("Favor de ingresar todos los datos requeridos");
              var offset = $($requeridos[cont]).offset().top - 180;
               $('html, body').animate({ scrollTop : offset }, 'slow');
              liberar = false;
              break;
          }
       }
       return liberar;
    });
    
    function validarEmail( email ) {
        expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return ( expr.test(email) );
    }

});
function llamarAMetodosCodigoPostal(codigo, idDireccion) {
    if (codigo.length < 5) {
        codigo = "0" + codigo;
    }
    //$("#interactivevalley_pakmailbundle_perfil_direccionFiscal" + elemento)
    $estado = $(idDireccion + "_estado");
    $delegacion = $(idDireccion + "_delegacion");
    $colonia = $(idDireccion + "_poblacion");
    $codigo = $(idDireccion + "_cp");
    var url = 'https://api-codigos-postales.herokuapp.com/codigo_postal/' + codigo;
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data.length > 0) {
                $estado.val(data[0].estado);
                $delegacion.val(data[0].municipio);
                $codigo.val(codigo);
                desactivarCampos(idDireccion);
            } else {
                alert("No se encontro el código postal ingresado");
                return false;
            }
            if (data.length == 1) {
                // solo hay una colonia
                $colonia.val(data[0].colonia);
            } else {
                // cuando hay mas de una colonia  
                var options = "";
                for (var cont = 0; data.length > cont; cont++) {
                    options += '<option value="' + data[cont].colonia + '">' + data[cont].colonia + '</option>';
                }

                bootbox.dialog({
                    title: "Selecciona tu colonia.",
                    message: '<div class="row">  ' +
                            '<div class="col-md-12"> ' +
                            '<form class="form-horizontal"> ' +
                            '<div class="form-group"> ' +
                            '<label class="col-md-4 control-label" for="colonia">Colonia</label> ' +
                            '<div class="col-md-4"> ' +
                            '<select id="colonia" name="colonia" type="text" placeholder="Seleccionar colonia" class="form-control"> ' +
                            options +
                            '</select>' +
                            '</div> ' +
                            '</div> ' +
                            '</form> </div>  </div>',
                    buttons: {
                        success: {
                            label: "Seleccionar",
                            className: "btn-success",
                            callback: function () {
                                var colonia = $('#colonia').val();
                                $colonia.val(colonia)
                            }
                        }
                    }
                });
            }
        }
    });
}
function agregarPais(idDireccion) {
    var $pais = $(idDireccion + "_pais");
    var $opciones = $(idDireccion + "_pais option");
    bootbox.prompt("Cual es el pais?", function (paisIngresado) {
        debugger;
        if (paisIngresado === null) {
            alert("No se ha ingresado ningun pais");
        } else {
            debugger;
            var pais = new String(paisIngresado);
            pais.toLowerCase();
            var Letra = pais[0];
            var encontrado = false;
            Letra.toUpperCase();
            pais[0] = Letra;
            for (var cont = 0; cont < $opciones.length; cont++) {
                if ($opciones[cont].value == pais) {
                    encontrado = true;
                    break;
                }
            }
            if (!encontrado) {
                var opcionUltima = $opciones[$opciones.length - 1];
                var opcionOtro = $('<option value="Otro">');
                opcionOtro.html("Otro...");
                opcionUltima.value = pais;
                opcionUltima.innerHTML = pais;
                $pais.append(opcionOtro);
            }
        }
    });
}
function activarCampos(idDireccion) {
    //$("#interactivevalley_pakmailbundle_perfil_direccionFiscal" + elemento)
    /*$(idDireccion+ "_estado").removeAttr('readonly');
     $(idDireccion+ "_delegacion").removeAttr('readonly');
     $(idDireccion+ "_poblacion").removeAttr('readonly');
     $(idDireccion+ "_cp").removeAttr('readonly');*/
}

function desactivarCampos(idDireccion) {
    //$("#interactivevalley_pakmailbundle_perfil_direccionFiscal" + elemento)
    /*$(idDireccion+ "_estado").attr('readonly','readonly');
     $(idDireccion+ "_delegacion").attr('readonly','readonly');
     $(idDireccion+ "_poblacion").attr('readonly','readonly');
     $(idDireccion+ "_cp").attr('readonly','readonly');*/
}

function revisarCampos(idDireccion) {
    if ($(idDireccion + "_pais").val() == "Mexico") {
        //desactivarCampos(idDireccion)
    } else {
        //activarCampos(idDireccion)
    }
}

