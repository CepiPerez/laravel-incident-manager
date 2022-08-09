@extends('layouts.main')

@section('content')

<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.groups.title')</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('groups.create') }}" class="col-auto btn btn-plain slate btn-sm ml-2 mt-3 mb-1">
            <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>@lang('main.groups.add_group')</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    <table class="table">
        <thead>
          <tr>
            <th style="width:5rem;">ID</th>
            <th class="th-auto">@lang('main.groups.group_name')</th>
            <th class="d-none d-md-table-cell text-center" style="width:8rem;">@lang('main.groups.members')</th>
            <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($groups as $tipo)
          <tr>
            <td>{{$tipo->id}}</td>
            <td class="td-truncated">{{$tipo->description}}</td>
            <td class="d-none d-md-table-cell text-center">{{$tipo->members}}</td>
            <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                <a href="{{ route('groups.edit', $tipo->id) }}" class="ri-lg ri-edit-line"></a>

                <a href="#" class="ri-lg ri-delete-bin-7-line @if($tipo->counter>0) disabled @endif" 
                  @if($tipo->counter==0)
                    onclick="window.confirm('@lang('main.groups.delete_question')')?
                    (document.getElementById('form-delete').setAttribute('action','{{ route('groups.destroy', $tipo->id) }}') &
                    document.getElementById('form-delete').submit()):''"
                  @endif
                ></a>
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>

    {{ $groups->links() }}

    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

<script>

  function habilitarTipoIncidente($url)
  {
    console.log("SEND: "+ $url);
    $.ajax({
      url: $url,
      type: 'get'
    })
    .done(
      function(response) { 
        //console.log("RESPONSE:" + response.activo)
        if (response.activo==1)
        {
          $('#'+response.id).removeClass('text-danger');
          $('#'+response.id).children().eq(4).children().eq(1).removeClass('fa-unlock');
          $('#'+response.id).children().eq(4).children().eq(1).addClass('fa-lock');
        }
        else
        {
          $('#'+response.id).addClass('text-danger');
          $('#'+response.id).children().eq(4).children().eq(1).removeClass('fa-lock');
          $('#'+response.id).children().eq(4).children().eq(1).addClass('fa-unlock');
        }

        $('#'+response.id).children().eq(3).text(response.activo==1?'Activo':'Inactivo');
        
      }
    );
  }  
</script>

@endsection