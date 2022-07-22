@extends('layouts.main')

@section('content')

<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.areas.title')</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('areas.create') }}" class="col-auto btn btn-outline-slate btn-sm ml-2 mt-2 mb-2 pl-3 pr-3">
            @lang('main.areas.add_area')</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    <table class="table">
        <thead>
          <tr>
            <th style="width:5rem;">ID</th>
            <th class="th-auto">@lang('main.areas.area')</th>
            <th class="d-none d-md-table-cell text-center" style="width:7rem;">@lang('main.common.priority')</th>
            <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($areas as $area)
          <tr>
            <td>{{$area->id}}</td>
            <td>{{$area->description}}</td>
            <td class="d-none d-md-table-cell text-center">{{$area->points}}</td>
            <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                <a href="{{ route('areas.edit', $area->id) }}" class="fa fa-edit"></a>

                <a href="#" class="fa fa-trash @if($area->counter>0) disabled @endif" 
                  @if($area->counter==0)
                    onclick="window.confirm('@lang('main.areas.delete_question')')?
                    (document.getElementById('form-delete').setAttribute('action','{{ route('areas.destroy', $area->id) }}') &
                    document.getElementById('form-delete').submit()):''"
                  @endif
                ></a>
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>

    {{ $areas->links() }}

    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

@endsection