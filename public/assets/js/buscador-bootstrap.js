// ----------------------------------------------------
// Buscador con botones de limpiar y buscar
// (C) 2021. Matias Perez para New Rol IT
// ----------------------------------------------------
// form = el id del formulario padre 
// (necesario para poder hacer el submit)
//
// valor = el valor que tiene el buscador 
// (si no se viene de una busqueda deberia estar vacio)
//
// NOTA: Es necesario el custom.css para los iconos
// ----------------------------------------------------

class Buscador extends HTMLElement
{
    constructor() {
        super();

        var myid = $(this).attr("id")? $(this).attr("id") : 'buscador';
        var buscarval = $(this).attr("value") ? $(this).attr("value") : '';
        var placeholder = $(this).attr("placeholder") ? $(this).attr("placeholder") : '';
        var formulario = $(this).attr("form");
        //console.log("Inicializando buscador: " + myid + " - valor: " + buscarval);

        this.classList.add("form-group");
        this.setAttribute("id", myid);
    
        var inputtext = document.createElement('input');
        inputtext.classList.add("form-control");
        inputtext.classList.add("text");
        inputtext.setAttribute("placeholder", placeholder);
        inputtext.setAttribute("name", "search");
        inputtext.setAttribute("value", buscarval);
        $('#'+myid).append(inputtext);
    
        var limpiar = document.createElement('a');
        limpiar.classList.add("fa");
        limpiar.classList.add("fa-times-circle");
        limpiar.classList.add("invisible");
        limpiar.setAttribute("id", "limpiar");
        limpiar.setAttribute("href", "");
        $('#'+myid).append(limpiar);
    
        var separador = document.createElement('p');
        separador.classList.add("separator");
        separador.classList.add("invisible");
        separador.innerHTML = "|"
        $('#'+myid).append(separador);
    
        var buscar = document.createElement('a');
        buscar.classList.add("fa");
        buscar.classList.add("fa-search");
        buscar.classList.add("disabled");
        buscar.setAttribute("id", "buscar");
        buscar.setAttribute("href", "");
        $('#'+myid).append(buscar);
    
    
        if ($(inputtext).val()!=="")
        {
            $(limpiar).removeClass("invisible");
            $(separador).removeClass("invisible");
        }
    
        $(limpiar).click(function(e) {
            e.preventDefault()
            $(inputtext).val("");
            $(buscar).addClass("disabled");
            $(limpiar).addClass("invisible");
            $(separador).addClass("invisible");
            $(inputtext).focus();
            if (buscarval!=="") {
                $(inputtext).val("");
                $('#'+formulario).submit();
            }
        });
    
        $(inputtext).on('change keyup paste', function () {
            if ($(inputtext).val()=="" && buscarval=="") {
              $(buscar).addClass("disabled");
              $(limpiar).addClass("invisible");
              $(separador).addClass("invisible");
            } else {
              $(buscar).removeClass("disabled");
              $(limpiar).removeClass("invisible");
              $(separador).removeClass("invisible");
            }
        });
        
        $(buscar).click(function(e) {
            e.preventDefault()
            $('#'+formulario).submit();
        });
    
    }

}
customElements.define('mi-buscador', Buscador);
