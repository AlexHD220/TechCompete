
<x-mail::message>

# Proyecto registrado
 
Felicidades!! <br>
El proyecto "{{ $proyecto -> nombre }}" se registró correctamente en la competencia:

<h1>{{ $proyecto -> competencia -> identificador }}</h1>

<a href="{{route('competencia.show', $proyecto -> competencia->id) }}" style="font-size: 15px;">Ver detalles de la competencia</a>
 
Los esperamos el próximo {{ date('d/m/Y', strtotime($competencia->fecha)) }}.<br>
<h1><b><i>{{ config('app.name') }}</i></b></h1>

</x-mail::message>