@extends('layouts.main')

@section('content')

<link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

<div class="container mb-3">

    <div class="row mr-0">
        <h3 class="col-sm pt-2">@lang('main.pro_types.title')</h3>
    </div>
    <hr class="mb-3 mt-0">

    <table class="table">
        <thead>
          <tr>
            <th style="width:5rem;">ID</th>
            <th class="th-auto">@lang('main.pro_types.progress_type')</th>
            <th style="text-align:right;width:7rem;">@lang('main.common.actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($progress_types as $tipo)
          <tr>
            <td>{{$tipo->id}}</td>
            <td>{{ trans_fb('main.pro_types.'.$tipo->description) }}</td>
            <td class="text-right no-pointer" style="word-spacing:.5rem;"> 
                <a href="{{ route('progresstypes.edit', $tipo->id) }}" class="ri-lg ri-edit-line"></a>
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>

    {{ $progress_types->links() }}

</div>

@endsection