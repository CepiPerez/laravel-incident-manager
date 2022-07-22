@extends('layouts.header')

@section('content')
<div class="container pt-5">
    <div class="text-center pb-2">
      <i class="fa fa-exclamation-circle text-danger text-center" style="font-size:32px;"></i>
    </div>
    <p class="text-center text-danger">404</p>
    <p class="text-center text-dark">@lang('errors.not_found')</p>
</div>
@endsection