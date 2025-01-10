<!DOCTYPE html>
<html lang="es">

<x-plantilla-head>
    <title>Registro Jueces</title>
</x-plantilla-head>

<x-plantilla-body>

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <h1 style="display: inline;">Listado de Códigos para Registro de Jueces</h1>            
            @auth <!--Cuando el usuario este logueado muestrame lo sigiente-->
                <button class="btn btn-primary" onclick="window.location.href = '/registrojuez/create';">Generar nuevo código</button>
            @endauth
        </div>

        @if($expiradoscount > 0)
            <div style="margin-bottom: 20px;">
                <form action="{{ route('registrojuez.destroyexpirados') }}" method="POST"  onsubmit="return confirm('¿Estás seguro de eliminar todos los registros expirados?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-primary" onMouseOver="this.style.backgroundColor='#053482'" onmouseout="this.style.backgroundColor='#004ecf'" style="font-size: 14px; background-color: #004ecf; border:0px; box-shadow: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-trash" style="font-size: 12px; margin-right: 8px;"></i> <!-- Ícono de FontAwesome -->
                        Eliminar Registros Expirados
                    </div>
                </button>
                </form>
            </div>
        @endif

        @if($registrojueces->count() == 0)
            <p sty style="margin-left: 20px;"><i>No hay códigos pendientes de ser registrados.</i></p>
        @else
            
            <div style="margin-top: 15px;">

                @foreach ($registrojueces as $registrojuez) <!--Listar todos los datos de la tabla user-->
                    
                    <li> <!--*afasfdfddsfsdf (alejandro@gmail.com) | 13/08/24 (Quedan 10 dias para registrarse)-->
                        <b style="font-size: 20px;">{{ $registrojuez -> codigo }}</b>

                        (<i><a href="mailto:{{ $registrojuez -> email }}">{{ $registrojuez -> email }}</a></i>)

                        <b style="font-size: 20px;"> | </b>

                        @if
                            <b style="font-size: 17px; cursor: pointer;" 
                                onmouseover="this.style.color='#9294a1';" 
                                onmouseout="this.style.color='#6c7293';"                                
                                title="Fecha de Creación y Expiración">
                                {{ date('d/m/Y', strtotime($registrojuez->creacion_date)) }}
                            </b>
                        @else
                            <b id="fecha" 
                                style="font-size: 17px; cursor: pointer;" 
                                onmouseover="this.style.color='#9294a1';" 
                                onmouseout="this.style.color='#6c7293';" 
                                data-creacion="{{ date('d/m/Y', strtotime($registrojuez->creacion_date)) }}" 
                                data-expiracion="{{ date('d/m/Y', strtotime($registrojuez->expiracion_date)) }}" 
                                title="Fecha de Creación"
                                onclick="toggleCambiarFechaCodigo(this)">
                                {{ date('d/m/Y', strtotime($registrojuez->creacion_date)) }}
                            </b>
                        @endif
                        
                        
                        @if($registrojuez->diasrestantes == 0)
                            <h style="font-size: 15px;" onmouseover="this.style.color='#9294a1';" onmouseout="this.style.color='#6c7293';" title="Fecha de Expiración">
                            (<i>Queda menos de 1 día para registrarse</i>)</h>
                        
                        @elseif ($registrojuez->diasrestantes > 1)
                            <h style="font-size: 15px;" onmouseover="this.style.color='#9294a1';" onmouseout="this.style.color='#6c7293';" title="Fecha de Expiración">
                            (<i>Quedan {{ $registrojuez->diasrestantes }} días para registrarse</i>)</h>
                        @elseif ($registrojuez->diasrestantes == 1)
                            <h style="font-size: 15px;" onmouseover="this.style.color='#9294a1';" onmouseout="this.style.color='#6c7293';" title="Fecha de Expiración">
                            (<i>Queda solo {{ $registrojuez->diasrestantes }} día para registrarse</i>)</h>
                        @else
                            <h style="font-size: 15px;" onmouseover="this.style.color='#9294a1';" onmouseout="this.style.color='#6c7293';" title="Fecha de Expiración">
                            (<i>Quedan {{ $registrojuez->diasrestantes }} días</i>)</h>
                        @endif
                        
                        <h style="margin-right: 5px;"></h>

                        
                        <!-- Botón de copiar -->
                        <button class="btn-copy" data-codigo="{{ $registrojuez->codigo }}" onclick="copyToClipboard(this)"   onmouseover="this.style.backgroundColor='#818284';" onmouseout="this.style.backgroundColor='#434851';" 
                        style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #434851; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                        title="Copiar Código">
                            <i class="fas fa-copy" style="font-size: 15px;"></i>
                        </button>

                        <form action="{{ route('registrojuez.reenviarcorreo', $registrojuez) }}" method="POST" style="display: inline-block;">
                            @csrf

                            <button type="submit" onclick="return confirm('¿Desea reenviar este código de registro por correo electrónico?')" onmouseover="this.style.backgroundColor='#6594dc';" onmouseout="this.style.backgroundColor='#0052cf';"  
                            style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: #0052cf; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                            title="Reenviar Código por Correo Electrónico">
                                <i class="fas fa-envelope" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                            </button>
                        </form>
            

                        <form action="{{route('registrojuez.destroy', $registrojuez)}}" method = "POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            
                            <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar este código de registro?')" onmouseover="this.style.backgroundColor='#ff6666';" onmouseout="this.style.backgroundColor='red';"  
                            style="margin-left: 5px; margin-right: 5px; margin-top: 5px; background-color: red; color: white; border: none; padding: 5px; border-radius: 10%; display: inline-flex; justify-content: center; align-items: center;"
                            title="Eliminar Código">
                                <i class="fas fa-trash" style="font-size: 15px;"></i> <!-- Ícono de FontAwesome -->
                            </button>
                        </form>                                                

                    </li><br>
                    
                @endforeach
            </div>
            

        @endif
        <!--<br>
        <button onclick="window.location.href = '/registrojuez/create';">Registrar nuevo registrojuez</button>-->
    </div>
</x-plantilla-body>

</html>