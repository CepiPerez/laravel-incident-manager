@extends('layouts.main')

@section('content')
<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container pb-3">

    <div class="row mr-0">
        <h3 class="col pt-2">@lang('main.incidents.incidents')</h3>

        <div class="col-auto botonera pr-0 pl-3">

            @if ($unassigned>0 && (!isset($filters['status_id']) || $filters['status_id']!=0) && Auth::user()->type==1)
                <a href="#" onclick="getUnassigned()" class="col-auto btn btn-plain danger btn-sm ml-2 mt-3 mb-0 mr-3">
                    <i class="ri-error-warning-line mr-1 m-0 p-0" style="vertical-align:middle;"></i>
                    @lang('main.incidents.unassigned_exists')
                </a>
            @endif
                
            @can('crear_inc')
                <a href="{{ route('incidents.create') }}" class="col-auto btn btn-plain slate btn-sm ml-2 mt-3 mb-0">
                    <i class="ri-add-line mr-1 m-0 p-0" style="vertical-align:middle;"></i>
                    @lang('main.incidents.new')
                </a>
            @endcan

        </div>
    </div>
    <hr class="mb-3 mt-0">


    <div class="row m-0 p-0">

        <form action="{{ route('incidents.index') }}" method="GET" id="form_buscar" class="col p-0 pr-3">
            <mi-buscador value="{{ $filters['search'] }}" form="form_buscar" placeholder="@lang('main.incidents.search')">
            </mi-buscador>
        </form>
    
        <button class="col-auto btn btn-filter-slate btn-sm pl-3 pr-3" data-toggle="modal" data-target="#filtrarModal">
            @if ( isset($filters) && !empty(array_intersect(array_keys(
                array_filter($filters, function($k) { return $k !== null;}))
                , ['assigned', 'client_id', 'status_id', 'module_id', 'area_id', 'pid', 'group_id', 'problem_id'])) )
                @lang('main.incidents.filters_applied')
            @else
                @lang('main.incidents.filter')
            @endif
        </button>

    </div>


    @if (count($incidents)>0)

        <table class="table ticketera">
            <thead>
            <tr>
                <th class="ordenar" id="{{ $filters['order']=='priority__asc'? 'priority__desc' : 'priority__asc' }}" style="width:2.5rem;">
                    P<span class="fa fa-sort pl-1 text-dimm"></span>
                </th>
                <th class="ordenar" id="{{ $filters['order']=='id__asc'? 'id__desc' : 'id__asc' }}" style="width:5rem;padding-left:0;">
                    @lang('main.incidents.table.inc')<span class="fa fa-sort pl-1 text-dimm"></span>
                </th>
                <th class="ordenar th-auto" id="{{ $filters['order']=='client_id__asc'? 'client_id__desc' : 'client_id__asc' }}">
                    @lang('main.common.description')<span class="fa fa-sort pl-1 text-dimm"></span>
                </th>
                <th class="ordenar d-none d-xl-table-cell" id="{{ $filters['order']=='created_at__asc'? 'created_at__desc' : 'created_at__asc' }}" style="width:140px;">
                    @lang('main.incidents.table.created')<span class="fa fa-sort pl-1 text-dimm"></span>
                </th>
                <th class="ordenar d-none d-lg-table-cell" id="{{ $filters['order']=='assigned__asc'? 'assigned__desc' : 'assigned__asc' }}" style="width:160px;">
                    @lang('main.incidents.table.assigned')<span class="fa fa-sort pl-1 text-dimm"></span>
                </th>
                <th class="ordenar d-none d-sm-table-cell text-center" id="{{ $filters['order']=='status_id__asc'? 'status_id__desc' : 'status_id__asc' }}" style="width:120px;">
                    @lang('main.incidents.table.status')<span class="fa fa-sort pl-1 text-dimm"></span>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($incidents as $value)
                <tr>
                    <td class="pl-1">
                        <a href="{{ route('incidents.edit', $value->id) }}">
                            <img src="{{asset('assets/icons/'.$value->pid.'.svg')}}" alt="" class="priority">
                        </a>
                    </td>
                    <td class="pl-0 text-secondary">
                        <a href="{{ route('incidents.edit', $value->id) }}">
                        {{ sprintf("%'.06d", $value->id) }}
                        </a>                        
                    </td>
                    <td class="td-truncated">
                        <a href="{{ route('incidents.edit', $value->id) }}">
                        @if (Auth::user()->type==1)
                        <span class="mr-2" style="font-weight:600;">{{ $value->client_desc }}</span>
                        <span class="text-secondary">{{ $value->title }}</span>
                        @else
                        <span class="mr-2" style="font-weight:500;">{{ $value->title }}</span>
                        @endif
                        </a>
                    </td>
                    <td class="d-none d-xl-table-cell">
                        <a href="{{ route('incidents.edit', $value->id) }}">
                            <span style="font-weight:500;">{{ date('d-m-Y ', strtotime($value->created_at)) }}</span>
                            <span class="text-secondary" style="font-size:.75rem;">{{ date(' H:i', strtotime($value->created_at)) }}</span>
                        </a>
                    </td>
                    <td class="d-none d-lg-table-cell td-truncated">
                        <a href="{{ route('incidents.edit', $value->id) }}">
                        @if ($value->status_id!=0 && $value->assigned)
                            <img src="{{ get_user_avatar($value->assigned) }}" alt="">
                            <span style="position:relative;top:1px;">{{ $value->assigned_name }}</span>
                        @else
                            <img src="{{ asset('profile/unassigned.png') }}" alt="">
                            <span class="text-secondary">@lang('main.incidents.table.unassigned')</span>
                        @endif
                        </a>
                    </td>
                    <td class="text-center d-none d-sm-table-cell">
                        <a href="{{ route('incidents.edit', $value->id) }}">
                        <i class="badge
                        @if ($value->status_id==0) badge-orange
                        @elseif ($value->status_id==5) badge-teal
                        @elseif ($value->status_id==10) badge-green
                        @elseif ($value->status_id==20) badge-gray
                        @elseif ($value->status_id==50) badge-lightgray
                        @else badge-blue
                        @endif
                        ">{{ __('main.status.'.$value->status_desc) }}</i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-none d-md-flex mb-0 referencias">
            <p><i>@lang('main.incidents.priorities')</i></p>
            @foreach ($priorities as $key => $val)
                <p class="ml-3"><img src="{{asset('assets/icons/'.$key.'.svg')}}" alt="" class="priority mr-2">
                    {{ trans_fb('main.priorities.'.$val) }}</p>
            @endforeach
        </div>
    @else
        @lang('main.incidents.no_incidents_found') 
    @endif


    {{ $incidents->links() }}


    <form action="" method="get" id="orderIncidents">
        <input type="hidden" name="order" id ="order" value="">
    </form>

</div>

{{-- @livewire('incidents-table') --}}


@endsection


@section('modal')
    <!-- Incident filters Modal -->
    <div class="modal fade" id="filtrarModal" tabindex="-1" role="dialog" 
        aria-labelledby="filtrarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="min-width:60vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="filtrarModalLabel">@lang('main.incidents.filters.apply_filters')</h5>
            </div>
            <div class="editor" style="margin:15px;">
                <form action="{{ route('incidents.index') }}" method="get" id="filtrarIncidentes" class="mb-3">

                    @if (Auth::user()->type==1)

                    
                    <div class="row">
                        <div class="form-group col-md pr-3 pr-md-1">
                            <label for="client_id">@lang('main.incidents.filters.client')</label>
                            <select class="form-control" id="client_id" name="client_id">
                                <option value="all">@lang('main.incidents.filters.all_clients')</option>
                                @if ($clients)
                                    @foreach ($clients as $key => $val)
                                    <option value="{{$key}}" @selected($filters['client_id']==$key)>{{$val}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div> 
                        <div class="form-group col-md pr-3">
                            <label for="assigned">@lang('main.incidents.filters.assigned')</label>
                            <select class="form-control" id="assigned" name="assigned">
                                <option value="all">@lang('main.incidents.filters.all_users')</option>
                                @foreach ($users as $key => $val)
                                    <option value="{{$key}}" @selected($filters['assigned']==$key)>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>  
                    </div>

                    <div class="row">
                        <div class="form-group col-md pr-3 pr-md-1">
                            <label for="group_id">@lang('main.incidents.filters.group')</label>
                            <select class="form-control" id="group_id" name="group_id">
                                <option value="all">@lang('main.incidents.filters.all_groups')</option>
                                @foreach ($groups as $key => $val)
                                <option value="{{$key}}" @selected($filters['group_id']==$key)>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md pr-3">
                            <label for="area_id">@lang('main.incidents.filters.area')</label>
                            <select id="area_id" name="area_id" class="form-control">
                                <option value="all">@lang('main.incidents.filters.all_areas')</option>
                                @if ($areas)
                                @foreach ($areas as $key => $val)
                                <option value="{{$key}}" @selected($filters['area_id']==$key)>
                                    {{$val}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md pr-3 pr-md-1">
                            <label for="module_id">@lang('main.incidents.filters.module')</label>
                            <select id="module_id" name="module_id" class="form-control">
                                <option value="all">@lang('main.incidents.filters.all_modules')</option>
                                @if ($modules)
                                @foreach ($modules as $key => $val)
                                <option value="{{$key}}" @selected($filters['module_id']==$key)>
                                    {{$val}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md pr-3">
                            <label for="problem_id">@lang('main.incidents.filters.problem')</label>
                            <select id="problem_id" name="problem_id" class="form-control">
                                <option value="all">@lang('main.incidents.filters.all_problems')</option>
                                @if ($problems)
                                @foreach ($problems as $key => $val)
                                <option value="{{$key}}" @selected($filters['problem_id']==$key)>
                                    {{$val}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                    </div>
                    

                    <div class="row">
                        <div class="form-group col-md pr-3 pr-md-1">
                            <label for="status_id">@lang('main.incidents.filters.status')</label>
                            <select class="form-control" id="status_id" name="status_id">
                                <option value="all">@lang('main.incidents.filters.all_statuses')</option>
                                <option value="opened" @selected($filters['status_id']=='opened')>@lang('main.incidents.filters.opened_ext')</option>
                                <option value="ended" @selected($filters['status_id']=='ended')>@lang('main.incidents.filters.closed_ext')</option>
                                <option value="0" @selected($filters['status_id']=='0')>@lang('main.incidents.filters.unassigned')</option>
                                <option value="1" @selected($filters['status_id']=='1')>@lang('main.incidents.filters.in_progress')</option>
                                <option value="5" @selected($filters['status_id']=='5')>@lang('main.incidents.filters.paused')</option>
                                <option value="10" @selected($filters['status_id']=='10')>@lang('main.incidents.filters.resolved')</option>
                                <option value="20" @selected($filters['status_id']=='20')>@lang('main.incidents.filters.closed')</option>
                                <option value="50" @selected($filters['status_id']=='50')>@lang('main.incidents.filters.canceled')</option>
                            </select>
                        </div>

                        <div class="form-group col-md">
                            <label for="pid">@lang('main.incidents.filters.priority')</label>
                            <select id="pid" name="pid" class="form-control">
                                <option value="all">@lang('main.incidents.filters.all_priorities')</option>
                                @foreach ($priorities as $key => $val)
                                <option value="{{$key}}" @selected($filters['pid']==$key)>
                                    {{ trans_fb('main.priorities.'.$val) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <label for="status_id">@lang('main.incidents.filters.status')</label>
                        <select class="form-control" id="status_id" name="status_id">
                            <option value="all">@lang('main.incidents.filters.all_statuses')</option>
                            <option value="opened" @selected($filters['status_id']=='opened')>@lang('main.incidents.filters.opened_ext')</option>
                            <option value="ended" @selected($filters['status_id']=='ended')>@lang('main.incidents.filters.closed_ext')</option>
                        </select>
                    </div>
                    @endif
                    

                </form>
                <div class="row m-0">
                    <button onclick="filtrar()" id="filtrar" class="col-auto btn btn-outline-success">@lang('main.incidents.filters.apply_filters')</button>
                    <button onclick="eliminar_filters()" id="elimiar_filters" class="col-auto btn btn-outline-danger ml-3">@lang('main.incidents.filters.remove')</button>
                    @if (Auth::user()->type==1)
                        <div class="col m-0 p-0 text-right">
                            <button onclick="mis_incidentes()" id="elimiar_filters" class="btn btn-outline-slate ml-3">@lang('main.incidents.filters.my_tasks')</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection


@push('css')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/buscador-bootstrap.js') }}"></script>

<script>

    

    $('#filtrarModal').on('shown.bs.modal', function () {
          $('#usuario').trigger('focus')
    })

    function filtrar() {
        $("#filtrarIncidentes").submit();
    }

    function eliminar_filters() {
        $('#assigned').val('all');
        $('#group_id').val('all');
        $('#client_id').val('all');
        $('#problem_id').val('all');
        $('#module_id').val('all');
        $('#area_id').val('all');
        $('#status_id').val('all');
        $('#pid').val('all');
        $("#filtrarIncidentes").submit();
    }

    function mis_incidentes() {
        $('#assigned').val('{{Auth::user()->id}}');
        $('#group_id').val('all');
        $('#client_id').val('all');
        $('#problem_id').val('all');
        $('#module_id').val('all');
        $('#area_id').val('all');
        $('#status_id').val('opened');
        $('#pid').val('all');
        $("#filtrarIncidentes").submit();
    }

    function getUnassigned() {
        $('#assigned').val('all');
        $('#group_id').val('all');
        $('#client_id').val('all');
        $('#problem_id').val('all');
        $('#module_id').val('all');
        $('#area_id').val('all');
        $('#status_id').val('0');
        $('#pid').val('all');
        $("#filtrarIncidentes").submit();
    }

    $('.ordenar').on('click', function () {
        //console.log($(this).attr('id'))

        $('#order').val($(this).attr('id'));
        document.getElementById("orderIncidents").submit();
        
    })

</script>

@endpush