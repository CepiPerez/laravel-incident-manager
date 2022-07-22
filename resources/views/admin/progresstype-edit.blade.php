@extends('layouts.main')

@section('content')


<div class="container pb-3">

    <div class="row">
      <h3 class="col pt-2">@lang('main.pro_types.edit_title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <div class="editor">

      <form action="{{ route('progresstypes.update', $progress_type->id) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
  
        <div class="form-group">
          <label for="description">@lang('main.common.description')</label>
          <input class="form-control" id="description" name="description" 
          value="{{ trans_fb('main.pro_types.'.$progress_type->description) }}">
        </div>
        
        @if($progress_type->id!=101)
        <div class="form-check pb-2 ml-0">
          <input type="checkbox" class="form-check-input" name="creator_visible" id="creator_visible"
            @checked($progress_type->creator_visible==1)
            @if($progress_type->id==30 || $progress_type->id==100) disabled @endif>
          <label class="ml-2 form-check-label" style="padding-top:2px;" 
            onclick="document.getElementById('creator_visible').click()">@lang('main.pro_types.creator_visible')</label>
        </div>
        @endif

        <div class="form-check pb-2 ml-0">
          <input type="checkbox" class="form-check-input" name="creator_email" id="creator_email"
           @checked($progress_type->creator_email==1)
           @if($progress_type->id==100) disabled @endif>
          <label class="ml-2 form-check-label" style="padding-top:2px;" 
            onclick="document.getElementById('creator_email').click()">@lang('main.pro_types.creator_email')</label>
        </div>

        <div class="form-check pb-3 ml-0">
          <input type="checkbox" class="form-check-input" name="internal_email" id="internal_email"
           @checked($progress_type->internal_email==1)
           @if($progress_type->id==100) disabled @endif>
          <label class="ml-2 form-check-label" style="padding-top:2px;" 
            onclick="document.getElementById('internal_email').click()">@lang('main.pro_types.internal_email')</label>
        </div>

        @if($progress_type->id!=30 && $progress_type->id!=100)
          <hr class="mt-0">
          <p class="mb-2">@lang('main.pro_types.email_template')</p>
          <textarea class="form-control p-2 mb-3" rows="10" id="html" name="html">{{ $template }}</textarea>
          <div class="card p-2 mb-3 scroll" hidden id="previa" style="min-height:226px;"></div>
          <p class="text-right btn btn-sm btn-outline-secondary mb-3" onClick="cambiarVista()" id="btnvista">@lang('main.pro_types.html_view')</p><br>
        @endif

        <button type="submit" class="col-auto btn btn-outline-slate mt-2">@lang('main.common.save_changes')</button>
  
      </form>

    </div>

    
</div>

@endsection


@push('scripts')

<script>

  function cambiarVista()
  {
    show = $('#previa').attr('hidden');
    if (!show)
    {
      $('#previa').attr('hidden', true);
      $('#html').attr('hidden', false);
      $('#btnvista').text('@lang('main.pro_types.html_view')');
    }
    else
    {
      var text = $('#html').val();
      text = text.replace('$INCIDENTE', '00123')
        .replace('$REPRESENTANTE_NUEVO', '@lang('main.pro_types.any_user')')
        .replace('$REPRESENTANTE', '@lang('main.pro_types.any_usertitle')')
        .replace('$DESTINO', '@lang('main.pro_types.any_user')')
        .replace('$DESCRIPCION', 'Detalle de la actualización: <br><b>Descripcion de ejemplo de avance del caso</b><br>')
        .replace('$LINK', '/app/incidente/00123/editar');

      $('#previa').html(text);
      $('#previa').attr('hidden', false);
      $('#html').attr('hidden', true);
      $('#btnvista').text('@lang('main.pro_types.code_view')');
    }

  }
</script>

@endpush
