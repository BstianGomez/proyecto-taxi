<script>
    // Configuración global para Toasts (notificaciones discretas)
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Función para facilitar el uso
    window.showAlert = function(icon, title) {
        // Evitar mostrar alertas al navegar con el botón atrás/adelante
        if (window.performance && window.performance.navigation && window.performance.navigation.type === 2) return;
        Toast.fire({
            icon: icon,
            title: title
        });
    }
</script>
