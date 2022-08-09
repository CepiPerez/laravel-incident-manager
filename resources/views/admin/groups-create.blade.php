@extends('layouts.main')

@section('content')

<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.groups.create')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">


      <form action="{{ route('groups.store') }}" method="post">
        @csrf

        <div class="form-group">
          <label for="description">@lang('main.groups.group_name')</label>
          <input class="form-control" id="description" name="description" value="{{ old('description') }}" autofocus>
        </div>

        <h5 class="pt-2">@lang('main.groups.members')</h5>
        <hr class="mb-3 mt-0">
        <div id="listado" class="bg-slate p-3 mb-0">

            <p id="vacio">@lang('main.groups.no_members')</p>
        </div>


        <div class="form-group mt-4">
          <label for="role">@lang('main.groups.add_member')</label>
          <div class="row mr-0">
            <div class="col">
              <select id="role" class="form-control col ml-3 mr-0">
               {{--  @foreach ($modules as $mod)
                  <option value="{{$mod->id}}">{{$mod->description}}</option>
                @endforeach --}}
              </select>
            </div>
            <button class="btn btn-sm btn-outline-slate col-auto ml-0" id="add">@lang('main.common.add')</button>
          </div>
        </div>
    
        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save')</button>
  
      </form>

    </div>

    
</div>

@endsection



@push('css')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
@endpush



@push('scripts')

<script src="{{ asset('assets/js/select2.min.js') }}"></script>

<script>

  var select_user = '{{__('main.incidents.select_user')}}';
  var not_found = '{{__('main.incidents.no_results')}}';
  var searching = '{{__('main.incidents.searching')}}';


  $(document).ready(function(e)
  {

    $('#role').select2({
      language: { noResults: () => not_found, searching: () => searching },
      placeholder: select_user,
      ajax: {
        url: '/utilities/get_data/users/a/',
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
    });


  });


  $('#add').on('click', function (e)
  {
    e.preventDefault();

    var exists = false;
    console.log('Selected: '+$('#role').val());
    if ($('#role').val()==null) return;

    $("input[name='users[]']").each(function() {
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
    input.setAttribute("name", "users[]");
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
