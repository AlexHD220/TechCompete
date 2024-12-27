function mostrarNotificacion(titulo, mensaje) {
    // Verificar si el navegador soporta notificaciones
    if (!("Notification" in window)) {
        alert("Este navegador no soporta notificaciones de escritorio.");
    } else if (Notification.permission === "granted") {
        // Mostrar la notificación
        const notificacion = new Notification(titulo, {
            body: mensaje,
            icon: "/images/notification-icon.png", // Cambia este valor al ícono que desees
        });

        // Configurar para que desaparezca después de unos segundos
        setTimeout(() => {
            notificacion.close();
        }, 5000); // Tiempo en milisegundos
    } else if (Notification.permission !== "denied") {
        // Pedir permiso al usuario
        Notification.requestPermission().then((permission) => {
            if (permission === "granted") {
                mostrarNotificacion(titulo, mensaje);
            }
        });
    }
}
