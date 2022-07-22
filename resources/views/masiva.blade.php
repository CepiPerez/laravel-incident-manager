@extends('layouts.main')

@section('content')

<style>
    .custom-file-upload {
        background: #f0f0f0; 
        padding: 8px;
        border: 1px solid #e3e3e3; 
        border-radius: 5px; 
        border: 1px solid #ccc; 
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }
    .custom-file-upload:hover {
        background: #e0e0e0; 
    }
</style>

<div class="container">

    <div class="row mr-0">
        <h3 class="col pt-2">Carga masiva de incidentes</h3>
        <div class="col-12 col-lg-6 text-right pr-0">
            <a href="{{ route('cargamasiva.descargarexcel') }}" class="col-auto btn btn-outline-slate btn-sm ml-2 mt-2 mb-2 pl-3 pr-3">
                Descargar plantilla base</a>
        </div>
    </div>
    <hr class="mb-3 mt-0">

    <form class="pt-3" action="{{ route('cargamasiva.guardar') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Archivo a procesar</label><br>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFileLang" name="archivo" accept=".xls,.xlsx">
                <label class="custom-file-label" data-browse="Seleccionar" for="customFileLang">Seleccionar Archivo</label>
            </div>
        </div>

        <button type="submit" class="btn btn-outline-slate mt-3">Procesar</button>
            

    </form>    


</div>

<script>
    $('#customFileLang').on('change',function() {
        var fileName = $(this).val();
        var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
        $(this).next('.custom-file-label').html(cleanFileName);
    })
</script>

@endsection
