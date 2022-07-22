@extends('layouts.main')

@section('content')

<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.clients.create')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">


      <form action="{{ route('clients.store') }}" method="post">
        @csrf

        <div class="form-group">
          <label for="description">@lang('main.clients.client_name')</label>
          <input class="form-control" id="description" name="description" value="{{ old('description') }}" autofocus>
        </div>
        
        <div class="form-group col-6 pl-0">
          <label for="service_type">@lang('main.clients.service_type')</label>
          <select id="service_type_id" name="service_type_id" class="form-control">
            @foreach ($service_types as $ti)
              <option value="{{$ti->id}}">{{$ti->description}}</option>
            @endforeach
          </select>
        </div>

        <h5>@lang('main.clients.areas')</h5>
        <hr class="mt-0">
        @foreach ($areas as $area)
        <div class="form-check pb-2 ml-0">
          <input type="checkbox" class="form-check-input" name="areas[]" id="{{$area->id}}"
            value="{{$area->id}}">
          <label class="ml-2 form-check-label" style="padding-top:1px;" 
            onclick="document.getElementById('{{$area->id}}').click()">{{ $area->description }}</label>
        </div>
        @endforeach
    
        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save')</button>
  
      </form>

    </div>

    
</div>


@endsection
