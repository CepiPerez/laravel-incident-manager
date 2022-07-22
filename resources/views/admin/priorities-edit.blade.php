@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.priorities.edit_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">

      <form action="{{ route('priorities.update', $priority->id) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
  
        <div class="form-group">
          <label for="description">@lang('main.common.description')</label>
          <input class="form-control" id="description" name="description" 
          value="{{ trans_fb('main.priorities.'.$priority->description) }}">
        </div>

        <div class="row">

          <div class="form-group col-sm">
            <label for="min">@lang('main.priorities.min')</label>
            <input type="number" class="form-control" id="min" name="min" value="{{ $priority->min }}">
          </div>
  
          <div class="form-group col-sm">
            <label for="max">@lang('main.priorities.max')</label>
            <input type="number" class="form-control" id="max" name="max" value="{{ $priority->max }}">
          </div>
  

        </div>
        
        <button type="submit" class="col-auto btn btn-outline-slate mt-2">Guardar cambios</button>
  
      </form>

    </div>

    
</div>

@endsection
