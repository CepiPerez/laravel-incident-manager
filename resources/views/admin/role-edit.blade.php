@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.roles.edit_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">

      <form action="{{ route('roles.update', $role->id) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
  
        <div class="form-group">
          <label for="description">@lang('main.common.description')</label>
          <input class="form-control" id="description" name="description" value="{{ $role->description }}">
        </div>

        <div class="form-group">
          <label for="type">@lang('main.users.user_type')</label>
          <select id="type" name="type" class="form-control">
            <option value=1 @selected($role->type==1)>@lang('main.users.internal')</option>
            <option value=0 @selected($role->type==0)>@lang('main.users.external')</option>
          </select>
        </div>

        <h5 class="mt-4">@lang('main.roles.permissions')</h5>
        <hr class="mt-0 mb-2">

        @foreach ($permissions->where('id', 1) as $perm)
        <div class="form-check pb-2 ml-0">
          <input type="checkbox" class="form-check-input" name="permissions[]" id="{{$perm->id}}"
            value="{{$perm->id}}" @checked( in_array($perm->id, $permissions_role) )>
          <label class="ml-2 form-check-label" style="padding-top:1px;" 
            onclick="elementClick('{{$perm->id}}')">{{ __('main.permissions.'.$perm->description) }}</label>
        </div>
        @endforeach

        @foreach ($permissions->whereBetween('id', [2, 4]) as $perm)
        <div class="form-check" style="height:2rem;">
          <input class="form-check-input" type="radio" name="permissions[]" id="{{$perm->id}}"
            value="{{$perm->id}}" @checked( in_array($perm->id, $permissions_role) )>
            <label class="ml-2 form-check-input" style="top:-3px;" 
              onclick="elementClick('{{$perm->id}}')">{{ __('main.permissions.'.$perm->description) }}</label>
          </label>
        </div>
        @endforeach

        @foreach ($permissions->where('id', '>', 4)->where('id', '<', 8) as $perm)
          <div class="form-check pb-2 ml-0">
            <input type="checkbox" class="form-check-input" name="permissions[]" id="{{$perm->id}}"
              value="{{$perm->id}}" @checked( in_array($perm->id, $permissions_role) )>
            <label class="ml-2 form-check-label" style="padding-top:1px;" 
              onclick="elementClick('{{$perm->id}}')">{{ __('main.permissions.'.$perm->description) }}</label>
          </div>
        @endforeach

        <div id="admin">
          
          <h5 class="mt-2">@lang('main.roles.admin_permissions')</h5>
          <hr class="mt-0 mb-2">

          @foreach ($permissions->where('id', '>', 7) as $perm)
            <div class="form-check pb-2 ml-0">
              <input type="checkbox" class="form-check-input" name="permissions_adm[]" id="{{$perm->id}}"
                value="{{$perm->id}}" @checked( in_array($perm->id, $permissions_role) )>
              <label class="ml-2 form-check-label" style="padding-top:1px;" 
                onclick="elementClick('{{$perm->id}}')">{{ __('main.permissions.'.$perm->description) }}</label>
            </div>
          @endforeach

        </div>
  
        <button type="submit" class="col-auto btn btn-outline-slate mt-3">@lang('main.common.save_changes')</button>
  
      </form>

    </div>

    
</div>

@endsection



@push('scripts')

<script>

  function elementClick(element) {
   
    el = document.getElementById(element);

    if (!el.disabled) el.click();
  }

  $(document).ready(function()
  {

    $(":checkbox").on('change', function ()
    {

      if (this.value==8) {
        if (this.checked) {
          $('#9').prop('checked', false);
      }}
      if (this.value==9) {
        if (this.checked) {
          $('#8').prop('checked', false);
      }}

    });


    $('#type').on('change', function ()
    {
      
      if (this.value==0)
      {
        $("#admin").prop('hidden', true);

        $("#2").prop('disabled', true);
        if ( $('#2:checked').val() )
          $('#3').prop('checked', true);

        /* $("#4").prop('disabled', false); */

        $("#5").prop('disabled', true);
          $('#5').prop('checked', false);
      }
      else
      {
        $("#admin").prop('hidden', false);

        $("#2").prop('disabled', false);

        /* $("#4").prop('disabled', false);
        if ( $('#4:checked').val() )
          $('#3').prop('checked', true); */

        $("#5").prop('disabled', false);

      }

    });

    $('#type').change();

  });


</script>

@endpush


