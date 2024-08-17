document.getElementById("showPassword").addEventListener("click", function () {
    var passwordField = document.getElementById("pass");
    if (passwordField.type === "password") {
      passwordField.type = "text";
    } else {
      passwordField.type = "password";
    }
  });

  /*document.getElementById("enviar").addEventListener("click", function () {
    var passwordField = document.getElementById("pass");
    if (passwordField.type === "text") {
      passwordField.type = "password";
    }
  });*/
  

  function cambiarTexto() {
    const boton = document.getElementById('showPassword');
    
    if (boton.innerHTML === 'Mostrar') {
      boton.innerHTML = 'Ocultar';
    } else {
      boton.innerHTML = 'Mostrar';
    }
  }



  const showPassword = document.getElementById('showPassword');

  document.getElementById("showPassword").addEventListener("click", function () {
    var passwordField = document.getElementById("password");
    if (passwordField.type === "password") {
      passwordField.type = "text";
      
      showPassword.classList.remove("fa-eye");
      showPassword.classList.add("fa-eye-slash");
    } else {
      passwordField.type = "password";
      
      showPassword.classList.remove("fa-eye-slash");
      showPassword.classList.add("fa-eye");
    }
  });

  

  const showPassword_confirmation = document.getElementById('showPassword_confirmation');

  document.getElementById("showPassword_confirmation").addEventListener("click", function () {
    var passwordField = document.getElementById("password_confirmation");
    if (passwordField.type === "password") {
      passwordField.type = "text";
      
      showPassword_confirmation.classList.remove("fa-eye");
      showPassword_confirmation.classList.add("fa-eye-slash");
    } else {
      passwordField.type = "password";
      
      showPassword_confirmation.classList.remove("fa-eye-slash");
      showPassword_confirmation.classList.add("fa-eye");
    }
  });


  /*const duracionInput = document.getElementById('duracion');  // Obtén el elemento del campo de número

  duracionInput.addEventListener('input', function() {  // Agrega un event listener para controlar los cambios en el campo

    let valor = parseInt(this.value); // Obtiene el valor actual del campo

    if (valor < 1) { // Verifica si el valor es menor que 1
      valor = 1;
    }

    if (valor > 60) { // Verifica si el valor es mayor que 60
      valor = 60;
    }

    this.value = valor; // Actualiza el valor del campo con el nuevo valor
  });*/


    const backToTopButton = document.getElementById("backToTopButton");

    // Controlar la visibilidad del botón
    function toggleBackToTopButton() {
        if (window.scrollY > 100) {   // mostrar cuando se desplaze 100 pixeles
            backToTopButton.style.display = "block";
        } else {
            backToTopButton.style.display = "none";
        }
    }

    // Detectar el desplazamiento de la página
    window.addEventListener("scroll", toggleBackToTopButton);
    //toggleBackToTopButton();



/*// Variable para mantener la URL de la página anterior
var paginaAnterior = null;

// Agrega un evento al botón de regresar
document.getElementById('pagAnterior').addEventListener('click', function() {
    if (paginaAnterior) {
        // Navega a la página anterior
        window.location.href = paginaAnterior;
    }
});

// Registra la página actual como la página anterior
if (document.referrer) {
    paginaAnterior = document.referrer;
}*/

function validarImagen() {
  var input = document.getElementById('imagen');
  var archivo = input.files[0];

  if (archivo) {
      var tipoImagen = archivo.type;
      if (tipoImagen === 'image/png' || tipoImagen === 'image/jpeg' || tipoImagen === 'image/jpg') {
          alert('Archivo válido: ' + tipoImagen);
      } else {
          alert('Por favor, selecciona un archivo PNG o JPG válido.');
          input.value = ''; // Limpiar el input para permitir seleccionar otro archivo
      }
  }
}

document.getElementById('createCompetencia').addEventListener('submit', function(event) {
  // Agrega aquí cualquier lógica adicional que necesites antes de enviar el formulario
  event.preventDefault(); // Evitar el envío real del formulario en este ejemplo
});