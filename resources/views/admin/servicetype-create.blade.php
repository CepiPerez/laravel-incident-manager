@extends('layouts.main')

@section('content')

<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.serv_types.create_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">


      <form action="{{ route('servicetypes.store') }}" method="post">
        @csrf

        <div class="form-group">
          <label for="description">@lang('main.common.description')</label>
          <input class="form-control" id="description" name="description" value="{{ old('description') }}" autofocus>
        </div>

        <div class="form-group">
          <label for="formControlRange" id="points_text">{{ __('main.common.priority') }}: {{ old('points') ? old('points') : 0 }}</label>
          <input type="range" id="points" class="form-control-range" name="points" 
            min="0" max="100" step="5" value="{{ old('points') ? old('points') : 0 }}">
        </div>

        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save')</button>
  
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
