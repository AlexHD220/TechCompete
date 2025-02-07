<x-guest-layout>

    <style>
        .boton {
            /*background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: opacity 0.3s;*/ /* Agrega una transición suave de 0.3 segundos */

            /*background-color: #eb1616; */
            background-color: #ec4747;
            color: #fff; 
            border: none; 
            padding: 10px 20px;
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px;
        }

        /* Estilos cuando el mouse se acerca al botón */
        .boton:hover {
            /*background-color: #e74848;*/
            background-color: #e27474;
            /*opacity: 0.5; /* Reduz la opacidad al 80% */
        }
    </style>

    <x-authentication-card-type>    

        <div class="text-center flex items-center justify-center">
            <b><h1 style="margin-top: -10px; font-size: 40px;">
                ¿Qué tipo de cuenta quieres crear?
            </h1></b>
        </div>

        <div class="text-center items-center justify-center" style="margin-top: 20px;">
            <div class="flex items-center justify-center" style=" margin-bottom: 30px;">
                <button onclick="window.location.href = '/institucion/signup';" class="boton" style="font-size: 20px; width: 200px;">
                    <b>Institución</b>
                </button>
            </div>

            <div class="flex items-center justify-center">
                <button onclick="window.location.href = '/asesor/create';" class="boton" style="font-size: 20px; width: 200px;">
                    <b>Asesor</b>
                </button>
            </div>
        </div>

        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>    

    </x-authentication-card-type>

</x-guest-layout>
