@extends('layouts.main')

@section('content')
<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.reports.title')</h3>
        <div class="col-sm botonera pr-0">
          <a href="{{ route('reports.download') }}" class="col-auto btn btn-plain success btn-sm ml-2 mt-3 mb-1">
            <i class="ri-download-cloud-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>
            @lang('main.reports.download')</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    <div class="card p-3 mb-3 info">
      <div class="">
        @lang('main.reports.showing_from_to', ['from' => $filters['date_from'], 'to' => $filters['date_to']])
      </div>
      
      @if (isset($filters['client']) && Auth::user()->type==1)
      <div class="">@lang('main.reports.client'): <b>{{ $filters['client_desc'] }}</b></div>
      @endif

      @if (isset($filters['group_id']))
      <div class="">@lang('main.reports.assigned_group'): <b>{{ $filters['group_id'] }}</b></div>
      @endif
      
      @if (isset($filters['assigned']))
      <div class="">@lang('main.reports.assigned_to'): <b>{{ $filters['assigned'] }}</b></div>
      @endif

      @if (isset($filters['status']))
      <div class="">@lang('main.reports.incident_state'): <b>{{ $filters['status_desc'] }}</b></div>
      @endif

    </div>

    @if (count($incidents)>0)
    <table class="table ticketera">
        <thead>
          <tr>
            <th style="width:6rem;">INC</th>
            <th class="d-none d-xl-table-cell" style="width:120px;">Creado</th>
            @if (!isset($filters['client']))
            <th class="d-none d-lg-table-cell" style="width:220px;">Cliente</th>
            @endif
            <th class="th-auto">Titulo</th>
            <th style="width:110px;">Estado</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($incidents as $inc)
          <tr>
            <td>{{sprintf("%'.06d", $inc->id)}}</td>
            <td class="d-none d-xl-table-cell">{{date('d-m-Y ', strtotime($inc->created_at))}}</td>
            @if (!isset($filters['client']))
            <td class="d-none d-lg-table-cell">{{$inc->client_desc}}</td>
            @endif
            <td class="td-truncated">{{$inc->title}}</td>
            <td>{{ __('main.status.'.$inc->status_desc) }}</td>
          </tr>
          @endforeach
        </tbody>
    </table>
    @else
      <p>No se encontraron incidentes para los filtros indicados</p>

    @endif

    {{ $incidents->appends(request()->query())->links() }}
    

</div>



@endsection
