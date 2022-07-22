@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">Modificar regla</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">

      <form action="{{ route('reglas.modificar', $regla->id) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
  
        <div class="form-group">
          <label for="descripcion">Descripcion</label>
          <input class="form-control" id="descripcion" name="descripcion" 
          value="{{ $regla->descripcion }}" autofocus placeholder="Ingrese la descripción">
        </div>

        <label>Condiciones</label>
        <div class="bg-slate p-2">
          <div id="listado">

            @if ($regla->condiciones)
  
              @foreach ($regla->condiciones as $con)
              <div class="card slate">
                <div class="d-flex" style="padding: .5rem 1rem;">
                  <span class="pr-3"><b>
                    @if ($con->valor=='dia') Día del mes
                    @elseif ($con->valor=='remitente') Remitente
                    @elseif ($con->valor=='cliente') Cliente
                    @elseif ($con->valor=='area') Area
                    @elseif ($con->valor=='modulo') Módulo
                    @elseif ($con->valor=='tipo_incidente') Tipo de incidente
                    @elseif ($con->valor=='tipo_servicio') Tipo de servicio
                    @endif
                  </b></span>
                  <span>
                    @if ($con->operador=='entre') entre {{ $con->minimo }} y {{ $con->maximo }}
                    @elseif ($con->valor=='dia') igual a {{ $con->igual }} 
                    @else {{ $con->helper }} 
                    @endif
                  </span>
                  <input type="hidden" name="condiciones[]" value="{{ $con->valor }}">
                  <input type="hidden" name="operador[]" value="{{ $con->operador }}">
                  <input type="hidden" name="minimo[]" value="{{ $con->minimo }}">
                  <input type="hidden" name="maximo[]" value="{{ $con->maximo }}">
                  <input type="hidden" name="igual[]" value="{{ $con->igual }}">
                  <input type="hidden" name="seleccion[]" value="{{ $con->igual }}">
                  <input type="hidden" name="texto[]" value="{{ $con->helper }}">
                </div>
                <span class="borrar_condicion fa fa-trash"></span>
              </div>
              @endforeach
  
            @else
              <p id="vacio">No hay condiciones</p>
            @endif

          </div>

          <div class="botonera p-0 m-0 mt-3">
            <span class="col-auto btn btn-outline-slate btn-sm mb-1 pt-0 pl-3 pr-3" 
              data-toggle="modal" data-target="#agregarCondicion" id="btn_agregar">
                Agregar condición
            </span>
          </div>

        </div>

        <div class="form-group col-6 pl-0 mt-3">
          <label for="formControlRange" id="texto_prioridad">Prioridad: {{ $regla->pondera }}</label>
          <input type="range" id="prioridad" class="form-control-range mt-2" name="prioridad" 
            min="0" max="100" step="5" value="{{ $regla->pondera }}">
        </div>
        
  
        <button type="submit" class="col-auto btn btn-outline-slate mt-2">Guardar cambios</button>
  
      </form>

    </div>


    <!-- Modal Agregar condicion -->
    <div class="modal fade" id="agregarCondicion" tabindex="-1" role="dialog" aria-labelledby="agregarCondicionLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="agregarCondicion">Agregar condición</h5>
            </div>
            <div class="editor" style="margin:15px;">
                <form action="" id="agregaCondicion">
                  
                  <div class="form-group">
                    <label for="valor">Campo a comprobar</label>
                    <select id="valor" name="valor" class="form-control">
                      <option value='dia'>Día del mes</option>
                      <option value='remitente'>Remitente</option>
                      <option value='cliente'>Cliente</option>
                      <option value='area'>Area</option>
                      <option value='modulo'>Módulo</option>
                      <option value='tipo_servicio'>Tipo de servicio</option>
                      <option value='tipo_incidente'>Tipo de incidente</option>
                    </select>
                  </div>
                  
                  <div class="row pl-3 pr-0" id="comparador1">
                    <div class="form-group">
                      <label for="operador">Operador</label>
                      <select id="operador" name="operador" class="form-control">
                      </select>
                    </div>
                                  
                    <div class="form-group col-4 pl-3 pr-2" id="minimo">
                      <label for="minimo">Valor mínimo</label>
                      <input type="number" min=1 max=31 class="form-control" id="minimo_val" name="minimo" value=1>
                    </div>
          
                    <div class="form-group col-4 pl-2 pr-0" id="maximo">
                      <label for="maximo">Valor máximo</label>
                      <input type="number" min=1 max=31 class="form-control" id="maximo_val" name="maximo" value=1>
                    </div>

                    <div class="form-group col-4 pl-3 pr-0" id="igual">
                      <label for="maximo">Valor</label>
                      <input type="number" class="form-control" name="igual" id="igual_val" value=1>
                    </div>
                    
                  </div>

                  <div class="row pl-3 pr-3 mb-3" id="comparador2">

                    <label for="valor">Igual a</label>
                    <select id="seleccion" name="seleccion" class="form-control">
                    </select>

                  </div>

                </form>
                <button onclick="agregaCondicion()" class="btn btn-outline-slate mt-2">Agregar</button>
            </div>
        </div>
      </div>
    </div>

    
</div>

@endsection


@push('scripts')

<script>

  var slider = document.getElementById("prioridad");
  var output = document.getElementById("texto_prioridad");

  slider.oninput = function() {
    output.innerHTML = 'Prioridad: ' + this.value;
  }

  $('#valor').on('change', function ()
  {
    $('#operador').empty();
    
    if (this.value=='dia')
    {
      $('#comparador2').attr('hidden', true);
      $('#comparador1').attr('hidden', false);

      var cont = document.createElement('option');
      cont.setAttribute('value', 'igual');
      cont.innerHTML = 'Igual a';
      $('#operador').append(cont);

      var cont2 = document.createElement('option');
      cont2.setAttribute('value', 'entre');
      cont2.innerHTML = 'Entre valores';
      $('#operador').append(cont2);
    }
    else
    {
      $('#comparador1').attr('hidden', true);
      $('#comparador2').attr('hidden', false);

      $('#seleccion').empty();

      var obj = undefined;

      if (this.value=='remitente')
        obj = <?php echo json_encode($usuarios); ?>;

      else if (this.value=='area')
        obj = <?php echo json_encode($areas); ?>;

      else if (this.value=='modulo')
        obj = <?php echo json_encode($modulos); ?>;

      else if (this.value=='tipo_incidente')
        obj = <?php echo json_encode($tipo_incidentes); ?>;

      else if (this.value=='tipo_servicio')
        obj = <?php echo json_encode($tipo_servicios); ?>;

      else if (this.value=='cliente')
        obj = <?php echo json_encode($clientes); ?>;
       

      for (var el in obj)
      {
        var div = document.createElement('option');
        div.setAttribute('value', el);
        div.innerHTML = obj[el];
        document.getElementById("seleccion").appendChild(div);
      };

    }



  });

  $('#operador').on('change', function ()
  {
    if (this.value=='igual')
    {
      $('#minimo').attr('hidden', true);
      $('#maximo').attr('hidden', true);
      $('#igual').attr('hidden', false);
    }
    else if (this.value=='entre')
    {
      $('#igual').attr('hidden', true);
      $('#minimo').attr('hidden', false);
      $('#maximo').attr('hidden', false);
    }

  });
  
  $('#btn_agregar').on('click', function ()
  {
    console.log("RESET");
    document.getElementById('agregaCondicion').reset();
    $('#valor').change();
    $('#operador').change();
  });

  $('body').on('click', '.borrar_condicion', function (event) {
    event.target.parentNode.remove();
    checkEmpty();
  });
  
  function agregaCondicion()
  {
    valor_text = $("#valor option:selected").text();
    valor_val = $("#valor option:selected").val();
    operador = $("#operador").val();
    minimo = $("#minimo_val").val()
    maximo = $("#maximo_val").val()
    igual = $("#igual_val").val()
    seleccion = $("#seleccion option:selected").val()
    texto = $("#seleccion option:selected").text()

    console.log(valor_val + "::" + valor_text + "::" + seleccion + ":::" + texto);

    crearCondicion(valor_text, valor_val, operador, minimo, maximo, seleccion, texto)
  }

  function crearCondicion(valor_text, valor_val, operador, minimo, maximo, seleccion, texto)
  {
    if (operador==undefined)
      operador = 'igual';

    var padre = document.createElement('div');
    padre.classList.add("card");
    padre.classList.add("slate");
  
    var cont = document.createElement('div');
    cont.classList.add("d-flex");
    cont.setAttribute("style", "padding: .5rem 1rem;");

    var val1 = document.createElement('span');
    val1.classList.add("pr-3");
    val1.innerHTML = '<b>' + valor_text + '</b>';

    var val2 = document.createElement('span');

    var val1i = document.createElement('input');
    val1i.setAttribute("type", "hidden");
    val1i.setAttribute("name", "condiciones[]");
    val1i.setAttribute("value", valor_val);

    cont.appendChild(val1);
    cont.appendChild(val1i);

    var valC = document.createElement('input');
    valC.setAttribute("type", "hidden");
    valC.setAttribute("name", "operador[]");
    valC.setAttribute("value",  operador);

    cont.appendChild(valC);

    var val2i = document.createElement('input');
    val2i.setAttribute("type", "hidden");
    val2i.setAttribute("name", "minimo[]");
    val2i.setAttribute("value", 0);

    var val3i = document.createElement('input');
    val3i.setAttribute("type", "hidden");
    val3i.setAttribute("name", "maximo[]");
    val3i.setAttribute("value", 0);

    var val4i = document.createElement('input');
    val4i.setAttribute("type", "hidden");
    val4i.setAttribute("name", "igual[]");
    val4i.setAttribute("value", 0);

    var val5i = document.createElement('input');
    val5i.setAttribute("type", "hidden");
    val5i.setAttribute("name", "seleccion[]");
    val5i.setAttribute("value", 0);

    var val6i = document.createElement('input');
    val6i.setAttribute("type", "hidden");
    val6i.setAttribute("name", "texto[]");
    val6i.setAttribute("value", 0);

    if (valor_val == 'dia')
    {
      if (operador == 'entre')
      {
        val2.innerHTML = 'entre ' + minimo + ' y ' + maximo;  
        val2i.setAttribute("value", minimo);
        val3i.setAttribute("value", maximo);
      }
  
      else if ( operador == 'igual')
      {
        val2.innerHTML = 'igual a ' + igual;  
        val4i.setAttribute("value", igual);
      }
    }

    else //if (valor_val == 'remitente')
    {
      val2.innerHTML = texto;
      val5i.setAttribute("value", seleccion);
      val6i.setAttribute("value", texto);
    }

    cont.appendChild(val2);

    cont.appendChild(val2i);
    cont.appendChild(val3i);
    cont.appendChild(val4i);
    cont.appendChild(val5i);
    cont.appendChild(val6i);

    padre.appendChild(cont);

    var del = document.createElement('span');
    del.classList.add("borrar_condicion");
    del.classList.add("fa");
    del.classList.add("fa-trash");

    padre.appendChild(del);

    document.getElementById("listado").appendChild(padre);

    checkEmpty();

    $('#agregarCondicion').modal('toggle');

  }

  function checkEmpty() {
    if (document.getElementById("listado").childElementCount==0)
    {
      $("#vacio").removeAttr("hidden");
    }
    else
    {
      $("#vacio").attr("hidden", true);
    }
  }

</script>

@endpush
