@extends('layouts.main')

@section('content')

<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.sla.edit_rule_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">


      <form action="{{ route('slarules.update', $rule->id) }}" method="post">
        @csrf
        @method('put')

        <div class="form-group">
          <label for="description">@lang('main.common.description')</label>
          <input class="form-control" id="description" name="description" 
          value="{{ $rule->description }}" autofocus>
        </div>

        <label>@lang('main.sla.conditions')</label>
        <div class="bg-slate p-3">
          <div id="listado">
            @foreach ($rule->conditions as $con)
              <div class="card slate">
                <div class="d-flex" style="padding: .5rem 1rem;">
                  <span class="pr-3"><b>
                    @if ($con->condition=='service_types') @lang('main.serv_types.service_type')
                    @elseif ($con->condition=='clients') @lang('main.clients.client')
                    @elseif ($con->condition=='areas') @lang('main.areas.area')
                    @elseif ($con->condition=='modules') @lang('main.modules.module')
                    @elseif ($con->condition=='problems') @lang('main.problems.problem')
                    @endif
                  </b></span>
                  <span>
                    {{ $con->helper }} 
                  </span>
                  <input type="hidden" name="conditions[]" value="{{ $con->condition }}">
                  <input type="hidden" name="values[]" value="{{ $con->value }}">
                  <input type="hidden" name="text[]" value="{{ $con->helper }}">
                </div>
                <i class="borrar_condicion ri-lg ri-delete-bin-7-line"></i>
              </div>
            @endforeach
          </div>
          <p id="vacio" hidden>@lang('main.sla.no_conditions')</p>

          <div class="botonera p-0 m-0 mt-3">
            <span class="col-auto btn btn-plain btn-sm slate mb-1 p-0 mr-2" 
              data-toggle="modal" data-target="#agregarCondicion" id="btn_agregar">
              <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>@lang('main.sla.add_condition')
            </span>
          </div>

        </div>

        <div class="form-group mt-3">
          <label for="formControlRange" id="sla_text">
          </label>
          <input type="range" id="sla" class="form-control-range" name="sla" 
            min="0" max="72" step="1" value="{{ $rule->sla }}">
        </div>
        
        <div class="form-group">
          <label for="active">@lang('main.common.status')</label>
          <select id="active" name="active" class="form-control">
              <option value=1 @selected($rule->active==1)>@lang('main.common.active')</option>
              <option value=0 @selected($rule->active==0)>@lang('main.common.inactive')</option>
          </select>
        </div>

  
        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save')</button>
  
      </form>

    </div>


    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" hidden 
          data-delay="5000" style="position:absolute;top:1rem;right:1rem;opacity:1;">
      <div class="toast-header bg-danger" style="height:1rem;">
      </div>
      <div class="toast-body">
        <div class="row">
          <div class="col-auto pt-1 mr-3" id="toast-list">
            <li>@lang('main.sla.error_incomplete')</li>
          </div>
          <div class="col">
            <button type="button" class="ml-auto mb-1 close" data-dismiss="toast" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    
</div>

@endsection



@push('css')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
@endpush



@push('scripts')

<script src="{{ asset('assets/js/select2.min.js') }}"></script>

<script>

  var select = [];
  select['areas'] = '{{__('main.incidents.select_area')}}';
  select['modules'] = '{{__('main.incidents.select_module')}}';
  select['problems'] = '{{__('main.incidents.select_problem')}}';
  select['clients'] = '{{__('main.incidents.select_client')}}';
  select['service_types'] = '{{__('main.incidents.select_service_type')}}';
  var not_found = '{{__('main.incidents.no_results')}}';
  var searching = '{{__('main.incidents.searching')}}';

  $('#valor').select2({noResults: () => not_found, searching: () => searching});
  $('#selection').select2();

  var slider2 = document.getElementById("sla");
  var output2 = document.getElementById("sla_text");
  slider2.oninput = function() {
   changeText();
  }

  function changeText() {
    var def = '{{ __("main.sla.sla") }}';
    if ($('#sla').val()==0)
      output2.innerHTML = def + ' ' + '{{ __("main.sla.not_setted") }}';
    else if ($('#sla').val()==1)
      output2.innerHTML = def + ' 1 ' + '{{ __("main.sla.hour") }}';
    else
      output2.innerHTML = def + ' ' +  $('#sla').val() + ' {{ __("main.sla.hours") }}';
  }
  

  
  $('#valor').on('change', function ()
  {
    $("#seleccion").children().remove();
    /* $('#seleccion').select2({
      language: { noResults: () => not_found, searching: () => searching },
      placeholder: select[this.value],
      ajax: {
        url: '/utilities/get_data/'+this.value+'/a',
        type: "GET",
        dataType: 'json',
        delay: 250,
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    }); */
    $.ajax({
      url: '/utilities/get_data/'+this.value+'/a',
      dataType: 'json',
    }).done(function (data) {
      //console.log(JSON.stringify(data) );
      $("#seleccion").select2({
        language: { noResults: () => not_found, searching: () => searching },
        placeholder: select[this.value],
        data: data,
      });
    });
  });
  
  
  $('#btn_agregar').on('click', function ()
  {
    document.getElementById('agregaCondicion').reset();
    $('#valor').change();

  });

  $('body').on('click', '.borrar_condicion', function (event) {
    event.target.parentNode.remove();
    checkEmpty();
  });
  
  function agregaCondicion()
  {
    valor_val = $("#valor option:selected").val();
    valor_text = $("#valor option:selected").text();
    seleccion = $("#seleccion option:selected").val()
    texto = $("#seleccion option:selected").text()

    //console.log(valor_val + "::" + valor_text + "::" + seleccion + ":::" + texto);

    if (valor_val!=undefined && seleccion!=undefined)
      crearCondicion(valor_text, valor_val, seleccion, texto)

  }

  function crearCondicion(valor_text, valor_val, seleccion, texto)
  {

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
    val2.innerHTML = texto;

    cont.appendChild(val1);
    cont.appendChild(val2);

    var val1i = document.createElement('input');
    val1i.setAttribute("type", "hidden");
    val1i.setAttribute("name", "conditions[]");
    val1i.setAttribute("value", valor_val);

    var val2i = document.createElement('input');
    val2i.setAttribute("type", "hidden");
    val2i.setAttribute("name", "values[]");
    val2i.setAttribute("value", seleccion);

    var val3i = document.createElement('input');
    val3i.setAttribute("type", "hidden");
    val3i.setAttribute("name", "text[]");
    val3i.setAttribute("value", texto);

    cont.appendChild(val1i);
    cont.appendChild(val2i);
    cont.appendChild(val3i);

    padre.appendChild(cont);

    var del = document.createElement('i');
    del.classList.add("borrar_condicion");
    del.classList.add("ri-lg");
    del.classList.add("ri-delete-bin-7-line");

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

  $(document).ready(function () {
    checkEmpty();
    changeText();
    $('#valor').change();
  });

  $('form').on('submit', function() {

    if ($('#description').val()=='' || document.getElementById("listado").childElementCount==0)
    {
      $('.toast').prop('hidden', false);
      $('.toast').toast('show');        
      return false;
    }


  });


</script>

@endpush


@section('modal')

  <!-- Modal add condition -->
  <div class="modal fade" id="agregarCondicion" tabindex="-1" role="dialog" aria-labelledby="agregarCondicionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title text-white" id="agregarCondicion">@lang('main.sla.add_condition')</h5>
          </div>
          <div class="editor" style="margin:15px;">
              <form action="" id="agregaCondicion">
                
                <div class="form-group">
                  <label for="valor">@lang('main.sla.condition')</label>
                  <select id="valor" name="valor" class="form-control">
                    <option value='service_types'>@lang('main.serv_types.service_type')</option>
                    <option value='clients'>@lang('main.clients.client')</option>
                    <option value='areas'>@lang('main.areas.area')</option>
                    <option value='modules'>@lang('main.modules.module')</option>
                    <option value='problems'>@lang('main.problems.problem')</option>
                  </select>
                </div>
                
                <div class="form-group" id="comparador">

                  <label for="seleccion">@lang('main.sla.value')</label>
                  <select id="seleccion" name="seleccion" class="form-control">
                  </select>

                </div>

              </form>
              <button onclick="agregaCondicion()" class="btn btn-outline-slate mt-2">@lang('main.common.add')</button>
          </div>
      </div>
    </div>
  </div>

@endsection