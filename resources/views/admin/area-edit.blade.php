@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.areas.edit_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">

      <form action="{{ route('areas.update', $area->id) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
  
        <div class="form-group">
          <label for="description">@lang('main.common.description')</label>
          <input class="form-control" id="description" name="description" value="{{ $area->description }}">
        </div>

        <div class="form-group">
          <label for="formControlRange" id="texto_priority">{{ __('main.common.priority') }}: {{ $area->points }}</label>
          <input type="range" id="priority" class="form-control-range" name="priority" 
            min="0" max="100" step="5" value="{{ $area->points }}">
        </div>
        
        <h5 class="pt-2">@lang('main.modules.title')</h5>
        <hr class="mb-3 mt-0">
        <div id="listado" class="bg-slate p-3 mb-0">

          @if (count($area->modules)>0)
  
            @foreach ($area->modules as $con)
              <div class="card slate">
                <span style="padding: .5rem 1rem;">{{$con->description}}</span>
                <i class="borrar_condicion ri-lg ri-delete-bin-7-line"></i>
                <input type="hidden" name="modules[]" value="{{$con->id}}">
              </div>
            @endforeach

          @endif
          <p id="vacio" hidden>@lang('main.areas.no_modules')s</p>

        </div>


        <div class="form-group mt-4">
          <label for="role">@lang('main.modules.add_module')</label>
          <div class="row mr-0">
            <select id="role" class="form-control col ml-3 mr-1">
              @foreach ($modules as $mod)
                <option value="{{$mod->id}}">{{$mod->description}}</option>
              @endforeach
            </select>
            <span class="btn btn-sm btn-outline-slate col-auto ml-2 mb-1" id="add">@lang('main.common.add')</span>
          </div>
        </div>

        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save_changes')</button>
  
      </form>

    </div>

    
</div>

@endsection


@push('scripts')

<script>

  var slider = document.getElementById("priority");
  var output = document.getElementById("texto_priority");

  slider.oninput = function() {
    output.innerHTML = '@lang('main.common.priority')' + ': ' + this.value;
  }

  $('#add').on('click', function ()
  {
    var exists = false;

    $("input[name='modules[]']").each(function() {
      if ($('#role').val() == $(this).val())
        exists = true;
    })

    if (exists) return;

    var padre = document.createElement('div');
    padre.classList.add("card");
    padre.classList.add("slate");

    var cont = document.createElement('div');
    cont.classList.add("d-flex");
    cont.setAttribute("style", "padding: 0 1rem;");

    var val1 = document.createElement('span');
    val1.setAttribute("style", "padding: .5rem 1rem;");
    val1.innerHTML = $('#role option:selected').text();

    var del = document.createElement('i');
    del.classList.add("borrar_condicion");
    del.classList.add("ri-lg");
    del.classList.add("ri-delete-bin-7-line");

    var input = document.createElement('input');
    input.setAttribute("type", "hidden");
    input.setAttribute("name", "modules[]");
    input.setAttribute("value", $('#role').val());

    padre.appendChild(val1);
    padre.appendChild(del);
    padre.appendChild(input);

    document.getElementById("listado").appendChild(padre);

    checkEmpty();

  
  });

  $('body').on('click', '.borrar_condicion', function (event) {
    event.target.parentNode.remove();
    checkEmpty();
  });

  function checkEmpty() {
    if (document.getElementById("listado").childElementCount==1)
    {
      $("#vacio").removeAttr("hidden");
    }
    else
    {
      $("#vacio").attr("hidden", true);
    }
  }

  $(document).ready(function(e)
  {
    checkEmpty();
  });

</script>

@endpush
