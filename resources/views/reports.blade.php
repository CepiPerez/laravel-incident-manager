@extends('layouts.main')

@section('content')
<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container">

    <div class="row mr-0">
        <h3 class="col pt-2">@lang('main.reports.title')</h3>
    </div>
    <hr class="mb-3 mt-0">


    <form action="{{ route('reports.process') }}" method="GET" class="editor">
        
        <div class="row">

          @if (Auth::user()->type==1)

            <div class="form-group col-12 col-md-6">
              <label for="group_id">@lang('main.reports.assigned_group')</label>
              <select id="group_id" name="group_id" class="form-control">
                <option value="all">@lang('main.reports.all')</option>
                @foreach ($groups as $key => $val)
                  <option value="{{$key}}">{{$val}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-12 col-md-6">
              <label for="assigned">@lang('main.reports.assigned_to')</label>
              <select id="assigned" name="assigned" class="form-control">
                <option value="all">@lang('main.reports.all')</option>
                @foreach ($users as $key => $val)
                  <option value="{{$key}}">{{$val}}</option>
                @endforeach
              </select>
            </div>
    
            <div class="form-group col-12 col-md-6">
              <label for="client">@lang('main.reports.client')</label>
              <select id="client" name="client" class="form-control">
                <option value="all">@lang('main.reports.all')</option>
                @foreach ($clients as $key => $val)
                  <option value="{{$key}}">{{$val}}</option>
                @endforeach
              </select>
            </div>

          @endif
    
          <div class="form-group col-12 col-md-6">
            <label for="status">@lang('main.reports.incident_state')</label>
            <select id="status" name="status" class="form-control">
              <option value="all">@lang('main.reports.exclude_cancel')</option>
              <option value="allinc">@lang('main.reports.include_cancel')</option>
              @foreach ($states as $key => $val)
                <option value="{{$key}}">{{ __('main.status.'.$val) }}</option>
              @endforeach
            </select>
          </div>

        </div>

        <div class="row mt-1 mb-1">

            <div class="form-group col-12 col-md-6">
                <label for="cliente">@lang('main.reports.from')</label>
                <div class="form-group date con-calendario" id="datePicker-desde" >
                    <input class="form-control text-center" type="text" id="date_from" 
                      name="date_from" autocomplete="false" value="{{date('d-m-Y')}}">
                </div>
            </div>

            <div class="form-group col-12 col-md-6">
                <label for="cliente">@lang('main.reports.to')</label>
                <div class="form-group date con-calendario" id="datePicker-hasta" >
                    <input class="form-control text-center" type="text" id="date_to" 
                      name="date_to" autocomplete="false" value="{{date('d-m-Y')}}">
                </div>
            </div>

        </div>

        <button type="submit" id="guardarCambios" class="col-auto btn btn-outline-slate mt-2">@lang('main.reports.process')</button>


    </form>

</div>

@endsection


@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/newdatetimepicker.css') }}">
@endpush


@push('scripts')

<script src="{{ asset('assets/js/newdatetimepicker.js') }}"></script>

<script>

$(document).ready(function() {

  $('#date_from').datetimepicker({
    format:'d-m-Y',
    formatDate:'d-m-Y',
    timepicker:false
  });

  $('#date_to').datetimepicker({
    format:'d-m-Y',
    formatDate:'d-m-Y',
    timepicker:false,
  });

  
});

</script>

@endpush