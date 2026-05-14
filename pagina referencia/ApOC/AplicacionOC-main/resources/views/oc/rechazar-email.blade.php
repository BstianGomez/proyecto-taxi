<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechazar Solicitud</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <p>Cargando panel de rechazo...</p>
    </div>
    <script>
        Swal.fire({
            title: 'Rechazar Solicitud',
            input: 'textarea',
            inputLabel: 'Motivo de rechazo',
            inputPlaceholder: 'Escribe aquí el motivo...',
            showCancelButton: true,
            confirmButtonText: 'Rechazar O/C',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            inputValidator: (value) => {
                if (!value || value.trim() === '') {
                    return 'Debes ingresar un motivo para rechazar'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form or redirect with observation
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("oc.solicitudes.rechazar-email.post", $solicitud->id) }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const observation = document.createElement('input');
                observation.type = 'hidden';
                observation.name = 'observacion_rechazo';
                observation.value = result.value;
                form.appendChild(observation);
                
                document.body.appendChild(form);
                form.submit();
            } else {
                window.close();
            }
        });
    </script>
</body>
</html>
