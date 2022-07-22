@extends('layouts.main')

@section('content')

<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">Reglas de prioridad</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('reglas.crear') }}" class="col-auto btn btn-outline-slate btn-sm ml-2 mt-2 mb-2 pl-3 pr-3">
              Agregar regla</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    @if ($reglas->count()>0)
    <table class="table">
        <thead>
          <tr>
            <th style="width:5rem;">ID</th>
            <th class="th-auto">Descripcion</th>
            <th class="d-none d-md-table-cell text-center" style="width:7rem;">Prioridad</th>
            <th class="d-none d-lg-table-cell text-center" style="width:8rem;">Estado</th>
            <th style="text-align:right;width:7rem;">ACCIONES</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($reglas as $tipo)
          <tr class="@if($tipo->activo!=1) text-danger @endif" id="{{$tipo->id}}">
            <td>{{$tipo->id}}</td>
            <td>{{$tipo->descripcion}}</td>
            <td class="d-none d-md-table-cell text-center">{{$tipo->pondera}}</td>
            <td class="d-none d-lg-table-cell text-center">{{$tipo->activo==1? 'Activo':'Inactivo'}}</td>
            <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                <a href="{{ route('reglas.editar', $tipo->id) }}" class="fa fa-edit"></a>

                <i onclick="habilitarRegla('{{ route('reglas.habilitar', $tipo->id) }}')" 
                  class="fa @if($tipo->activo==1) fa-lock @else fa-unlock @endif"></i>

                <a href="#" class="fa fa-trash @if($user->contador>0) disabled @endif" 
                  @if($user->contador==0)
                    onclick="window.confirm('Esta seguro que desea eliminar la regla?')?
                    (document.getElementById('form-delete').setAttribute('action','{{ route('reglas.eliminar', $tipo->id) }}') &
                    document.getElementById('form-delete').submit()):''"
                  @endif
                ></a>
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>
    @else
      <p>No se encontraron reglas</p>
    @endif


    {{ $reglas->appends(request()->query())->links(true) }}


    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

<script>

  var slider = document.getElementById("prioridad");
  var output = document.getElementById("texto_prioridad");
  //output.innerHTML = 'Prioridad: ' slider.value;

  // Update the current slider value (each time you drag the slider handle)
  slider.oninput = function() {
    output.innerHTML = 'Prioridad: ' + this.value;
  }


  function habilitarRegla($url)
  {
    //console.log("SEND: "+ $url);
    $.ajax({
      url: $url,
      type: 'get'
    })
    .done(
      function(response) { 
        if (response.activo==1)
        {
          $('#'+response.id).removeClass('text-danger');
          $('#'+response.id).children().eq(3).children().eq(1).removeClass('fa-unlock');
          $('#'+response.id).children().eq(3).children().eq(1).addClass('fa-lock');
        }
        else
        {
          $('#'+response.id).addClass('text-danger');
          $('#'+response.id).children().eq(3).children().eq(1).removeClass('fa-lock');
          $('#'+response.id).children().eq(3).children().eq(1).addClass('fa-unlock');
        }

        $('#'+response.id).children().eq(3).text(response.activo==1?'Activo':'Inactivo');
      }
    );
  }  

</script>

@endsection