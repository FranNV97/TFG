@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header titulo">{{ __('Solicitar cambio contraseña') }}</div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        Te hemos enviado un correo con el link de cambio de contraseña
                    </div>
                @endif

                <div class="card-body row col-md-12 justify-content-center">

                    <form class="col-md-6" method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">{{ __('Correo electrónico') }}</label>

                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="form-group">

                            <button type="submit" class="btn btn-primary">
                                {{ __('Enviar enlace') }}
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
