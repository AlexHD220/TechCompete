
<x-mail::message>

# Te damos la bienvenida {{ $asesor -> name }}.

Tu cuenta como Asesor ha sido aprobada y activada exitosamente. <br>

<i>A partir de este momento puedes acceder a tu cuenta desde la página <a href="{{ url('/login') }}" style="font-size: 15px;">inicio de sesión</a>.</i>

<br>

<b>ⓘ Recuerda que si es la primera vez que inicias sesión en la cuenta, primero deberás <a href="{{ $verificationUrl }}" style="font-size: 15px;">verificar tu cuenta de correo electrónico</a> antes de poder ingresar.</b>

<div style="font-family: Arial, sans-serif; margin: 20px 0;">
    <hr style="border: none; border-top: 1px solid #d4d1de; margin: 10px 0;">
    <i style="font-size: 14px;">Si tiene problemas para verificar su dirección de correo electrónico, copie y pegue la URL a continuación en su navegador web: <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a></i>
    <hr style="border: none; border-top: 1px solid #d4d1de; margin: 10px 0;">
</div>

<br>

¿Tienes algún Problema? <i>!Contáctanos¡</i>
- <a href="tel:+523323385972" target="_blank" > Teléfono: 3323385972 </a>
- <a href="https://api.whatsapp.com/send?phone=523323385972" target="_blank" > WhatsApp: 3323385972</a>
- <a href="mailto:dg967172@techcompete.com" target="_blank" > Correo electrónico: dg967172@techcompete.com </a> <!-- Correo de ejemplo -->

</x-mail::message>