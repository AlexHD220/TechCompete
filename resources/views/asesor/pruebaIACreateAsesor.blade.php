<x-guest-layout>
    <x-authentication-card-register>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <div class="flex items-center justify-center">
            <b><h1 style="margin-bottom: 15px; font-size: 20px;">Valida tu credencial</h1></b>
        </div>

        <form id="upload-form" enctype="multipart/form-data">
            @csrf

            <div class="mt-4">
                <x-label for="imagen" value="{{ __('Subir credencial institucional o identificación oficial') }}" />
                <div>
                    <div style="display: flex; align-items: center; margin-top: 4px;">
                        <label for="imagen" id="imagen-button" class="custom-file-label" style="font-size: 16px;">Seleccionar imagen</label>                    
                    </div>

                    <input type="file" id="imagen" name="imagen" placeholder="imagen" accept=".png, .jpg, .jpeg" required />
                    <b><div id="file-name" class="file-name" style="margin-left: 10px;">Ningún archivo seleccionado</div></b>
                </div>  
            </div>

            <!-- Botón extra para validar la credencial -->
            <div class="flex items-center justify-between mt-4" style="margin-top: 30px;">
                <x-button class="ml-4" type="button" id="validarCredencialBtn">
                    {{ __('Validar Credencial') }}
                </x-button>
            </div>
        </form>

        <!-- Mensaje de validación -->
        <div id="resultado" style="margin-top: 20px; font-size: 16px;"></div>

    </x-authentication-card-register>

    <div style="margin-bottom: 60px;"></div>

    <script>
        // Mostrar el nombre del archivo cuando se selecciona una imagen
        document.getElementById('imagen').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : "Ningún archivo seleccionado";
            document.getElementById('file-name').textContent = fileName;
        });

        document.getElementById('validarCredencialBtn').addEventListener('click', async function (event) {
            event.preventDefault(); // Prevenir el envío tradicional del formulario

            const resultadoDiv = document.getElementById('resultado');
            resultadoDiv.innerHTML = 'Procesando...'; // Mensaje de "Procesando"

            const formData = new FormData();
            const imagenFile = document.getElementById('imagen').files[0];

            // Verificar si se ha seleccionado una imagen
            if (!imagenFile) {
                alert("Por favor, selecciona una imagen.");
                return;
            }

            // Agregar la imagen al FormData
            formData.append('imagen', imagenFile);

            try {
                const response = await fetch('http://localhost:5000/procesar-imagen', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                resultadoDiv.innerHTML = ''; // Limpiar mensaje de "Procesando"

                if (response.ok) {
                    const { mensaje, codigo_coincide, nombre_coincide, escuela_coincide } = data;

                    const resultElement = document.createElement('div');
                    resultElement.classList.add('message');

                    // Cambiar el estilo según el resultado
                    if (codigo_coincide.includes("✅") && nombre_coincide.includes("✅") && escuela_coincide.includes("✅")) {
                        resultElement.classList.add('success');
                    }  else if (codigo_coincide.includes("✅") || nombre_coincide.includes("✅") || escuela_coincide.includes("✅")) {
                        resultElement.classList.add('warning');
                    }
                    else {
                        resultElement.classList.add('error');
                    }

                    // Construir el resultado con color según la coincidencia
                    resultElement.innerHTML = `
                        <b>${mensaje}</b><br><br>
                        <span class="highlight"><span class="label" style="color: ${codigo_coincide.includes("✅") ? '#28a745' : '#dc3545'};">Código:</span> <span class="${codigo_coincide.includes("✅") ? 'success' : 'error'}">${codigo_coincide}</span></span><br>
                        <span class="highlight"><span class="label" style="color: ${nombre_coincide.includes("✅") ? '#28a745' : '#dc3545'};">Nombre:</span> <span class="${nombre_coincide.includes("✅") ? 'success' : 'error'}">${nombre_coincide}</span></span><br>
                        <span class="highlight"><span class="label" style="color: ${escuela_coincide.includes("✅") ? '#28a745' : '#dc3545'};">Escuela:</span> <span class="${escuela_coincide.includes("✅") ? 'success' : 'error'}">${escuela_coincide}</span></span>
                    `;

                    resultadoDiv.appendChild(resultElement);
                } else {
                    const errorElement = document.createElement('div');
                    errorElement.classList.add('error');
                    errorElement.innerHTML = `<p>Error: ${data.error}</p>`;
                    resultadoDiv.appendChild(errorElement);
                }
            } catch (error) {
                const errorElement = document.createElement('div');
                errorElement.classList.add('error');
                errorElement.innerHTML = `<p>Error al procesar la imagen. Inténtalo nuevamente.</p>`;
                resultadoDiv.appendChild(errorElement);
            }
        });
    </script>
</x-guest-layout>