@extends('layouts.main')

@section('content')
<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.clients.title')</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('clients.create') }}" class="col-auto btn btn-plain slate btn-sm ml-2 mt-3 mb-1">
            <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>@lang('main.clients.add_client')</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    <table class="table">
        <thead>
          <tr>
            <th style="width:5rem;">ID</th>
            <th class="th-auto">@lang('main.clients.client')</th>
            <th class="d-none d-lg-table-cell th-auto">@lang('main.clients.service_type')</th>
            <th class="d-none d-md-table-cell" style="width:8rem;">@lang('main.common.status')</th>
            <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($clients as $cli)
          <tr class="@if($cli->active!=1) text-danger @endif" id="{{$cli->id}}">
            <td>{{$cli->id}}</td>
            <td>{{$cli->description}}</td>
            <td class="d-none d-lg-table-cell">{{$cli->service}}</td>
            <td class="d-none d-md-table-cell">{{$cli->active==1? __('main.common.active'):__('main.common.inactive')}}</td>
            <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                <a href="{{ route('clients.edit', $cli->id) }}" class="ri-lg ri-edit-line"></a>

                {{-- <i onclick="habilitarCliente('{{ route('clientes.habilitar', $cli->id) }}')" 
                class="fa @if($cli->activo==1) fa-lock @else fa-unlock @endif"></i> --}}

                <a href="#" class="ri-lg ri-delete-bin-7-line @if($cli->counter>0) disabled @endif" 
                  @if($cli->counter==0)
                    onclick="window.confirm('@lang('main.clients.delete_question')')?
                    (document.getElementById('form-delete').setAttribute('action','{{ route('clients.destroy', $cli->id) }}') &
                    document.getElementById('form-delete').submit()):''"
                  @endif
                ></a>
            </td>
            
          </tr>
          @endforeach
        </tbody>
    </table>

    {{ $clients->links() }}

    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

<script>

  function habilitarCliente($url)
  {
    //console.log("SEND: "+ $url);
    $.ajax({
      url: $url,
      type: 'get'
    })
    .done(
      function(response) { 
        //console.log("RESPONSE:" + response.activo)
        if (response.activo==1)
        {
          $('#'+response.codigo).removeClass('text-danger');
          $('#'+response.codigo).children().eq(3).children().eq(1).removeClass('fa-unlock');
          $('#'+response.codigo).children().eq(3).children().eq(1).addClass('fa-lock');
        }
        else
        {
          $('#'+response.codigo).addClass('text-danger');
          $('#'+response.codigo).children().eq(3).children().eq(1).removeClass('fa-lock');
          $('#'+response.codigo).children().eq(3).children().eq(1).addClass('fa-unlock');
        }

        $('#'+response.codigo).children().eq(3).text(response.activo==1?'Activo':'Inactivo');
        
      }
    );
  }  
</script>

@endsection