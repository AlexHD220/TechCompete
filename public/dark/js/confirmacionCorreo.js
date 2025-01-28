const email = document.getElementById('email');
const confirmEmail = document.getElementById('email_confirmation');
const emailError = document.getElementById('emailError');

document.getElementById('registroForm').addEventListener('submit', function (event) {
    if (email.value !== confirmEmail.value) {
        event.preventDefault(); // Evita que el formulario se envíe
        emailError.style.display = 'block'; // Muestra el mensaje de error
        confirmEmail.setCustomValidity('Los correos electrónicos no coinciden.'); // Establece un mensaje de validación
    } else {
        emailError.style.display = 'none'; // Oculta el mensaje de error
        confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
    }
});


if (confirmEmail.value != email.value) {
    emailError.style.display = 'block';
    confirmEmail.setCustomValidity('Los correos electrónicos no coinciden.'); // Actualiza el mensaje de validación
}

confirmEmail.addEventListener('input', function () {
    if(confirmEmail.value){    
        if (confirmEmail.value === email.value) {
            emailError.style.display = 'none';
            confirmEmail.setCustomValidity(''); // Limpia el mensaje si coinciden
        } else {
            emailError.style.display = 'block';
            confirmEmail.setCustomValidity('Los correos electrónicos no coinciden.'); // Actualiza el mensaje de validación
        }
    }
    else{
        emailError.style.display = 'none';        
        confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
    }
});

email.addEventListener('input', function () {
    if(confirmEmail.value){
        if (confirmEmail.value === email.value) {
            emailError.style.display = 'none';
            confirmEmail.setCustomValidity(''); // Limpia el mensaje si coinciden
        } else {
            emailError.style.display = 'block';
            confirmEmail.setCustomValidity('Los correos electrónicos no coinciden.'); // Actualiza el mensaje de validación
        }
    }
    else{
        emailError.style.display = 'none';        
        confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
    }
});



/*const email = document.getElementById('email');
const confirmEmail = document.getElementById('email_confirmation');
const emailError = document.getElementById('emailError');

//confirmEmail.addEventListener('input', function () {
//});

email.addEventListener('input', emailErrorMessage);

confirmEmail.addEventListener('input', emailErrorMessage);

function emailErrorMessage (){
    if(confirmEmail.value){
        if (confirmEmail.value === email.value) {
            emailError.style.display = 'none';        
            confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
        } else {
            emailError.style.display = 'inline';
        }
    }
    else{
        emailError.style.display = 'none';        
        confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
    }
};

document.getElementById('registroForm').addEventListener('submit', function (event) {
    if (email.value !== confirmEmail.value) {
        event.preventDefault(); // Evita que el formulario se envíe
        emailError.style.display = 'block'; // Muestra el mensaje de error
        confirmEmail.setCustomValidity('Los correos electrónicos no coinciden.'); // Establece un mensaje de validación
    } else {
        emailError.style.display = 'none'; // Oculta el mensaje de error
        confirmEmail.setCustomValidity(''); // Limpia el mensaje de validación
    }
});*/