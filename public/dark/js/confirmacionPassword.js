const password = document.getElementById('password');
const confirmPassword = document.getElementById('password_confirmation');
const passwordError = document.getElementById('passwordError');

document.getElementById('registroForm').addEventListener('submit', function (event) {
    if (password.value !== confirmPassword.value) {
        event.preventDefault(); // Evita que el formulario se envíe
        passwordError.style.display = 'block'; // Muestra el mensaje de error
        confirmPassword.setCustomValidity('Las contraseñas no coinciden.'); // Establece un mensaje de validación
    } else {
        passwordError.style.display = 'none'; // Oculta el mensaje de error
        confirmPassword.setCustomValidity(''); // Limpia el mensaje de validación
    }
});

confirmPassword.addEventListener('input', function () {
    if(confirmPassword.value){    
        if (confirmPassword.value === password.value) {
            passwordError.style.display = 'none';
            confirmPassword.setCustomValidity(''); // Limpia el mensaje si coinciden
        } else {
            passwordError.style.display = 'block';
            confirmPassword.setCustomValidity('Las contraseñas no coinciden.'); // Actualiza el mensaje de validación
        }
    }
    else{
        passwordError.style.display = 'none';        
        confirmPassword.setCustomValidity(''); // Limpia el mensaje de validación
    }
});

password.addEventListener('input', function () {
    if(confirmPassword.value){
        if (confirmPassword.value === password.value) {
            passwordError.style.display = 'none';
            confirmPassword.setCustomValidity(''); // Limpia el mensaje si coinciden
        } else {
            passwordError.style.display = 'block';
            confirmPassword.setCustomValidity('Las contraseñas no coinciden.'); // Actualiza el mensaje de validación
        }
    }
    else{
        passwordError.style.display = 'none';        
        confirmPassword.setCustomValidity(''); // Limpia el mensaje de validación
    }
});
    
    
    /*const password = document.getElementById('password');
const confirmPassword = document.getElementById('password_confirmation');
const passwordError = document.getElementById('passwordError');

//confirmPassword.addEventListener('input', function () {
//});

password.addEventListener('input', passwordErrorMessage);

confirmPassword.addEventListener('input', passwordErrorMessage);

function passwordErrorMessage (){
    if(confirmPassword.value){
        if (confirmPassword.value === password.value) {
            passwordError.style.display = 'none';        
            confirmPassword.setCustomValidity(''); // Limpia el mensaje de validación
        } else {
            passwordError.style.display = 'inline';
        }
    }
    else{
        passwordError.style.display = 'none';        
        confirmPassword.setCustomValidity(''); // Limpia el mensaje de validación
    }
};

document.getElementById('registroForm').addEventListener('submit', function (event) {

    if (password.value !== confirmPassword.value) {        
        confirmPassword.setCustomValidity('Las contraseñas no coinciden.'); // Establece un mensaje de validación
        passwordError.style.display = 'block'; // Muestra el mensaje de error    
        event.preventDefault(); // Evita que el formulario se envíe   
        //return;         
    } 
    else {
        passwordError.style.display = 'none'; // Oculta el mensaje de error
        confirmPassword.setCustomValidity(''); // Limpia el mensaje de validación        
    }
});*/


//======================================================================================>


/*const password = document.getElementById('password');
const confirmPassword = document.getElementById('password_confirmation');
const passwordError = document.getElementById('passwordError');

confirmPassword.addEventListener('input', function () {
    if (confirmPassword.value === password.value) {
        passwordError.style.display = 'none';        
        confirmPassword.setCustomValidity(''); // Limpia el mensaje de validación
    } else {
        passwordError.style.display = 'inline';
    }
});

document.getElementById('registroForm').addEventListener('submit', function (event) {
    if (password.value !== confirmPassword.value) {
        event.preventDefault(); // Evita que el formulario se envíe
        passwordError.style.display = 'block'; // Muestra el mensaje de error
        confirmPassword.setCustomValidity('Las contraseñas no coinciden.'); // Establece un mensaje de validación
    } else {
        passwordError.style.display = 'none'; // Oculta el mensaje de error
        confirmPassword.setCustomValidity(''); // Limpia el mensaje de validación
    }
});*/


//======================================================================================>