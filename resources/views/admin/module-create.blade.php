@extends('layouts.main')

@section('content')

<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.modules.create_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">


      <form action="{{ route('modules.store') }}" method="post">
        @csrf

        <div class="form-group">
          <label for="description">@lang('main.common.description')</label>
          <input class="form-control" id="description" name="description" value="{{ old('description') }}" autofocus>
        </div>

        <div class="form-group">
          <label for="formControlRange" id="texto_priority">{{ __('main.common.priority') }}: {{ old('priority') ?? 0 }}</label>
          <input type="range" id="priority" class="form-control-range" name="priority" 
            min="0" max="100" step="5" value="{{ old('priority') ?? 0 }}">
        </div>

        <h5 class="pt-2">@lang('main.problems.title')</h5>
        <hr class="mb-3 mt-0">
        <div id="listado" class="bg-slate p-3 pb-2">
          <p id="vacio">@lang('main.modules.no_problems')</p>
        </div>

        <div class="form-group mt-4">
          <label for="role">@lang('main.problems.add_type')</label>
          <div class="row mr-0">
            <select id="role" name="role" class="form-control col ml-3 mr-1">
              @foreach ($problems as $mod)
                <option value="{{$mod->id}}">{{$mod->description}}</option>
              @endforeach
            </select>
            <span class="btn btn-sm btn-outline-slate col-auto ml-2 mb-1" id="add">@lang('main.common.add')</span>
          </div>
        </div>
    
        <button type="submit" class="col-auto btn btn-outline-slate mt-4">@lang('main.common.save')</button>
  
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

</script>

@endpush
