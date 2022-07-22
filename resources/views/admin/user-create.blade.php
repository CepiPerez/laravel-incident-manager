@extends('layouts.main')

@section('content')

<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.users.create_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">


      <form action="{{ route('users.store') }}" method="post">
        @csrf

        <div class="row">
          
          <div class="form-group col-md-6 pr-3 pr-md-1">
            <label for="username">@lang('main.users.username')</label>
            <input class="form-control" id="username" name="username" value="{{ old('username') }}">
          </div>
  
          <div class="form-group col-md">
            <label for="password">@lang('main.users.password')</label>
            <input class="form-control" id="password" name="password" type="password" value="">
          </div>

        </div>

        <div class="row">
          
          <div class="form-group col-md-6 pr-3 pr-md-1">
            <label for="name">@lang('main.users.full_name')</label>
            <input class="form-control" id="name" name="name" value="{{ old('name') }}" autocomplete="false">
          </div>
  
          <div class="form-group col-md">
            <label for="email">@lang('main.users.email')</label>
            <input class="form-control" id="email" name="email" value="{{ old('email') }}" autocomplete="false">
          </div>

        </div>

        <div class="row">

          <div class="form-group col-md-6 pr-3 pr-md-1">
            <label for="type">@lang('main.users.user_type')</label>
            <select id="type" name="type" class="form-control">
              <option value=1 @selected(old('type')==1)>@lang('main.users.internal')</option>
              <option value=0 @selected(old('type')==0)>@lang('main.users.external')</option>
            </select>
          </div>

          <div class="form-group col-md" id="client_option" hidden>
            <label for="client_id">@lang('main.users.client')</label>
            <select id="client_id" name="client_id" class="form-control">
              @foreach ($clients as $cli)
              <option value="{{$cli->id}}" @selected($cli->id==old('client_id'))>{{$cli->description}}</option>
              @endforeach
            </select>
          </div>

          {{-- <div class="form-group col-md" id="group_option">
            <label for="group_id">@lang('main.users.group')</label>
            <select id="group_id" name="group_id" class="form-control">
              @foreach ($groups as $gr)
              <option value="{{$gr->id}}" @selected($gr->id==old('group_id'))>{{$gr->description}}</option>
              @endforeach
            </select>
          </div> --}}

        </div>

        <div class="row">

          <div class="form-group col-md-6 pr-3 pr-md-1">
            <label for="role">@lang('main.users.role')</label>
            <select id="role" name="role" class="form-control">
              @foreach ($roles as $rol)
              <option value="{{$rol->id}}" @selected(old('role')==$rol->id)>{{$rol->description}}</option>
              @endforeach
            </select>
          </div>

        </div>

  
        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save')</button>
  
      </form>

    </div>

    
</div>

@endsection


@push('scripts')

<script>

  $(document).ready(function(e)
  {

    $('#type').on('change', function ()
    {
      admin = $("#role").children().eq(0);
      if (this.value==0)
      {
        admin.attr('disabled', true);

        //$('#group_option').prop('hidden', true);
        $('#client_option').prop('hidden', false);

        if (admin.is(':selected'))
        {
            next = $("#role").children().eq(1); 
            $("#role").val(next.val());
        }
      }
      else
      {
        admin.attr('disabled', false);

        $('#client_option').prop('hidden', true);
        //$('#group_option').prop('hidden', false);
      }

    });

    $('#type').change();

  });


</script>

@endpush
