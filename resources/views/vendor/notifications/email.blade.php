@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('¡Vaya!')
@else
# ¡Hola!
@endif
@endif

{{-- Intro Lines --}}
@if ($level !== 'error')
Estás recibiendo este correo porque hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta.
@endif

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
Restablecer Contraseña
@endcomponent
@endisset

{{-- Outro Lines --}}
@if ($level !== 'error')
Este enlace de restablecimiento de contraseña expirará en {{ config('auth.passwords.users.expire') }} minutos.

Si no solicitaste un restablecimiento de contraseña, no se requiere ninguna acción adicional.
@endif

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
    "Si tienes problemas para hacer clic en el botón \":actionText\", copia y pega la URL a continuación\n".
    'en tu navegador web:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
