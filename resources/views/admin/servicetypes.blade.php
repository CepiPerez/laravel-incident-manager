@extends('layouts.main')

@section('content')

<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.serv_types.title')</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('servicetypes.create') }}" class="col-auto btn btn-plain slate btn-sm ml-2 mt-3 mb-1">
            <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>@lang('main.serv_types.add_type')</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    <table class="table">
        <thead>
          <tr>
            <th style="width:5rem;">ID</th>
            <th class="th-auto">@lang('main.serv_types.service_type')</th>
            <th class="d-none d-md-table-cell text-center" style="width:7rem;">@lang('main.common.priority')</th>
            <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($service_types as $tipo)
          <tr>
            <td>{{$tipo->id}}</td>
            <td>{{$tipo->description}}</td>
            <td class="d-none d-md-table-cell text-center">{{$tipo->points}}</td>
            <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                <a href="{{ route('servicetypes.edit', $tipo->id) }}" class="ri-lg ri-edit-line"></a>

                <a href="#" class="ri-lg ri-delete-bin-7-line @if($tipo->counter>0) disabled @endif" 
                  @if($tipo->counter==0)
                    onclick="window.confirm('@lang('main.serv_types.delete_question')')?
                    (document.getElementById('form-delete').setAttribute('action','{{ route('servicetypes.destroy', $tipo->id) }}') &
                    document.getElementById('form-delete').submit()):''"
                  @endif
                ></a>
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>

    {{ $service_types->links() }}

    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

@endsection