@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.users.user_profile')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="">

      <h4 class="">{{ $user->username }}</h4>
      <hr class="mt-0">

      <form action="{{ route('user.profile.update', $user->id) }}" 
        method="post" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="row">

          <div class="col-sm">
            
            <div class="form-group">
              <label for="name">@lang('main.users.full_name')</label>
              <input class="form-control" id="name" name="name" value="{{ old('name') ?? $user->name }}">
            </div>
    
            <div class="form-group">
              <label for="password">@lang('main.users.password') <span class="text-secondary">@lang('main.users.leave_blank')</span></label>
              <input class="form-control" id="password" name="password" type="password" value="{{old('password') ?? '' }}" autocomplete="false">
            </div>
    
            <div class="form-group">
              <label for="email">@lang('main.users.email')</label>
              <input class="form-control" id="email" name="email" value="{{ old('email') ??  $user->email }}" autocomplete="false">
            </div>

          </div>

          <div class="col-sm text-center">

            <div class="form-group">
              <label for="avatar">@lang('main.users.avatar')</label><br>
              <img src="{{ $user->avatar }}" alt="" height="180" width="180" class="profilepic edit" id="profilepic">
              {{-- <i class="fa fa-edit profilepic-edit"></i> --}}
              <input hidden type="file" class="custom-file-input" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.webp">
            </div>


          </div>

        </div>

  
  
        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save_changes')</button>
  
      </form>

    </div>

    
</div>

@endsection



@push('scripts')

<script>

    $('#profilepic').on('click',function() {
      $('#avatar').click();  
    })

    $('#avatar').on('change',function() {
      imgPreview(this);
    })

    function imgPreview(input){
     if(input.files && input.files[0]){
       var reader = new FileReader();
       reader.onload = function(e){
         $("#profilepic").show().attr("src", e.target.result);
       }
       reader.readAsDataURL(input.files[0]);
     }
    }


</script>

@endpush