@extends('layouts.main')


@section('content')

  <link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

  <div class="container">

    <div class="row mr-0">
        <h3 class="col mt-2">@lang('main.dashboard.title')</h3>
        <button class="col-auto btn btn-filter-slate btn-sm pl-3 pr-3 mt-2" data-toggle="modal" data-target="#filtrarModal">
          @if ( isset($filters) && !empty(array_intersect(array_keys(
              array_filter($filters, function($k) { return $k !== null;}))
              , ['assigned', 'group_id'])) )
              @lang('main.incidents.filters_applied')
          @else
              @lang('main.incidents.filter')
          @endif
      </button>
    </div>
    <hr class="mb-3 mt-0">

    @if ($counter->total>0)
      <div class="m-0 counter big title mb-3" onclick="openLink('{{ route('dashboard.index', 'registered') }}')">
        <div class="value">{{$counter->total}}</div>
        <div class="text">@choice('main.dashboard.registered', $counter->total)</div>
      </div>

      <div style="width:100%; height: 1rem;
        display: grid; margin-bottom: .5rem;
        grid-template-columns: {{$counter->unassigned}}fr {{$counter->in_progress}}fr 
        {{$counter->paused}}fr {{$counter->resolved}}fr 
        {{$counter->closed}}fr {{$counter->canceled}}fr;
      ">
        <div id="unassigned" style="background-color: var(--orange);"></div>
        <div id="in_progress" style="background-color: var(--yellow);"></div>
        <div id="paused" style="background-color: var(--teal);"></div>
        <div id="resolved" style="background-color: var(--green);"></div>
        <div id="closed" style="background-color: var(--gray);"></div>
        <div id="canceled" style="background-color: lightgray;"></div>
      </div>

      <div class="d-flex justify-content-start" style="gap:1rem;flex-wrap:wrap;">
        
        <div class="counter" onclick="openLink('{{ route('dashboard.index', 'unassigned') }}')">
          <div class="value" style="color: var(--orange);">{{$counter->unassigned}}</div>
          <div class="text">@choice('main.dashboard.unassigned', $counter->unassigned)</div>
        </div>

        <div class="counter" onclick="openLink('{{ route('dashboard.index', 'in_progress') }}')">
          <div class="value" style="color: var(--yellow);">{{$counter->in_progress}}</div>
          <div class="text">@choice('main.dashboard.in_progress', $counter->in_progress)</div>
        </div>

        <div class="counter" onclick="openLink('{{ route('dashboard.index', 'paused') }}')">
          <div class="value" style="color: var(--teal);">{{$counter->paused}}</div>
          <div class="text">@choice('main.dashboard.paused', $counter->paused)</div>
        </div>

        <div class="counter" onclick="openLink('{{ route('dashboard.index', 'resolved') }}')">
          <div class="value" style="color: var(--green);">{{$counter->resolved}}</div>
          <div class="text">@choice('main.dashboard.resolved', $counter->resolved)</div>
        </div>

        <div class="counter" onclick="openLink('{{ route('dashboard.index', 'closed') }}')">
          <div class="value" style="color: var(--gray);">{{$counter->closed}}</div>
          <div class="text">@choice('main.dashboard.closed', $counter->closed)</div>
        </div>

        <div class="counter" onclick="openLink('{{ route('dashboard.index', 'canceled') }}')">
          <div class="value" style="color: lightgray;">{{$counter->canceled}}</div>
          <div class="text">@choice('main.dashboard.canceled', $counter->canceled)</div>
        </div>

      </div>
    @else
      <h5 class="mt-3">@lang('main.dashboard.no_incidents')</h5>
    @endif


    @if ($counter->opened>0 && $sla->sla_default>0)
      <div class="m-0 counter big title mt-5 mb-3" onclick="openLink('{{ route('dashboard.index', 'opened') }}')">
        <div class="value">{{$counter->opened}}</div>
        <div class="text">@choice('main.dashboard.opened', $counter->opened)</div>
      </div>

      <div style="width:100%; height: 1rem;
        display: grid; margin-bottom: .5rem;
        grid-template-columns: {{$counter->on_time}}fr {{$counter->to_expire}}fr 
        {{$counter->expired}}fr;
      ">
        <div id="on_time" style="background-color: var(--green);"></div>
        <div id="to_expire" style="background-color: var(--orange);"></div>
        <div id="expired" style="background-color: var(--red);"></div>
      </div>

      <div class="d-flex justify-content-start" style="gap:1rem;flex-wrap:wrap;">

        <div class="counter big" onclick="openLink('{{ route('dashboard.index', 'on_time') }}')">
          <div class="value" style="color: var(--green);">{{$counter->on_time}}</div>
          <div class="text">@choice('main.dashboard.on_time', $counter->on_time)</div>
        </div>

        <div class="counter big" onclick="openLink('{{ route('dashboard.index', 'to_expire') }}')">
          <div class="value" style="color: var(--orange);">{{$counter->to_expire}}</div>
          <div class="text">@choice('main.dashboard.to_expire', $counter->to_expire)</div>
        </div>

        <div class="counter big" onclick="openLink('{{ route('dashboard.index', 'expired') }}')">
          <div class="value" style="color: var(--red);">{{$counter->expired}}</div>
          <div class="text">@choice('main.dashboard.expired', $counter->expired)</div>
        </div>

      </div>
    @endif

    @if ($incidents)
      <h5 class="pb-1 mt-5" style="font-size:1.5rem;">{{ $status }}</h5>
  
      @if ($incidents->count() > 0)
        <table class="table ticketera">
          <thead>
          <tr>
            <th style="width:7rem;">@lang('main.common.incident')</th>
            <th class="th-auto">@lang('main.common.description')</th>
            <th class="d-none d-lg-table-cell" style="width:170px;">@lang('main.incidents.table.created')</th>
            <th class="d-none d-md-table-cell" style="width:150px;">@lang('main.incidents.table.assigned')</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($incidents as $incident)
            <tr onclick="abrirEnlace('{{ route('incidents.edit', $incident->id) }}')">
              <td>
                <a href="{{ route('incidents.edit', $incident->id) }}">
                  <img src="{{asset('assets/icons/'.$incident->pid.'.svg')}}" alt="" class="priority">
                  {{ sprintf("%'.06d", $incident->id) }}
                </a>
              </td>
              <td class="td-truncated">
                <a href="{{ route('incidents.edit', $incident->id) }}">
                  <span class="mr-2" style="font-weight:600;">{{ $incident->client->description }}</span>
                  {{ $incident->title }}
                </a>
              </td>
              <td class="d-none d-lg-table-cell">
                <a href="{{ route('incidents.edit', $incident->id) }}">
                  <span style="font-weight:500;">{{ date('d-m-Y ', strtotime($incident->created_at)) }}</span>
                  <span style="color:slategray;font-size:.75rem;">{{ date(' H:i', strtotime($incident->created_at)) }}</span>
                </a>
              </td>
              <td class="d-none d-md-table-cell" class="td-truncated">
                <a href="{{ route('incidents.edit', $incident->id) }}">
                  @if ($incident->status_id!=0 && $incident->assigned)
                    <img src="{{ get_user_avatar($incident->assigned) }}" alt="">
                    {{ $incident->assigned_name }}
                  @else
                    <img src="{{ asset('profile/unassigned.png') }}" alt="">
                    <span style="color:gray;">@lang('main.incidents.table.unassigned')</span>
                  @endif
                </a>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>

        {{ $incidents->links() }}
      
      @else
        <p>@lang('main.incidents.no_incidents_found')</p>
      @endif
    
    @endif
    
    {{-- @livewire('dashboard-table', ['filter'=>$filter, 'sla'=>$sla]) --}}


  </div>
    

</div>

@endsection


@push('css')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
@endpush


@push('scripts')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/buscador-bootstrap.js') }}"></script>

{{-- @livewireScripts --}}

<script>

  var select_user = '{{__('main.incidents.select_user')}}';
  var not_found = '{{__('main.incidents.no_results')}}';
  var searching = '{{__('main.incidents.searching')}}';
  $('#group_id').select2({language: { noResults: () => not_found, searching: () => searching }});
  $('#assigned').select2({language: { noResults: () => not_found, searching: () => searching }});

  function openLink(enlace) {
    window.location = enlace;
  }

  /* function setDashboardFilter(val) {
    console.log("FILTER:"+val)
    window.livewire.emit('dashboardfilter', val)
  } */

  function filter() {
    $("#dashboardFilters").submit();
  }

  function removefilters() {
    $('#group_id').val('all');
    $('#assigned').val('all');
    $("#dashboardFilters").submit();
  }

</script>

@endpush


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
                <form action="" method="get" id="dashboardFilters" class="mb-3">

                  <div class="form-group col-md p-0">
                    <label for="group_id">@lang('main.incidents.filters.group')</label>
                    <select class="form-control" id="group_id" name="group_id">
                      <option value="all">@lang('main.incidents.filters.all_groups')</option>
                      @foreach ($groups as $key => $val)
                      <option value="{{$key}}" @selected($filters['group_id']==$key)>{{$val}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group col-md p-0 mb-4">
                    <label for="assigned">@lang('main.incidents.filters.assigned')</label>
                    <select class="form-control" id="assigned" name="assigned">
                      <option value="all">@lang('main.incidents.filters.all_users')</option>
                      @foreach ($users as $key => $val)
                      <option value="{{$key}}" @selected($filters['assigned']==$key)>{{$val}}</option>
                      @endforeach
                    </select>
                  </div>

                </form>
                <div class="row m-0">
                  <button onclick="filter()" id="filtrar" class="col-auto btn btn-outline-success">@lang('main.incidents.filters.apply_filters')</button>
                  <button onclick="removefilters()" id="elimiar_filters" class="col-auto btn btn-outline-danger ml-3">@lang('main.incidents.filters.remove')</button>
                </div>
              </div>
          </div>
        </div>
    </div>
@endsection