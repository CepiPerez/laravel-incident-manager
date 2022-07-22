@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.serv_types.edit_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">

      <form action="{{ route('servicetypes.update', $service_type->id) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
  
        <div class="form-group">
          <label for="description">@lang('main.common.description')</label>
          <input class="form-control" id="description" name="description" value="{{ $service_type->description }}">
        </div>
        
        <div class="form-group">
          <label for="formControlRange" id="points_text">{{ __('main.common.priority') }}: {{ $service_type->points }}</label>
          <input type="range" id="points" class="form-control-range" name="points" 
            min="0" max="100" step="5" value="{{ $service_type->points }}">
        </div>

        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save_changes')</button>
  
      </form>

    </div>

    
</div>

<script>

  var slider = document.getElementById("points");
  var output = document.getElementById("points_text");
  slider.oninput = function() {
    output.innerHTML = 'Prioridad: ' + this.value;
  }
  
</script>

@endsection
