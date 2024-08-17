<!--<h1> Se creo el asesor {{ $asesor -> nombre }}. </h1>-->

<x-mail::message>
# Asesor creado
 
Se creó el asesor {{ $asesor -> nombre }}.

<a href="{{route('asesor.show', $asesor->id) }}" style="font-size: 15px;">Ver asesor</a>
 
<h1><b><i>{{ config('app.name') }}</i></b></h1>
</x-mail::message>