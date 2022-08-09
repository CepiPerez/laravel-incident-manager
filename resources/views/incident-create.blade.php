@extends('layouts.main')

@section('content')


<div class="container pb-4 editor">

    <div class="row">
      <h3 class="col pt-2">@lang('main.incidents.new')</h3>
    </div>
    <hr class="mb-3 mt-0">


    <form action="{{ route('incidents.store') }}" method="post" enctype="multipart/form-data">
    @csrf

     

      <div class="row">

        @if (Auth::user()->type==1)
        <div class="form-group col-auto pr-0">
          <label for="date">@lang('main.incidents.date')</label>
          <input class="form-control" type="text" id="date" 
            name="date" autocomplete="false" value="{{ date('d-m-Y H:i') }}"
            style="max-width:130px;margin-top:1px;">
        </div>
        @endif

        
      </div>

      <div class="row">

        <div class="form-group col-md pr-3 pr-md-1">
          <label for="client_id">@lang('main.incidents.client')</label>
          <select id="client_id" name="client_id" @if (Auth::user()->type==0) disabled @endif class="form-control" autofocus>
            @foreach ($clients as $cli)
              <option value="{{$cli['id']}}" @selected(old('client_id')==$cli['id'])>
                  {{$cli['description']}}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md">
          <label for="area_id">@lang('main.incidents.area')</label>
          <select id="area_id" name="area_id" class="form-control">
          </select>
        </div>

      </div>

      <div class="row">

        <div class="form-group col-md pr-3 pr-md-1">
          <label for="module_id">@lang('main.incidents.filters.module')</label>
          <select id="module_id" name="module_id" class="form-control">
            {{-- @foreach ($modules as $mod)
              <option value="{{$mod->id}}" @selected(old('module')==$mod->id)>
                  {{$mod->description}}</option>
            @endforeach --}}
          </select>
        </div>

        <div class="form-group col-md">
          <label for="problem_id">@lang('main.incidents.filters.problem')</label>
          <select id="problem_id" name="problem_id" class="form-control">
            {{-- @foreach ($incident_types as $ti)
              <option value="{{$ti->id}}" @selected(old('incident_type')==$ti->id)>
                  {{$ti->description}}</option>
            @endforeach --}}
          </select>
        </div>

      </div>

      @if (Auth::user()->type==1)
      <div class="row">

        {{-- <div class="form-group col-md pr-3 pr-md-1">
          <label for="status">@lang('main.incidents.filters.status')</label>
          <select id="status" name="status" class="form-control">
            @foreach ($status as $st)
              <option value="{{$st->id}}" @selected(old('status')==$st->id)>
                  {{ __('main.status.'.$st->description) }}</option>
            @endforeach
          </select>
        </div> --}}

        <div class="form-group col-md pr-3 pr-md-1">
          <label for="group_id">@lang('main.incidents.assigned_group')</label>
          <select id="group_id" name="group_id" class="form-control">
            <option value="0">@lang('main.incidents.unnasigned_selection')</option>
            @foreach ($groups as $gr)
              <option value="{{$gr['id']}}" @selected(old('group_id')==$gr['id'])>
                  {{ $gr['description'] }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md">
          <label for="assigned">@lang('main.incidents.assigned_user')</label>
          <select id="assigned" name="assigned" class="form-control">
            <option value="0">@lang('main.incidents.unnasigned_selection')</option>
            {{-- @foreach ($users as $key => $val)
              <option value="{{$key}}" @selected(old('assigned')==$key || Auth::user()->id==$key)>
                  {{$val}}</option>
            @endforeach --}}
          </select>
        </div>

      </div>
      @endif

      <div class="form-group">
        <label for="title">@lang('main.incidents.title')</label>
        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
          value="{{ old('title') }}">
      </div>

      <div class="form-group">
        <label for="description">@lang('main.common.description')</label>
        <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror"
          id="description" oninput="auto_grow(this)" 
          {{-- rows="5" --}}>{{ old('description') }}</textarea>
      </div>

      <div class="form-group">
        <label>@lang('main.incidents.attachments')</label>
        {{-- <div>
          <input type="file" name="archivo[]" multiple id="archivo">
        </div> --}}
        <div class="attachemnts-card">
          <button class="btn btn-sm btn-outline-slate upload-btn" type="button">Agregar archivo</button>
          {{-- <form action="/upload" method="post" id="uploadform"> --}}
            <input name="archivo[]" type="file" class="file d-none" multiple 
            accept=".csv, .xls, .xlsx, .doc, .docx, .png, .jpeg, .jpg, .txt, .zip, .rar, .csv, .pdf"/>
          {{-- </form> --}}
          <div class="image-container"></div>
        </div>
      </div>

      <button type="submit" id="guardarCambios" class="col-auto btn btn-outline-slate mt-3">@lang('main.common.save')</button>

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

    
</div>

@endsection


@push('css')

<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/newdatetimepicker.css') }}">
<link href="{{ asset('assets/css/filepond.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/css/imageupload.css') }}" rel="stylesheet" />

@endpush


@push('scripts')

<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/newdatetimepicker.js') }}"></script>
<script src="{{ asset('assets/js/filepond.js') }}"></script>
<script src="{{ asset('assets/js/imageupload.js') }}"></script>


{{-- <script type="module" >
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
      },
    }
  });


</script> --}}

<script>

  var select_area = '{{__('main.incidents.select_area')}}';
  var select_module = '{{__('main.incidents.select_module')}}';
  var select_problem = '{{__('main.incidents.select_problem')}}';
  var select_user = '{{__('main.incidents.select_user')}}';
  var not_found = '{{__('main.incidents.no_results')}}';
  var searching = '{{__('main.incidents.searching')}}';

  $('#client_id').select2();
  $('#area_id').select2();
  $('#module_id').select2({language: { noResults: () => not_found, searching: () => searching },placeholder: select_module});
  $('#problem_id').select2({language: { noResults: () => not_found, searching: () => searching },placeholder: select_problem});
  $('#status').select2();
  $('#group_id').select2();
  $('#assigned').select2({language: { noResults: () => not_found, searching: () => searching },placeholder: select_user});


  $('#customFileLang').on('change',function()
  {
    var fileName = $(this).val();
    var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
    $(this).next('.custom-file-label').html(cleanFileName);
  })

  function auto_grow(element) {
      element.style.height = "5px";
      element.style.height = (element.scrollHeight)+"px";
  }
  

  $(document).ready(function(e)
  {
    auto_grow(document.getElementById('description'));

    window.addEventListener('resize', function(event){
      auto_grow(document.getElementById('description'));
    });

    $('#date').datetimepicker({
      format:'d-m-Y H:i',
      formatTime:'H:i',
      formatDate:'d-m-Y',
      step: 10

    });

    $('#client_id').on('change', function ()
    {
      $("#area_id").children().remove();
      $("#module_id").children().remove();
      $("#problem_id").children().remove();

      $.ajax({
        url: '/utilities/get_data/areas/'+this.value+'/',
        dataType: 'json',
      }).done(function (data) {
        $("#area_id").select2({
          language: { noResults: () => not_found, searching: () => searching },
          placeholder: select_area,
          data: data,
        });
        $("#area_id").change();
      });

    });

    $('#area_id').on('change', function ()
    {
      $("#module_id").children().remove();
      $("#problem_id").children().remove();

      $.ajax({
        url: '/utilities/get_data/modules/'+this.value+'/',
        dataType: 'json',
      }).done(function (data) {
        $("#module_id").select2({
          language: { noResults: () => not_found, searching: () => searching },
          placeholder: select_module,
          data: data,
        });
        $("#module_id").change();
      });

    });

    $('#module_id').on('change', function ()
    {
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
      
    });

    $('#group_id').on('change', function ()
    {
      $("#assigned").children().remove();
      
      $.ajax({
        url: '/utilities/get_data/users/'+this.value+'/',
        dataType: 'json',
      }).done(function (data) {
        $("#assigned").select2({
          language: { noResults: () => not_found, searching: () => searching },
          placeholder: select_problem,
          data: data,
        });
      });

    });

    $('#status').on('change', function ()
    {
      if (this.value==0)
      {
        $('#group_id').attr('disabled', true);
        $('#assigned').attr('disabled', true);
        $('#group_id').val(0).change();
      }
      else
      {
        $('#group_id').removeAttr('disabled');
        $('#assigned').removeAttr('disabled');
      }

    });


    $('#client_id').change();
    $('#status').change();

    $('form').on('submit', function() {

      console.log('AREA: ' +$('#area_id').val());
      console.log('MODULE: ' +$('#module_id').val());
      console.log('PROBLEM: ' +$('#problem_id').val());
      console.log('ASSIGNED: ' +$('#assigned').val());
      console.log('title: ' +$('#title').val());
      console.log('description: ' +$('#description').val());
      console.log('status: ' +$('#status').val());
      console.log('group: ' +$('#group_id').val());

      if ($('#area_id').val()==undefined || $('#module_id').val()==undefined 
        || $('#problem_id').val()==undefined || $('#title').val()=='' || $('#description').val()==''
        /* || ($('#status').val()!=0) */)
      {
        $('.toast').prop('hidden', false);
        $('.toast').toast('show');        
        return false;
      }
      

    });


  });

</script>

@endpush