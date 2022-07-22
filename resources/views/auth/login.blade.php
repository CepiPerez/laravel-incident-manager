@extends('layouts.header')

@section('content')
<div class="container" style="max-width:400px;">

<div class="card text-center">

    <div class="container p-3">
        
        <img src="{{ asset('assets/logonewrol.png') }}" alt="" height="36px;" class="mt-4 mb-4">

        <div class="card-body">
            <form action="{{ route('login') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" autofocus style="text-align:center;">
                </div>
                <div class="form-group">
                    <label for="password">Clave</label>
                    <input type="password" id="password" name="password" 
                    class="form-control" placeholder="Ingrese la clave" style="text-align:center;">
                </div>
                <div class="block mt-3">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>
                <br>

                <button type="submit" class="btn btn-info">Ingresar</button>
            </form>
        </div>
    </div>

</div>

</div>

</div>
@endsection
