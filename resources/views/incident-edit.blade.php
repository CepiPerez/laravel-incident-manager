@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">
        @lang('main.incidents.incident') #{{ sprintf("%'.06d", $incident->id) }}
      </h3>
      @if ($incident->status_id<10 && Auth::user()->type==1)
      <p class="col-auto text-secondary" style="height:2rem;padding-top:16px;padding-right:1.25rem;">
        @if ($sla->sla_default>0)
          @php $s = sla_expiration($incident->created_at, $incident->sla); @endphp
          @if ($s['expired']) 
            <span class="text-danger"> 
            <i class="ri-error-warning-line" style="vertical-align:middle;" aria-hidden="true"></i>
          @elseif ($s['hours'] < $sla->sla_notify)
            <span class="text-orange"> 
            <i class="ri-error-warning-line" style="vertical-align:middle;" aria-hidden="true"></i>
          @endif
          {{ $s['text'] }}
          @if ($s['expired'] || $s['hours'] < $sla->sla_notify) 
            </span>
          @endif
        @endif
      </p>
      @endif
    </div>
    <hr class="mb-3 mt-0">

    <form action="{{ route('incidents.update', $incident->id) }}" method="post" id="guardarCambios">
      @csrf
      @method('put')

      <div class="row m-0 p-0 editor">

        <!-- Panel Principal -->
        <div class="col-12 col-lg-9 m-0 p-0 pr-0 pr-lg-4 principal">

          <div class="form-group col-sm p-0">
            <input type="text" id="title" name="title" class="form-control" autofocus value="{{ $incident->title}}"
            @if(Auth::user()->type==0 || $incident->status->id>=20) disabled @endif>
          </div>

          <div class="form-group">
            <label for="description">@lang('main.common.description')</label>
            <textarea type="text" name="description" class="form-control" id="inc_desc" oninput="auto_grow(this)"
            @if(Auth::user()->type==0 || $incident->status->id>=20) disabled @endif>{{ $incident->description }}</textarea>
          </div>

          @if ($incident->attachments->count()>0)
            <label>@lang('main.incidents.attachments')</label>
            <div class="d-grid mb-3">

              @foreach ($incident->attachments as $att)

                @if ( check_img(Storage::path('attachments/'.$incident->id.'/0/'.$att->attachment)) )
                  <div class="btn btn-outline-secondary attachment img mb-2">
                    <a href="{{ route('incidents.attachment', [$incident->id, 0, $att->attachment]) }}"
                      target="_blank" style="text-decoration: none;">
                      <img src="{{asset('attachments/'.$incident->id.'/0/'.$att->attachment)}}" alt="" class="incident-attachment">
                      <span class="incident-attachment-text">{{$att->attachment}}</span>
                    </a>
                  </div>
                @else
                  <a href="{{ route('incidents.attachment', [$incident->id, 0, $att->attachment]) }}"
                    target="_blank" style="text-decoration: none;">
                    <div class="btn btn-outline-secondary attachment mb-2">
                      <img src="{{ get_icon_svg($att->attachment) }}" alt="" class="p-1" height=42 width=42><br>
                      {{ $att->attachment }}
                    </div>
                  </a>
                @endif
              
              @endforeach

            </div>
          @endif

          <div class="row">

            <div class="form-group col-md-6 pr-3 pr-md-1">
              <label for="client_id">@lang('main.incidents.client')</label>
              <select id="client_id" name="client_id" class="form-control" autofocus 
                @if(Auth::user()->type==0 || $incident->status->id>=20) disabled @endif>
                @foreach ($clients as $cli)
                  <option value="{{$cli['id']}}" @selected($incident->client->description==$cli['description'])>
                      {{$cli['description']}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-md">
              <label for="area_id">@lang('main.incidents.area')</label>
              <select id="area_id" name="area_id" class="form-control" 
                @if(Auth::user()->type==0 || $incident->status->id>=20) disabled @endif>
                <option value="{{$incident->area_id}}">{{$incident->area_desc}}</option>
              </select>
            </div>

            <div class="form-group col-md-6 pr-3 pr-md-1">
              <label for="module_id">@lang('main.incidents.module')</label>
              <select id="module_id" name="module_id" class="form-control" 
                @if(Auth::user()->type==0 || $incident->status->id>=20) disabled @endif>
                <option value="{{$incident->module_id}}">{{$incident->module_desc}}</option>
              </select>
            </div>

            <div class="form-group col-md">
              <label for="problem_id">@lang('main.incidents.problem')</label>
              <select id="problem_id" name="problem_id" class="form-control" 
                @if(Auth::user()->type==0 || $incident->status->id>=20) disabled @endif>
                <option value="{{$incident->problem_id}}">{{$incident->problem_desc}}</option>
              </select>
            </div>

            
          </div>


          <label>@lang('main.incidents.progress_notes')</label>

          <div class="bg-slate p-3">

            <!-- Avances del incidente -->
            @forelse ($incident->progress as $value)
              @if (Auth::user()->type==1 || $value->visible==1)
                <div class="card card-body m-0 pt-1 pl-1 pr-1 pb-0 mb-2  
                  @if ($value->progress_type_id==100) orange
                  @elseif ($value->progress_type_id==30) yellow
                  @else slate
                  @endif;">
                  <div class="m-0">
                    <div class="row m-0 pl-2 pr-2 pt-1"
                      @if ($value->description ||
                        $value->attachments ||
                        (($value->user_id==Auth::user()->id || Auth::user()->role_id==1) && $incident->status->id<20
                        ))
                        data-toggle="collapse" href="#collapse_{{$loop->index}}" role="button" aria-expanded="false" aria-controls="collapse_{{$loop->index}}"
                      @endif
                      >
                      <div class="col-6 p-0 text-left">
                        <p class="text-strong m-0 mb-2">
                        @if ($value->progress_type_id==2)
                          @lang('main.incidents.assigned_to') {{ $groups[$value->assigned_group_to] ?? '' }}
                          {{ $value->assigned_to ? ($value->assigned_group_to? '> '.$value->assigned_desc : '') : '' }}
                        @else
                          {{ trans_fb('main.pro_types.'.$value->pr_desc) }}
                        @endif
                        </p>
                      </div>
                      <div class="col-6 p-0 text-right">
                        <span class="d-none d-md-inline text-secondary">
                          <img src="{{ get_user_avatar($value->user_id) }}" alt=""
                               class="profilepic small">
                          {{ $value->user_desc }} 
                        </span>
                        <span class="m-0 mb-2 ml-3 text-secondary">
                          <i class="ri-calendar-line pr-1 text-primary" style="vertical-align:middle;"></i>
                          {{ date('d-m-Y', strtotime($value->created_at)) }}
                        </span>
                      </div>
                    </div>


                    @if ($value->description ||
                    $value->attachments ||
                    (($value->user_id==Auth::user()->id || Auth::user()->role_id==1) && $incident->status->id<20
                    ))
                    <div class="collapse" id="collapse_{{$loop->index}}">
                      

                      @if ($value->description)
                        <div class="row text-small ml-0 mr-2 mb-2">
                          <p class="pl-2 pr-2 m-0 pt-0 text-secondary">{{ htmlentities($value->description) }}</p>
                        </div>
                      @endif

                      @if ($value->attachments)
                        <div class="d-flex mb-2 ml-2">
                          @foreach ($value->attachments as $att)

                            @if ( check_img(Storage::path('attachments/'.$incident->id.'/'.$value->id.'/'.$att->attachment)) )
                              <div class="btn btn-outline-secondary attachment img">
                                <a href="{{ route('incidents.attachment', [$incident->id, $value->id, $att->attachment]) }}"
                                  target="_blank" style="text-decoration: none;">
                                  <img src="{{asset('attachments/'.$value->incident_id.'/'.$value->id.'/'.$att->attachment)}}" alt="" class="incident-attachment">
                                  <span class="incident-attachment-text">{{$att->attachment}}</span>
                                </a>
                              </div>
                            @else
                              <a href="{{ route('incidents.attachment', [$incident->id, $value->id, $att->attachment]) }}"
                                target="_blank" style="text-decoration: none;">
                                <div class="btn btn-outline-secondary attachment">
                                  <img src="{{ get_icon_svg($att->attachment) }}" alt="" class="p-1" height=42 width=42><br>
                                  {{ $att->attachment }}
                                </div>
                              </a>
                            @endif      
                          @endforeach
                        </div>
                      @endif

                      @if (($value->user_id==Auth::user()->id || Auth::user()->role_id==1) && $incident->status->id<20)
                        <div class="col text-right text-small m-0 p-0 mb-2 pr-2 pb-1">
                          <div class="col-auto text-right p-0 pl-0 m-0 mb-0 text-dark"> 
                            <a href="#" onclick="eliminarAvance('{{route('incident.progress.destroy', [$incident->id, $value->id]) }}')"
                              class="btn btn-sm btn-plain danger m-0 pl-2 pr-2 pt-0 pb-0">
                              <i class="ri-xl ri-delete-bin-7-line mr-1 m-0 p-0" style="font-size:.9rem;"></i>
                              @lang('main.incidents.att_remove')
                            </a> 
                          </div>
                        </div>
                      @endif

                    </div>

                    @endif                   

                  </div>
                </div>
              @endif
            @empty
              <p>@lang('main.incidents.no_progress')</p>
            @endforelse

            <div class="botonera p-0 m-0 mt-3">
              @if(Auth::user()->type==1 && $incident->status->id<20)
                <span class="col-auto btn btn-plain btn-sm orange p-0 mr-3" onclick="addPrivateNote()">
                  <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>
                  @lang('main.incidents.add_private_note')
                </span>
                <span class="col-auto btn btn-plain btn-sm p-0 mr-3" onclick="addProgress()">
                  <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>
                  @lang('main.incidents.add_progress')
                </span>
              @elseif (Auth::user()->type==0 && $incident->status->id<20)
                <span class="col-auto btn btn-plain btn-sm p-0 mr-3" onclick="addUserNote()">
                  <i class="ri-add-line mr-2 m-0 p-0" style="vertical-align:middle;"></i>
                  @lang('main.incidents.add_note')
                </span>
              @endif
            </div>

          </div>


          <div class="separador"></div>
          
        </div>

        <!-- Panel Lateral -->
        <div class="col-none col-lg-3 pl-0 pl-lg-4 mt-3 mt-lg-0">

          <div class="row m-0 p-0">

            <div class="col-6 col-lg-12 form-group m-0 p-0">
              <label for="cliente">@lang('main.incidents.table.created')</label>
              <input class="col form-control" type="text" id="fecha" 
                @if(Auth::user()->type==0 || $incident->status->id>=20) disabled @endif
                name="fecha" autocomplete="false" value="{{ date('d-m-Y H:i', strtotime($incident->created_at)) }}"
                style="max-width:130px;">
            </div>
  
            <div class="col-6 col-lg-12 form-group m-0 pl-4 pl-lg-0 mt-lg-4">
              <label>@lang('main.incidents.table.creator')</label>
              <br>
              <div class="mt-0 mt-lg-0">
                <img src="{{ get_user_avatar($incident->creator_user->id) }}" alt="" class="profilepic">
                <span style="font-size: 1.1rem;">{{ $incident->creator_user->name }}</span>
              </div>
            </div>

          </div>

          <div class="row m-0 p-0 mt-3">

            <div class="col-6 col-lg-12 form-group pt-2 pl-0">
              <label for="cliente">@lang('main.incidents.table.status')</label><br>
              <i class="badge @if ($incident->status->id==0) badge-orange
                  @elseif ($incident->status->id==5) badge-teal
                  @elseif ($incident->status->id==10) badge-green
                  @elseif ($incident->status->id==20) badge-gray
                  @elseif ($incident->status->id==50) badge-lightgray
                  @else badge-blue
                  @endif">{{ __('main.status.'.$incident->status->description) }}</i>
            </div>
    
            <div class="col-6 col-lg-12 form-group pt-2 pl-4 pl-lg-0">
              <label>@lang('main.incidents.table.priority')</label>
              <br>
              <img src="{{asset('assets/icons/'.$incident->pid.'.svg')}}" alt="" class="priority mr-2">
              <span style="font-size: 1.1rem;">{{ trans_fb('main.priorities.'.$incident->pdesc) }}</span>
            </div>

            <div class="col-6 col-lg-12 form-group pt-2 pl-0">
              <label>@lang('main.incidents.assigned_group')</label>
              <br>
              @if ($incident->group_id)
                <img src="{{ asset('profile/group.png') }}" alt="" class="profilepic">
                <span style="font-size: 1.1rem;">{{ $groups[$incident->group_id] }}</span>
              @else
                <img src="{{ asset('profile/no_group.png') }}" alt="" class="profilepic">
                <span style="font-size: 1.1rem;opacity:.7;">@lang('main.incidents.table.unassigned')</span>
              @endif
            </div>

            <div class="col-6 col-lg-12 form-group pt-2 pl-4 pl-lg-0">
              <label>@lang('main.incidents.assigned_user')</label>
              <br>
              @if ($incident->assigned_user)
                  <img src="{{ get_user_avatar($incident->assigned_user->id) }}" alt="" class="profilepic">
                  <span style="font-size: 1.1rem;">{{ $incident->assigned_user->name }}</span>
              @else
                  <img src="{{ asset('profile/unassigned.png') }}" alt="" class="profilepic">
                  <span style="font-size: 1.1rem;opacity:.7;">@lang('main.incidents.table.unassigned')</span>
              @endif
            </div>

          </div>


          @if (Auth::user()->type==1 && $incident->status->id<20)
      
            <hr class="mt-0 mt-lg-5">
            <div class="row m-0">
                <button type="submit" class="btn btn-outline-slate">@lang('main.common.save_changes')</button>
            </div>

          @elseif ($incident->status_id < 20)

            <hr class="mt-5">
            <div class="row m-0">
              @if($incident->status_id==10)
                <span class="btn btn-outline-slate mb-2" onclick="reopenIncident()">
                  @lang('main.incidents.reopen_incident')
                </span>
              @endif
              <span class="btn btn-outline-slate mr-2" onclick="closeIncident()">
                @lang('main.incidents.close_incident')
              </span>
              @if($incident->progress->count()==0 && $incident->status_id==0)
                <span class="btn btn-outline-slate mt-2" onclick="cancelIncident()">
                  @lang('main.incidents.cancel_incident')
                </span>
              @endif
            </div>
            
          @endif
            
        </div>
  
      </div>
    </form>
  
</div>


{{-- @livewire('incident-edit', ['incident_id' => $id]) --}}


<form action="" method="post" id="eliminarAvance">
  @csrf
  @method('delete')
</form>

<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" hidden 
      data-delay="5000" style="position:absolute;top:1rem;right:1rem;opacity:1;">
  <div class="toast-header bg-danger" style="height:1rem;">
  </div>
  <div class="toast-body">
    <div class="row">
      <div class="col-auto pt-1 mr-3" id="toast-list">
        <li>@lang('main.incidents.error_incomplete')</li>
      </div>
      <div class="col">
        <button type="button" class="ml-auto mb-1 close" data-dismiss="toast" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
  </div>
</div>


@endsection


@section('modal')

    <!-- Modal add progress -->
    @if (Auth::user()->type==1)
    <div class="modal fade" id="agregarAvance" tabindex="-1" role="dialog" aria-labelledby="agregarAvanceLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="agregarAvanceLabel">@lang('main.incidents.add_progress')</h5>
            </div>
            <div class="editor" style="margin:15px;">
                <form action="{{ route('incident.progress.store', $incident->id) }}" method="post" id="guardarAvance" enctype="multipart/form-data">
                  @csrf

                    <div class="form-group" id="selectorAvance">
                    <label for="progress_type">@lang('main.pro_types.title')</label>
                    <select class="form-control" id="progress_type" name="progress_type">
                        @foreach ($progress_types as $pt)
                          <option value="{{$pt->id}}">{{ trans_fb('main.pro_types.'.$pt->description) }}</option>
                        @endforeach
                    </select>
                    </div>  

                    <input type="hidden" id="progress_type_sub" name="progress_type_sub">

                    <div class="text-danger mb-4" id="nota_danger" hidden>
                      @lang('main.incidents.caution_msg')
                    </div>

                    <div id="asignacion" hidden>

                      <div class="form-group">
                        <label for="assign_group">@lang('main.incidents.assigned_group')</label>
                        <select class="form-control" id="assign_group" name="assign_group">
                          @foreach ($groups as $key => $val)
                            <option value="{{$key}}" @selected(old('group_id')==$key)>
                                {{ $val }}</option>
                          @endforeach
                        </select>
                        </div> 
  
                      <div class="form-group">
                      <label for="assign_user">@lang('main.incidents.assigned_user')</label>
                      <select class="form-control" id="assign_user" name="assign_user">
                          {{-- @foreach ($usuarios as $key => $val)
                          <option value="{{$key}}" @selected($incident->asignado? $incident->asignado==$key : Auth::user()->Usuario==$key)>{{$val}}</option>
                          @endforeach --}}
                      </select>
                      </div> 

                    </div>

                    
                    <div class="form-group">
                      <label for="description">@lang('main.common.description')</label>
                      <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                        rows="3"></textarea>
                    </div>

                    {{-- <div class="form-group">
                      <label>@lang('main.incidents.attachment')</label><br>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFileLang" name="archivo">
                        <label class="custom-file-label" data-browse="Seleccionar" for="customFileLang">@lang('main.incidents.att_select')</label>
                      </div>
                    </div> --}}

                    <div class="form-group">
                      <label>@lang('main.incidents.attachments')</label><br>
                      <div>
                          <input type="file" name="archivo[]" multiple id="archivo">
                      </div>
                    </div>
                    

                </form>
                <button onclick="guardarAvance()" class="btn btn-outline-slate">@lang('main.common.save')</button>
            </div>
        </div>
      </div>
    </div>
    @endif

@endsection


@push('css')

<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/newdatetimepicker.css') }}">
<link href="{{ asset('assets/css/filepond.css') }}" rel="stylesheet" />

@endpush


@push('scripts')

<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/newdatetimepicker.js') }}"></script>
<script src="{{ asset('assets/js/filepond.js') }}"></script>

<script type="module" >
  @if (Config::get('app.locale')=='es')
  import es_es from '{{ asset('assets/js/filepond_es.js') }}';
  FilePond.setOptions(es_es);
  @endif

  const inputElement = document.querySelector('input[id="archivo"]');
  const pond = FilePond.create(inputElement);
  const root = document.querySelector('.filepond--root');
  
  root.addEventListener('FilePond:processfilerevert', (e) => {
      $.ajax({
        url: '/upload/delete',
        type: 'POST',
        data: {'_token': '{!! csrf_token() !!}', 'filename': e.detail.file.serverId}
      })
  });	

  FilePond.setOptions({
    allowMultiple: true,
    server: {
      url: '/upload',
      process: {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        timeout: 7000
      }
    }
  });
    
</script>

<script>
  
  var select_area = '{{__('main.incidents.select_area')}}';
  var select_module = '{{__('main.incidents.select_module')}}';
  var select_problem = '{{__('main.incidents.select_problem')}}';
  var select_user = '{{__('main.incidents.select_user')}}';
  var not_found = '{{__('main.incidents.no_results')}}';
  var searching = '{{__('main.incidents.searching')}}';

  $('#client_id').select2();
  $('#area_id').select2();
  $('#module_id').select2();
  $('#problem_id').select2();
  $('#status').select2();
  $('#progress_type').select2();
  $('#assign_group').select2();
  $('#assign_user').select2();

  function addProgress() {
    $('#agregarAvanceLabel').text('{{__('main.incidents.add_progress')}}');
    $('.modal-header').removeClass('bg-danger');
    $('.modal-header').removeClass('bg-warning');
    $('#selectorAvance').attr('hidden', false);
    $('#progress_type').val($("#progress_type option:first").val()).change();
    $('#progress_type_sub').val('progress');
    $('#agregarAvance').modal('toggle');
  }

  function addPrivateNote() {
    $('#agregarAvanceLabel').text('{{__('main.incidents.add_private_note')}}');
    $('.modal-header').removeClass('bg-danger');
    $('.modal-header').addClass('bg-warning');
    $('#selectorAvance').attr('hidden', true);
    $('#progress_type_sub').val('note');
    $('#progress_type').val('100').change();
    $('#agregarAvance').modal('toggle');
  }

  function addUserNote() {
    $('#agregarAvanceLabel').text('{{__('main.incidents.add_note')}}');
    $('.modal-header').removeClass('bg-danger');
    $('.modal-header').removeClass('bg-warning');
    $('#selectorAvance').attr('hidden', true);
    $('#progress_type_sub').val('note');
    $('#progress_type').val('30').change();
    $('#agregarAvance').modal('toggle');
  }

  function cancelIncident() {
    $('#agregarAvanceLabel').text('{{__('main.incidents.cancel_incident')}}');
    $('.modal-header').removeClass('bg-warning');
    $('.modal-header').addClass('bg-danger');
    $('#selectorAvance').attr('hidden', true);
    $('#nota_danger').attr('hidden', false);
    $('#nota_danger').text('ATENCION: Una vez cerrado el incidente, el mismo ya no podrá ser modificado.');
    $('#progress_type_sub').val('cancel');
    $('#progress_type').val('50').change();
    $('#agregarAvance').modal('toggle');
  }

  function closeIncident() {
    $('#agregarAvanceLabel').text('{{__('main.incidents.close_incident')}}');
    $('.modal-header').removeClass('bg-warning');
    $('.modal-header').addClass('bg-danger');
    $('#selectorAvance').attr('hidden', true);
    $('#nota_danger').attr('hidden', false);
    $('#nota_danger').text('ATENCION: Una vez cerrado el incidente, el mismo ya no podrá ser modificado.');
    $('#progress_type_sub').val('close');
    $('#progress_type').val('20').change();
    $('#agregarAvance').modal('toggle');
  }

  function reopenIncident() {
    $('#agregarAvanceLabel').text('{{__('main.incidents.reopen_incident')}}');
    $('.modal-header').removeClass('bg-warning');
    $('.modal-header').removeClass('bg-danger');
    $('#selectorAvance').attr('hidden', true);
    $('#nota_danger').attr('hidden', true);
    $('#progress_type_sub').val('reopen');
    $('#progress_type').val('6').change();
    $('#agregarAvance').modal('toggle');
  }

  $('#customFileLang').on('change',function() {
      var fileName = $(this).val();
      var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
      $(this).next('.custom-file-label').html(cleanFileName);
  })

  $('#customFileLang2').on('change',function() {
      var fileName = $(this).val();
      var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
      $(this).next('.custom-file-label').html(cleanFileName);
  })

  $('#customFileLang3').on('change',function() {
      var fileName = $(this).val();
      var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
      $(this).next('.custom-file-label').html(cleanFileName);
  })

  $('#progress_type').on('change',function() {
      if($(this).val() >= 20)
        $('#nota_danger').attr('hidden', false);
      else
        $('#nota_danger').attr('hidden', true);

      if($(this).val()==2)
        $('#asignacion').attr('hidden', false);
      else
        $('#asignacion').attr('hidden', true);


  })

  $('#agregarAvance').on('shown.bs.modal', function () {
        $('#progress_type').trigger('focus')
  })

  $('#agregarNota').on('shown.bs.modal', function () {
        $('#d_nota').trigger('focus')
  })

  function guardarIncidente() {
      document.getElementById("guardarCambios").submit();
  } 

  function guardarAvance() {
      document.getElementById("guardarAvance").submit();
  }

  function cierreCliente() {
      document.getElementById("cierreCliente").submit();
  }

  function cancelCliente() {
      document.getElementById("cancelCliente").submit();
  }

  function eliminarAvance(url) {
    $('#eliminarAvance').attr('action', url);
    $('#eliminarAvance').submit();
  }

  function reabrirIncidente() {
    document.getElementById("reabrirIncidente").submit();
  }

  function guardarNota() {
      document.getElementById("guardarNota").submit();
  }

  function auto_grow(element) {
      element.style.height = "5px";
      element.style.height = (element.scrollHeight)+"px";
  }

  $(document).ready(function(e)
  {
    var start = true;

    auto_grow(document.getElementById('inc_desc'));

    window.addEventListener('resize', function(event){
      auto_grow(document.getElementById('inc_desc'));
    });

    $('#fecha').datetimepicker({
      format:'d-m-Y H:i',
      formatTime:'H:i',
      formatDate:'d-m-Y',
      step: 10

    });

    
    $('#client_id').on('change', function ()
    {
      if (!start)
      {
        $("#area_id").children().remove();
        $("#module_id").children().remove();
        $("#problem_id").children().remove();
      }

      $.ajax({
        url: '/utilities/get_data/areas/'+this.value+'/',
        dataType: 'json',
      }).done(function (data) {
        $("#area_id").select2({
          language: { noResults: () => not_found, searching: () => searching },
          placeholder: select_area,
          data: data,
        });
        if (data.length>0 && !start) 
          $('#area_id select').val(data[0].id).change();
        $('#area_id').change();
      });
    });

    $('#area_id').on('change', function ()
    {
      console.log("AREA: "+this.value)
      if (!start)
      {
        $("#module_id").children().remove();
        $("#problem_id").children().remove();
      }

      $.ajax({
        url: '/utilities/get_data/modules/'+this.value+'/',
        dataType: 'json',
      }).done(function (data) {
        $("#module_id").select2({
          language: { noResults: () => not_found, searching: () => searching },
          placeholder: select_module,
          data: data,
        });
        if (data.length>0 && !start) 
          $('#module_id select').val(data[0].id).change();
  
        $('#module_id').change();
      });

    });

    $('#module_id').on('change', function ()
    {
      if (!start)
        $("#problem_id").children().remove();

      $.ajax({
        url: '/utilities/get_data/problems/'+this.value+'/',
        dataType: 'json',
      }).done(function (data) {
        $("#problem_id").select2({
          language: { noResults: () => not_found, searching: () => searching },
          placeholder: select_problem,
          data: data,
        });
      });

      start = false;
    });

    $('#assign_group').on('change', function ()
    {
      //if (this.value != {{$incident->group ?? 0}})
        $("#assign_user").children().remove();

      $('#assign_user').select2({
        language: { noResults: () => not_found, searching: () => searching },
        placeholder: select_user,
        ajax: {
          url: '/utilities/get_data/users/'+this.value+'/',
          type: "GET",
          dataType: 'json',
          delay: 250,
          processResults: function (response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });

    $('#client_id').change();
    //$('#area_id').change();
    //$('#module_id').change();
    $('#assign_group').change();

    //start = false;

    /* $('form').on('submit', function() {

      console.log('AREA: ' +$('#area_id').val());
      console.log('MODULE: ' +$('#module_id').val());
      console.log('PROBLEM: ' +$('#problem_id').val());
      console.log('ASSIGNED: ' +$('#assigned').val());
      console.log('title: ' +$('#title').val());
      console.log('description: ' +$('#description').val());

      if ($('#area_id').val()==undefined || $('#module_id').val()==undefined || $('#problem_id').val()==undefined
        || $('#title').val()=='' || $('#description').val()=='')
      {
        $('.toast').prop('hidden', false);
        $('.toast').toast('show');        
        return false;
      }

    }); */

    
  });

</script>

@endpush


