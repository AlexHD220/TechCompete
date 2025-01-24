//======================================================================================>

// Pagina recargada
if(document.getElementById('fecha_competencia').value){
    document.getElementById('inicio_registros').removeAttribute('disabled');  
}

document.getElementById('fecha_competencia').addEventListener('input', function() {
  var inicioRegistros = document.getElementById('inicio_registros');  
  var finRegistros = document.getElementById('fin_registros');  

  if (this.value.trim() !== '') {
      inicioRegistros.removeAttribute('disabled');  
      
      inicioRegistros.value = ""; // Limpia el valor de fecha_inicio  cada vez que fecha competencia cambie
      finRegistros.value = ""; // Limpia el valor de fecha_fin cada vez que fecha competencia cambie
      finRegistros.setAttribute('disabled', 'true');
  } else {
      inicioRegistros.setAttribute('disabled', 'true');
      finRegistros.setAttribute('disabled', 'true');

      inicioRegistros.value = ""; // Limpia el valor de fecha_fin cada vez que fecha competencia cambie
      finRegistros.value = ""; // Limpia el valor de fecha_fin cada vez que fecha competencia cambie
  }
});


const fechaCompetenciaInput = document.getElementById('fecha_competencia');
const inicioRegistrosInput = document.getElementById('inicio_registros');
const finRegistrosInput = document.getElementById('fin_registros');

// Pagina recargada
if (fechaCompetenciaInput.value) {
    const fechaSeleccionada = new Date(fechaCompetenciaInput.value);
    fechaSeleccionada.setDate(fechaSeleccionada.getDate() - 1); // Sumar 1 día
    const maximoinicioRegistros = fechaSeleccionada.toISOString().split('T')[0]; // Formatear a YYYY-MM-DD

    inicioRegistrosInput.max = maximoinicioRegistros;
}

// Actualizar el mínimo de fecha_fin al cambiar fecha
fechaCompetenciaInput.addEventListener('change', function () {
  if (fechaCompetenciaInput.value) {
      const fechaSeleccionada = new Date(fechaCompetenciaInput.value);
      fechaSeleccionada.setDate(fechaSeleccionada.getDate() - 1); // Sumar 1 día
      const maximoinicioRegistros = fechaSeleccionada.toISOString().split('T')[0]; // Formatear a YYYY-MM-DD

      inicioRegistrosInput.max = maximoinicioRegistros;
  } else {                
      const yesterday = new Date();
      yesterday.setDate(yesterday.getDate() - 1);
      const yesterdayFormatted = yesterday.toISOString().split('T')[0];
      
      inicioRegistrosInput.max = yesterdayFormatted;          
  }
});


// Pagina recargada
if (fechaCompetenciaInput.value) {
    const fechaSeleccionada = new Date(fechaCompetenciaInput.value);      
    const maximofinregistros = fechaSeleccionada.toISOString().split('T')[0]; // Formatear a YYYY-MM-DD

    finRegistrosInput.max = maximofinregistros;
}

// Actualizar el mínimo de fecha_fin al cambiar fecha
fechaCompetenciaInput.addEventListener('change', function () {
  if (fechaCompetenciaInput.value) {
      const fechaSeleccionada = new Date(fechaCompetenciaInput.value);      
      const maximofinregistros = fechaSeleccionada.toISOString().split('T')[0]; // Formatear a YYYY-MM-DD

      finRegistrosInput.max = maximofinregistros;
  } else {                
      const today = new Date();
      today.setDate(today.getDate() + 1);
      const todayFormatted = today.toISOString().split('T')[0];
      
      finRegistrosInput.max = todayFormatted;          
  }
});


//-------------------------------------------------------------------------------------->

if(document.getElementById('inicio_registros').value){
    document.getElementById('fin_registros').removeAttribute('disabled');
}

document.getElementById('inicio_registros').addEventListener('input', function() {
    var finRegistros = document.getElementById('fin_registros');

    if (this.value.trim() !== '') {
        finRegistros.removeAttribute('disabled');
        finRegistros.value = ""; // Limpia el valor de fecha_fin cada vez que fecha inicio cambie
    } else {
        finRegistros.setAttribute('disabled', 'true');
        finRegistros.value = ""; // Limpia el valor de fecha_fin 
    }
});


// Pagina recargada
if (inicioRegistrosInput.value) {
    const fechaSeleccionada = new Date(inicioRegistrosInput.value);
    fechaSeleccionada.setDate(fechaSeleccionada.getDate() + 1); // Sumar 1 día
    const minimoFechaFin = fechaSeleccionada.toISOString().split('T')[0]; // Formatear a YYYY-MM-DD

    finRegistrosInput.min = minimoFechaFin;
}


// Actualizar el mínimo de fecha_fin al cambiar fecha
inicioRegistrosInput.addEventListener('change', function () {
    if (inicioRegistrosInput.value) {
        const fechaSeleccionada = new Date(inicioRegistrosInput.value);
        fechaSeleccionada.setDate(fechaSeleccionada.getDate() + 1); // Sumar 1 día
        const minimoFechaFin = fechaSeleccionada.toISOString().split('T')[0]; // Formatear a YYYY-MM-DD

        finRegistrosInput.min = minimoFechaFin;
    } else {                
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
        
        finRegistrosInput.min = tomorrowFormatted;          
    }
});

//======================================================================================>


