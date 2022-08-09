@extends('layouts.main')

@section('content')

<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.sla.title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <form action="{{ route('sla.update', 1) }}" method="post" enctype="multipart/form-data">
      @csrf
      @method('put')

      <div class="form-group">
        <label for="formControlRange" id="sla_text">
        </label>
        <input type="range" id="sla" class="form-control-range" name="sla" 
          min="0" max="72" step="1" value="{{ $data->sla_default ?? 0 }}">
      </div>

      <div class="form-group">
        <label for="formControlRange" id="notify_time">
        </label>
        <input type="range" id="sla_notify" class="form-control-range" name="sla_notify" 
          min="0" max="72" step="1" value="{{ $data->sla_notify ?? 0 }}">
      </div>

      <button type="submit" id="guardarCambios" class="col-auto btn btn-outline-slate mt-2 mb-3">@lang('main.common.save_changes')</button>


    </form>


    <div class="row mr-0">
      <h5 class="col-sm pt-2">@lang('main.sla.rules')</h5>
      <div class="col-sm botonera pr-0">
        <a href="{{ route('slarules.create') }}" class="col-auto btn btn-plain slate btn-sm ml-2 mt-3 mb-1">
          <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>@lang('main.sla.add_rule')</a>
      </div>
    </div>
    <hr class="mb-3 mt-0">
    
    @if ($rules->count()>0)
      <table class="table">
          <thead>
            <tr>
              <th style="width:5rem;">ID</th>
              <th class="th-auto">@lang('main.common.description')</th>
              <th class="d-none d-md-table-cell text-center" style="width:7rem;">@lang('main.common.sla')</th>
              <th class="d-none d-lg-table-cell text-center" style="width:8rem;">@lang('main.common.status')</th>
              <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($rules as $tipo)
            <tr class="@if($tipo->active!=1) text-danger @endif" id="{{$tipo->id}}">
              <td>{{$tipo->id}}</td>
              <td>{{$tipo->description}}</td>
              <td class="d-none d-md-table-cell text-center">{{$tipo->sla}}</td>
              <td class="d-none d-lg-table-cell text-center">{{$tipo->active==1? __('main.common.active'):__('main.common.inactive')}}</td>
              <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                  <a href="{{ route('slarules.edit', $tipo->id) }}" class="ri-lg ri-edit-line"></a>

                  <a href="#" class="ri-lg ri-delete-bin-7-line" 
                      onclick="window.confirm('@lang('main.sla.delete_question')')?
                      (document.getElementById('form-delete').setAttribute('action','{{ route('slarules.destroy', $tipo->id) }}') &
                      document.getElementById('form-delete').submit()):''"
                  ></a>
              </td>
            </tr>
            @endforeach
          </tbody>
      </table>
    @else
      <p>@lang('main.sla.no_rules')</p>
    @endif

    <form id="form-delete" method="post" action="">
      @csrf 
      @method('delete')
    </form>

</div>

@endsection


@push('scripts')

<script>

  var slider = document.getElementById("sla");
  var output = document.getElementById("sla_text");
  slider.oninput = function() {
   changeText();
  }

  var slider2 = document.getElementById("sla_notify");
  var output2 = document.getElementById("notify_time");
  slider2.oninput = function() {
   changeText2();
  }

  function changeText() {
    var def = '{{ __("main.sla.sla_default") }}';
    if ($('#sla').val()==0)
      output.innerHTML = def + ' ' + '{{ __("main.sla.not_setted") }}';
    else if ($('#sla').val()==1)
      output.innerHTML = def + ' 1 ' + '{{ __("main.sla.hour") }}';
    else
      output.innerHTML = def + ' ' +  $('#sla').val() + ' {{ __("main.sla.hours") }}';
  }

  function changeText2() {
    var def = '{{ __("main.sla.to_expire_time") }}';
    if ($('#sla_notify').val()==0)
      output2.innerHTML = def + ' ' + '{{ __("main.sla.not_setted") }}';
    else if ($('#sla_notify').val()==1)
      output2.innerHTML = def + ' 1 ' + '{{ __("main.sla.hour") }}';
    else
      output2.innerHTML = def + ' ' +  $('#sla_notify').val() + ' {{ __("main.sla.hours") }}';
  }
  

  $(document).ready(function () {
    changeText();
    changeText2();
  });

</script>

@endpush