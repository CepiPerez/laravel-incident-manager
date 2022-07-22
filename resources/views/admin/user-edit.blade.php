@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.users.edit_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">

      <form action="{{ route('users.update', $user->id) }}" method="post" autocomplete="off">
        @csrf
        @method('put')

        <div class="row">
          
          <div class="form-group col-md-6 pr-3 pr-md-1">
            <label for="username">@lang('main.users.username')</label>
            <input class="form-control" id="username" name="username" value="{{ $user->username }}">
          </div>
  
          <div class="form-group col-md">
            <label for="password">@lang('main.users.password')</label>
            <input class="form-control" id="password" name="password" type="password" value="">
          </div>

        </div>
  

        <div class="row">

          <div class="form-group col-md-6 pr-3 pr-md-1">
            <label for="name">@lang('main.users.full_name')</label>
            <input class="form-control" id="name" name="name" value="{{ $user->name }}">
          </div>

          <div class="form-group col-md-6">
            <label for="email">@lang('main.users.email')</label>
            <input class="form-control" id="email" name="email" value="{{ $user->email }}" autocomplete="false">
          </div>

        </div>

        <div class="row">

          <div class="form-group col-md-6 pr-3 pr-md-1">
            <label for="type">@lang('main.users.user_type')</label>
            <select id="type" name="type" class="form-control">
              <option value=1 @selected($user->type==1)>@lang('main.users.internal')</option>
              <option value=0 @selected($user->type==0)>@lang('main.users.external')</option>
            </select>
          </div>

          <div class="form-group col-md-6" id="client_option" hidden>
            <label for="client_id">@lang('main.users.client')</label>
            <select id="client_id" name="client_id" class="form-control">
              @foreach ($clients as $cli)
              <option value="{{$cli->id}}" @selected($user->client_id==$cli->id)>{{$cli->description}}</option>
              @endforeach
            </select>
          </div>

          {{-- <div class="form-group col-md" id="group_option">
            <label for="group_id">@lang('main.users.group')</label>
            <select id="group_id" name="group_id" class="form-control">
              @foreach ($groups as $gr)
              <option value="{{$gr->id}}" @selected($user->group_id==$gr->id)>{{$gr->description}}</option>
              @endforeach
            </select>
          </div> --}}

        </div>
  

        </div>

        <div class="row">

          <div class="form-group col-md-6 pr-3 pr-md-1">
            <label for="role">@lang('main.users.role')</label>
            <select id="role" name="role" class="form-control">
              @foreach ($roles as $rol)
              <option value="{{$rol->id}}" @selected($user->role_id==$rol->id)>{{$rol->description}}</option>
              @endforeach
            </select>
          </div>
          
          <div class="form-group col-md">
            <label for="active">@lang('main.common.status')</label>
            <select id="active" name="active" class="form-control">
                <option value=1 @selected($user->active==1)>@lang('main.common.active')</option>
                <option value=0 @selected($user->active==0)>@lang('main.common.inactive')</option>
            </select>
          </div>

        </div>

        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save_changes')</button>
  
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
      //console.log("TIPO: "+this.value);

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
