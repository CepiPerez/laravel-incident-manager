@extends('layouts.main')

@section('content')
<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.users.title')</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('users.create') }}" class="col-auto btn btn-plain slate btn-sm ml-2 mt-3 mb-1">
            <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>@lang('main.users.add_user')</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    <table class="table">
        <thead>
          <tr>
            <th style="width:14rem;">@lang('main.users.username')</th>
            <th class="th-auto">@lang('main.users.name')</th>
            <th class="d-none d-lg-table-cell" style="width:10rem;">@lang('main.users.type')</th>
            <th class="d-none d-md-table-cell" style="width:10rem;">@lang('main.users.role')</th>
            {{-- <th class="d-none d-lg-table-cell" style="width:10rem;">@lang('main.users.client')</th> --}}
            <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
          <tr class="@if($user->active!=1) text-danger @endif" id="{{$user->id}}">
            <td>
              <img src="{{ $user->avatar }}" alt=""> {{$user->username}}
            </td>
            <td>{{$user->name}}</td>
            <td class="d-none d-lg-table-cell">{{$user->type==1? __('main.users.internal'):__('main.users.external')}}</td>
            <td class="d-none d-md-table-cell">{{$user->role->description}}</td>
            {{-- <td class="d-none d-lg-table-cell">{{$user->client->description}}</td> --}}
            <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                <a href="{{ route('users.edit', $user->id) }}" class="ri-lg ri-edit-line"></a>

                <a href="#" class="ri-lg ri-delete-bin-7-line @if($user->counter>0) disabled @endif" 
                  @if($user->counter==0)
                    onclick="window.confirm('@lang('main.users.delete_question')')?
                    (document.getElementById('form-delete').setAttribute('action','{{ route('users.destroy', $user->id) }}') &
                    document.getElementById('form-delete').submit()):''"
                  @endif
                ></a>
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>

    {{-- {{ $users->appends(request()->query())->links(true) }} --}}

    {{ $users->links() }}

    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

@endsection