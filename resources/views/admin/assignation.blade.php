@extends('layouts.main')

@section('content')

<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.assignation.title')</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('assignation.create') }}" class="col-auto btn btn-outline-slate btn-sm ml-2 mt-2 mb-2 pl-3 pr-3">
            @lang('main.assignation.add_rule')</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    
    @if ($rules->count()>0)
      <table class="table">
          <thead>
            <tr>
              <th style="width:5rem;">ID</th>
              <th class="th-auto">@lang('main.common.description')</th>
              <th class="d-none d-md-table-cell" style="width:15rem;">@lang('main.assignation.group')</th>
              <th class="d-none d-md-table-cell" style="width:15rem;">@lang('main.assignation.user')</th>
              <th class="d-none d-lg-table-cell text-center" style="width:8rem;">@lang('main.common.status')</th>
              <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($rules as $tipo)
            <tr class="@if($tipo->active!=1) text-danger @endif" id="{{$tipo->id}}">
              <td>{{$tipo->id}}</td>
              <td>{{$tipo->description}}</td>
              <td class="d-none d-md-table-cell">{{$tipo->group_name}}</td>
              <td class="d-none d-md-table-cell">{{$tipo->user_name}}</td>
              <td class="d-none d-lg-table-cell text-center">{{$tipo->active==1? __('main.common.active'):__('main.common.inactive')}}</td>
              <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                  <a href="{{ route('assignation.edit', $tipo->id) }}" class="fa fa-edit"></a>

                  <a href="#" class="fa fa-trash" 
                      onclick="window.confirm('@lang('main.assignation.delete_question')')?
                      (document.getElementById('form-delete').setAttribute('action','{{ route('assignation.destroy', $tipo->id) }}') &
                      document.getElementById('form-delete').submit()):''"
                  ></a>
              </td>
            </tr>
            @endforeach
          </tbody>
      </table>
    @else
      <p>@lang('main.assignation.no_rules')</p>
    @endif

    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

@endsection