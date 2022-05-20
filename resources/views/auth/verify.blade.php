@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header titulo">{{ __('Verifica tu correo electronico') }}</div>

                <div class="card-body row col-md-12 justify-content-center">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un nuevo enlace de verificación se ha enviado a su dirección de correo electrónico.') }}
                        </div>
                    @endif

                    {{ __('Antes de continuar, compruebe su correo eletrónico para enviar un enlace de verificación.') }}
                    {{ __('Si no recibió el correo') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="Enviar" class="btn btn-link p-0 m-0 align-baseline">{{ __('Pulse aquí para enviar otro') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
