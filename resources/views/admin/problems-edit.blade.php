@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.problems.edit_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">

      <form action="{{ route('problems.update', $problem->id) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
  
        <div class="form-group">
          <label for="description">@lang('main.common.description')</label>
          <input class="form-control" id="description" name="description" value="{{ $problem->description }}">
        </div>
        
        <div class="form-group">
          <label for="formControlRange" id="points_text">{{ __('main.common.priority') }}: {{ $problem->points }}</label>
          <input type="range" id="points" class="form-control-range" name="points" 
            min="0" max="100" step="5" value="{{ $problem->points }}">
        </div>

      
        <div class="form-group pl-0">
          <label for="active">@lang('main.common.status')</label>
          <select id="active" name="active" class="form-control">
              <option value=1 @selected($problem->active==1)>@lang('main.common.active')</option>
              <option value=0 @selected($problem->active==0)>@lang('main.common.inactive')</option>
          </select>
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

  $(document).ready(function () {
    changeText();
  });

</script>

@endsection
