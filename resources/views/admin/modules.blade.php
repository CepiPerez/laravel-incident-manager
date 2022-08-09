@extends('layouts.main')

@section('content')

<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.modules.title')</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('modules.create') }}" class="col-auto btn btn-plain slate btn-sm ml-2 mt-3 mb-1">
            <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>@lang('main.modules.add_module')</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    <table class="table">
        <thead>
          <tr>
            <th style="width:5rem;">ID</th>
            <th class="th-auto">@lang('main.modules.module')</th>
            <th class="d-none d-md-table-cell text-center" style="width:7rem;">@lang('main.common.priority')</th>
            <th class="d-none d-lg-table-cell text-center" style="width:8rem;">@lang('main.common.status')</th>
            <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($modules as $mod)
          <tr class="@if($mod->active!=1) text-danger @endif" id="{{$mod->id}}">
            <td>{{$mod->id}}</td>
            <td class="td-truncated">{{$mod->description}}</td>
            <td class="d-none d-md-table-cell text-center">{{$mod->points}}</td>
            <td class="d-none d-lg-table-cell text-center">{{$mod->active==1? __('main.common.active'):__('main.common.inactive')}}</td>
            <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                <a href="{{ route('modules.edit', $mod->id) }}" class="ri-lg ri-edit-line"></a>


                <a href="#" class="ri-lg ri-delete-bin-7-line @if($mod->counter>0) disabled @endif" 
                  @if($mod->counter==0)
                    onclick="window.confirm('@lang('main.modules.delete_question')')?
                    (document.getElementById('form-delete').setAttribute('action','{{ route('modules.destroy', $mod->id) }}') &
                    document.getElementById('form-delete').submit()):''"
                  @endif
                ></a>
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>

    {{ $modules->links() }}

    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

@endsection