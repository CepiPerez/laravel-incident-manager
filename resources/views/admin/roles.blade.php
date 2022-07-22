@extends('layouts.main')

@section('content')

<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.roles.title')</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('roles.create') }}" class="col-auto btn btn-outline-slate btn-sm ml-2 mt-2 mb-2 pl-3 pr-3">
            @lang('main.roles.add_role')</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    <table class="table">
        <thead>
          <tr>
            <th style="width:5rem;">ID</th>
            <th class="th-auto">@lang('main.roles.role')</th>
            <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($roles as $rol)
          <tr>
            <td>{{$rol->id}}</td>
            <td>{{$rol->description}}</td>
            <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
              @if($rol->id!=1)
                <a href="{{ route('roles.edit', $rol->id) }}" class="fa fa-edit"></a>

                <a href="#" class="fa fa-trash @if($rol->counter>0) disabled @endif" 
                  @if($rol->counter==0)
                    onclick="window.confirm('@lang('main.roles.delete_question')')?
                    (document.getElementById('form-delete').setAttribute('action','{{ route('roles.destroy', $rol->id) }}') &
                    document.getElementById('form-delete').submit()):''"
                  @endif
                ></a>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>

    {{ $roles->links() }}

    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

@endsection