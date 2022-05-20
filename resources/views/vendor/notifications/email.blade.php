@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# ¡Ooops!
@else
# ¡Hola!
@endif
@endif

{{-- Intro Lines
@foreach ($introLines as $line)
{{ $line }}

@endforeach --}}

<p>Estás recibiendo este mensaje porque has pedido resetear la contraseña de tu cuenta</p>

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines
@foreach ($outroLines as $line)
{{ $line }}

@endforeach --}}

<p>Si no has pedido recuperar tu contraseña, simplemente obvia el mensaje</p>

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Saludos,<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "Si tienes algún problema al clicar el boton \":actionText\", copia y pega la siguiente URL\n".
    'en tu navegador:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
