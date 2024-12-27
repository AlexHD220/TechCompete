
<x-mail::message>

# Código de activación generado.

¡¡ Muchas gracias por formar parte de este evento como Juez y Evaluador !! <br>

<br>

A continuacion te mostramos el código de activación que debes utilizar para crear tu cuenta de usuario. <br>


<div style="font-family: Arial, sans-serif; margin: 20px 0;">
    <hr style="border: none; border-top: 2px solid #4b5563; margin: 10px 0;">
    <div style="font-size: 24px; font-weight: bold; color: #333;">
        <h1>{{ $registro -> codigo }}</h1>
    </div>
    <hr style="border: none; border-top: 2px solid #4b5563; margin: 10px 0;">
</div>

Este código de activación es único e intransferible, y está vinculado al correo electronico <b><i>"{{ $registro -> email }}"</i></b>.

<br>

Para crear tu cuenta deberas ingresar a la siguiente página y llenar el formulario de registro con tus datos.

<a href="{{route('juez.createjuez', $registro->codigo) }}" style="font-size: 15px;">Página de Registro</a>

<b><i>*Recuerda que tienes hasta antes del <u> {{ \Carbon\Carbon::parse($registro->expiracion_date)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}</u> para registrar tu cuenta o de lo contrario este código habrá expirado.</i></b>

<br>

¿Tienes algún Problema? <i>!Contáctanos¡</i>
- <a href="tel:+523323385972" target="_blank" > Teléfono: 3323385972 </a>
- <a href="https://api.whatsapp.com/send?phone=523323385972" target="_blank" > WhatsApp: 3323385972</a>
- <a href="mailto:dg967172@techcompete.com" target="_blank" > Correo electrónico: dg967172@techcompete.com </a>

</x-mail::message>