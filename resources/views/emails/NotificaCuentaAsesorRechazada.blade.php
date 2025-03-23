
<x-mail::message>

# Lamentablemente no pudimos combrobar su identidad, revise las siguientes observaciones para conocer cuál fue la razón del problema:

<b>Observaciones.</b><br>
<i>{{ $observaciones }}</i>

<br>

<div style="font-family: Arial, sans-serif; margin: 20px 0;">
    <hr style="border: none; border-top: 1px solid #d4d1de; margin: 10px 0;">
</div>

<br>

ⓘ Para poder crear una cuenta como asesor es necesario comprobar su identidad por medio de una credencial institucional o identificación oficial.

<br>

Por favor revise la informacion proporcionada y actualicela lo más pronto posible a traves del siguiente página. <br>

<div style="display: flex; justify-content: center; align-items: center; text-align: center;">                            
    <a href="{{route('asesor.buscarcuentastore') }}" style="font-size: 15px;">Buscar cuenta de asesor.</a>                                
</div>

<br>

Utilizando el código de reporte que se muestra a coninuación para modificar la informacion previamente registrada.<br>

<div style="font-family: Arial, sans-serif; margin: 20px 0;">
    <hr style="border: none; border-top: 2px solid #4b5563; margin: 10px 0;">
    <div style="display: flex; justify-content: center; align-items: center; text-align: center;">                            
        <h1 style="font-size: 20px; font-weight: bold; color: #333; margin-bottom: 0px;">{{ $asesor -> codigo_rechazo }}</h1>                                   
    </div>
    <hr style="border: none; border-top: 2px solid #4b5563; margin: 10px 0;">
</div>

<b><i>ES MUY IMPORTANTE que no comparta este código con nadie, ya que este muestra la información proporcionada anteriormente al correo electronico <b><i>"{{ $asesor -> email }}" y contiene sus datos personales.</i></b>

<br>

¿Tienes algún Problema? <i>!Contáctanos¡</i>
- <a href="tel:+523323385972" target="_blank" > Teléfono: 3323385972 </a>
- <a href="https://api.whatsapp.com/send?phone=523323385972" target="_blank" > WhatsApp: 3323385972</a>
- <a href="mailto:dg967172@techcompete.com" target="_blank" > Correo electrónico: dg967172@techcompete.com </a> <!-- Correo de ejemplo -->

</x-mail::message>