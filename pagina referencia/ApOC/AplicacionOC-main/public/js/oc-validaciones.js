/**
 * Validaciones del lado del cliente para formularios de OC
 * Incluye validación de RUT chileno, montos, campos requeridos, etc.
 */

// Validar RUT chileno
function validarRut(rut) {
    if (!rut || rut === '') return false;
    
    // Eliminar puntos, espacios y guiones
    rut = rut.replace(/[^0-9kK]/g, '');
    
    if (rut.length < 2) return false;
    
    // Separar número y dígito verificador
    const cuerpo = rut.slice(0, -1);
    const dv = rut.slice(-1).toUpperCase();
    
    // Validar que el cuerpo sea numérico
    if (!/^\d+$/.test(cuerpo)) return false;
    
    // Calcular dígito verificador
    let suma = 0;
    let multiplo = 2;
    
    for (let i = cuerpo.length - 1; i >= 0; i--) {
        suma += parseInt(cuerpo.charAt(i)) * multiplo;
        multiplo = multiplo === 7 ? 2 : multiplo + 1;
    }
    
    const dvEsperado = 11 - (suma % 11);
    let dvCalculado = '';
    
    if (dvEsperado === 11) {
        dvCalculado = '0';
    } else if (dvEsperado === 10) {
        dvCalculado = 'K';
    } else {
        dvCalculado = dvEsperado.toString();
    }
    
    return dv === dvCalculado;
}

// Formatear RUT chileno (12345678-9)
function formatearRut(rut) {
    if (!rut) return '';
    
    // Eliminar todo excepto números y K
    rut = rut.replace(/[^0-9kK]/g, '');
    
    if (rut.length < 2) return rut;
    
    // Separar cuerpo y dígito verificador
    const cuerpo = rut.slice(0, -1);
    const dv = rut.slice(-1).toUpperCase();
    
    // Formatear con puntos y guión (opcional: agregar puntos de miles)
    return cuerpo + '-' + dv;
}

// Formatear monto chileno (1.234.567,89)
function formatearMonto(valor) {
    if (!valor) return '';
    
    // Convertir a número
    const numero = parseFloat(valor.toString().replace(/[^0-9.,]/g, '').replace(/\./g, '').replace(',', '.'));
    
    if (isNaN(numero)) return '';
    
    // Formatear con puntos de miles y coma decimal
    return numero.toLocaleString('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
}

// Validar email
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Validar formulario de OC antes de enviar
function validarFormularioOC(formulario) {
    const errores = [];
    
    // Validar CECO (si existe)
    const ceco = formulario.querySelector('[name="ceco"]');
    if (ceco && ceco.hasAttribute('required') && !ceco.value) {
        errores.push('Debe seleccionar un CECO');
        marcarError(ceco);
    } else if (ceco) {
        limpiarError(ceco);
    }
    
    // Validar tipo documento
    const tipoDocumento = formulario.querySelector('[name="tipo_documento"]');
    if (tipoDocumento && tipoDocumento.hasAttribute('required') && !tipoDocumento.value) {
        errores.push('Debe seleccionar un tipo de documento');
        marcarError(tipoDocumento);
    } else if (tipoDocumento) {
        limpiarError(tipoDocumento);
    }
    
    // Validar RUT proveedor
    const rutProveedor = formulario.querySelector('[name="rut_proveedor"]');
    if (rutProveedor && rutProveedor.value) {
        if (!validarRut(rutProveedor.value)) {
            errores.push('El RUT del proveedor no es válido');
            marcarError(rutProveedor);
        } else {
            limpiarError(rutProveedor);
        }
    }
    
    // Validar nombre proveedor
    const nombreProveedor = formulario.querySelector('[name="nombre_proveedor"]');
    if (nombreProveedor && nombreProveedor.hasAttribute('required') && !nombreProveedor.value) {
        errores.push('Debe ingresar o seleccionar un proveedor');
        marcarError(nombreProveedor);
    } else if (nombreProveedor) {
        limpiarError(nombreProveedor);
    }
    
    // Validar email proveedor (si existe)
    const emailProveedor = formulario.querySelector('[name="email_proveedor"]');
    if (emailProveedor && emailProveedor.value && !validarEmail(emailProveedor.value)) {
        errores.push('El email del proveedor no es válido');
        marcarError(emailProveedor);
    } else if (emailProveedor) {
        limpiarError(emailProveedor);
    }
    
    // Validar descripción
    const descripcion = formulario.querySelector('[name="descripcion"]');
    if (descripcion && descripcion.hasAttribute('required') && !descripcion.value.trim()) {
        errores.push('Debe ingresar una descripción');
        marcarError(descripcion);
    } else if (descripcion && descripcion.value.length > 5000) {
        errores.push('La descripción no puede exceder 5000 caracteres');
        marcarError(descripcion);
    } else if (descripcion) {
        limpiarError(descripcion);
    }
    
    // Validar cantidad
    const cantidad = formulario.querySelector('[name="cantidad"], [name="cantidad_participantes"], [name="cantidad_modulos"]');
    if (cantidad) {
        const valorCantidad = parseInt(cantidad.value);
        if (isNaN(valorCantidad) || valorCantidad < 1) {
            errores.push('La cantidad debe ser al menos 1');
            marcarError(cantidad);
        } else if (valorCantidad > 999999) {
            errores.push('La cantidad no puede exceder 999.999');
            marcarError(cantidad);
        } else {
            limpiarError(cantidad);
        }
    }
    
    // Validar monto
    const monto = formulario.querySelector('[name="monto"]');
    if (monto) {
        const valorMonto = parseFloat(monto.value);
        if (isNaN(valorMonto) || valorMonto <= 0) {
            errores.push('El monto debe ser mayor a 0');
            marcarError(monto);
        } else if (valorMonto > 999999999999.99) {
            errores.push('El monto no puede exceder 999.999.999.999,99');
            marcarError(monto);
        } else {
            limpiarError(monto);
        }
    }
    
    // Validar archivo adjunto con verificaciones de seguridad
    const archivo = formulario.querySelector('[name="adjunto"], [name="archivo"]');
    if (archivo && archivo.files.length > 0) {
        const erroresArchivo = validarArchivoSeguro(archivo.files[0]);
        if (erroresArchivo.length > 0) {
            errores.push(...erroresArchivo);
            marcarError(archivo);
        } else {
            limpiarError(archivo);
        }
    }
    
    return errores;
}

// Validar archivo con verificaciones de seguridad
function validarArchivoSeguro(file) {
    const errores = [];
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    // 1. Validar tamaño
    if (file.size > maxSize) {
        errores.push('El archivo no puede exceder 10MB');
    }
    
    if (file.size === 0) {
        errores.push('El archivo está vacío');
    }
    
    // 2. Validar extensión permitida (solo documentos)
    const extensionesPermitidas = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
    const extension = file.name.split('.').pop().toLowerCase();
    
    if (!extensionesPermitidas.includes(extension)) {
        errores.push('Solo se permiten archivos PDF, Word y Excel');
    }
    
    // 3. Detectar extensiones peligrosas
    const extensionesPeligrosas = [
        'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 
        'jar', 'app', 'deb', 'rpm', 'sh', 'ps1', 'msi', 'dmg',
        'htm', 'html', 'php', 'asp', 'aspx', 'jsp', 'cgi'
    ];
    
    if (extensionesPeligrosas.includes(extension)) {
        errores.push('Tipo de archivo no permitido por motivos de seguridad');
    }
    
    // 4. Validar nombre del archivo
    const nombreArchivo = file.name;
    
    // Detectar path traversal
    if (nombreArchivo.includes('..') || nombreArchivo.includes('/') || nombreArchivo.includes('\\')) {
        errores.push('El nombre del archivo contiene caracteres no permitidos');
    }
    
    // Detectar caracteres especiales peligrosos
    const caracteresProhibidos = /[<>:"|?*\x00-\x1F]/;
    if (caracteresProhibidos.test(nombreArchivo)) {
        errores.push('El nombre del archivo contiene caracteres especiales no permitidos');
    }
    
    // 5. Validar nombre no sospechoso
    const patronesSospechosos = [
        /\.exe\./i,
        /\.scr\./i,
        /\.bat\./i,
        /double.*extension/i,
    ];
    
    for (const patron of patronesSospechosos) {
        if (patron.test(nombreArchivo)) {
            errores.push('El archivo tiene un nombre sospechoso');
            break;
        }
    }
    
    // 6. Validar que tenga extensión
    if (!nombreArchivo.includes('.') || nombreArchivo.lastIndexOf('.') === nombreArchivo.length - 1) {
        errores.push('El archivo debe tener una extensión válida');
    }
    
    // 7. Validar longitud del nombre
    if (nombreArchivo.length > 255) {
        errores.push('El nombre del archivo es demasiado largo');
    }
    
    return errores;
}

// Marcar campo con error
function marcarError(campo) {
    campo.style.borderColor = '#dc2626';
    campo.style.borderWidth = '2px';
    
    // Agregar clase de error si no existe
    if (!campo.classList.contains('error')) {
        campo.classList.add('error');
    }
}

// Limpiar error de campo
function limpiarError(campo) {
    campo.style.borderColor = '';
    campo.style.borderWidth = '';
    campo.classList.remove('error');
}

// Mostrar mensaje de error en el formulario
function mostrarErrores(formulario, errores) {
    // Buscar o crear contenedor de errores
    let alertaErrores = formulario.querySelector('.alert-validacion');
    
    if (!alertaErrores) {
        alertaErrores = document.createElement('div');
        alertaErrores.className = 'alert alert-error alert-validacion';
        
        const formBody = formulario.querySelector('.form-body');
        if (formBody) {
            formBody.insertAdjacentElement('beforebegin', alertaErrores);
        }
    }
    
    alertaErrores.innerHTML = `
        <span style="font-size: 18px;">⚠</span>
        <div>
            <div>Por favor corrija los siguientes errores:</div>
            <ul class="alert-list">
                ${errores.map(error => `<li>${error}</li>`).join('')}
            </ul>
        </div>
    `;
    
    // Scroll al inicio del formulario
    formulario.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Limpiar mensajes de error
function limpiarMensajesError(formulario) {
    const alertaErrores = formulario.querySelector('.alert-validacion');
    if (alertaErrores) {
        alertaErrores.remove();
    }
}

// Inicializar validaciones para un formulario
function inicializarValidaciones(formulario) {
    // Prevenir envío del formulario si hay errores
    formulario.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Limpiar mensajes anteriores
        limpiarMensajesError(formulario);
        
        // Validar formulario
        const errores = validarFormularioOC(formulario);
        
        if (errores.length > 0) {
            mostrarErrores(formulario, errores);
            return false;
        }
        
        // Si todo está OK, enviar el formulario
        this.submit();
    });
    
    // Validación en tiempo real para RUT
    const camposRut = formulario.querySelectorAll('[name="rut_proveedor"], [name="rut_razon_social"]');
    camposRut.forEach(campo => {
        if (!campo.hasAttribute('readonly')) {
            campo.addEventListener('blur', function() {
                if (this.value) {
                    this.value = formatearRut(this.value);
                    
                    if (!validarRut(this.value)) {
                        marcarError(this);
                    } else {
                        limpiarError(this);
                    }
                }
            });
        }
    });
    
    // Validación en tiempo real para email
    const camposEmail = formulario.querySelectorAll('[name="email_proveedor"], [type="email"]');
    camposEmail.forEach(campo => {
        campo.addEventListener('blur', function() {
            if (this.value && !validarEmail(this.value)) {
                marcarError(this);
            } else {
                limpiarError(this);
            }
        });
    });
    
    // Validación en tiempo real para campos numéricos
    const camposNumero = formulario.querySelectorAll('[name="cantidad"], [name="cantidad_participantes"], [name="cantidad_modulos"], [name="monto"]');
    camposNumero.forEach(campo => {
        campo.addEventListener('blur', function() {
            const valor = parseFloat(this.value);
            
            if (isNaN(valor) || valor <= 0) {
                marcarError(this);
            } else {
                limpiarError(this);
            }
        });
    });
    
    // Limpiar error cuando el usuario comienza a escribir
    const todosCampos = formulario.querySelectorAll('input, select, textarea');
    todosCampos.forEach(campo => {
        campo.addEventListener('input', function() {
            limpiarError(this);
        });
    });
}

// Auto-inicializar validaciones en formularios de OC
document.addEventListener('DOMContentLoaded', function() {
    const formulariosOC = document.querySelectorAll('form.card, form[action*="/oc/"]');
    formulariosOC.forEach(formulario => {
        inicializarValidaciones(formulario);
    });
});
