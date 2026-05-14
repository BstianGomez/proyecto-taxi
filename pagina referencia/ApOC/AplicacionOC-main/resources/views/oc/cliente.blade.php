<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de OC Cliente</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />

    <style>
        @include('oc.partials.common_styles')
    </style>
    @include('oc.partials.common_scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'cliente'])
        <div class="main-content">
        <x-page-header 
            title="" 
            subtitle=""
            :backRoute="null"
        />

        <main class="content">
            <form class="card" method="POST" enctype="multipart/form-data">
                <div style="margin: -40px -40px 30px; padding: 24px 40px; border-bottom: 1px solid var(--line); background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); border-radius: 24px 24px 0 0;">
                    <div style="font-family: 'Space Grotesk', sans-serif; font-size: 20px; font-weight: 700; color: #0f172a;">Solicitud de OC Cliente</div>
                    <div style="font-size: 13px; color: var(--muted); margin-top: 4px;">Completa los campos para solicitar una orden de compra</div>
                </div>
                @csrf
                
                <!-- Honeypot para evitar bots -->
                <div style="display:none;" aria-hidden="true">
                    <label for="my_company_website">Sitio web modificado (no rellenar)</label>
                    <input type="text" name="my_company_website" id="my_company_website" tabindex="-1" autocomplete="off">
                </div>


                @if ($errors->any())
                    <div class="alert alert-error">
                        <span style="font-size: 18px;">⚠</span>
                        <div>
                            <div>Revisa los campos antes de enviar.</div>
                            <ul class="alert-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        <span style="font-size: 18px;">✓</span>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <div class="form-body">
                    <div class="form-grid three-col">
                        <div class="form-field">
                            <label class="form-label">Razón Social <span class="required">*</span></label>
                            <select class="select" name="razon_social" id="razonSocialSelect" required>
                                <option value="">Seleccione razón social</option>
                                @foreach($razonesSociales as $razonSocial)
                                    <option value="{{ $razonSocial->razon_social }}" data-rut="{{ $razonSocial->rut }}" data-codigo="{{ $razonSocial->cod_deudor }}">{{ $razonSocial->razon_social }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">RUT Razón Social <span class="required">*</span></label>
                            <input type="text" class="input" name="rut_razon_social" id="rutRazonSocial" readonly style="background: #f5f8fb;" />
                        </div>

                        <div class="form-field">
                            <label class="form-label">Código cliente <span class="required">*</span></label>
                            <input type="text" class="input" name="codigo_cliente" id="codigoCliente" readonly style="background: #f5f8fb;" />
                        </div>
                    </div>

                    <div class="form-grid three-col">
                        <div class="form-field">
                            <label class="form-label">Número de material <span class="required">*</span></label>
                            <input type="text" class="input" name="numero_material" required />
                        </div>

                        <div class="form-field">
                            <label class="form-label">Número de PV <span class="required">*</span></label>
                            <input type="text" class="input" name="numero_pv" required />
                        </div>

                        <div class="form-field">
                            <label class="form-label">Nombre curso <span class="required">*</span></label>
                            <input type="text" class="input" name="nombre_curso" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+" title="El nombre del curso no puede contener números" required />
                        </div>
                    </div>

                    <div class="form-grid three-col">
                        <div class="form-field">
                            <label class="form-label">Modalidad curso <span class="required">*</span></label>
                            <select class="select" name="modalidad_curso" required>
                                <option value="">Seleccione modalidad</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Online">Online</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Cantidad de horas <span class="required">*</span></label>
                            <input type="number" class="input" name="cantidad_horas" min="1" required />
                        </div>

                        <div class="form-field">
                            <label class="form-label">Cantidad de jornadas <span class="required">*</span></label>
                            <input type="number" class="input" name="cantidad_jornadas" min="1" required />
                        </div>
                    </div>

                    <div class="form-grid three-col">
                        <div class="form-field">
                            <label class="form-label">Fecha de inicio curso <span class="required">*</span></label>
                            <input type="date" class="input" name="fecha_inicio_curso" required />
                        </div>

                        <div class="form-field">
                            <label class="form-label">Fecha de término curso <span class="required">*</span></label>
                            <input type="date" class="input" name="fecha_termino_curso" required />
                        </div>

                        <div class="form-field">
                            <label class="form-label">Tipo de documento <span class="required">*</span></label>
                            <select class="select" name="tipo_documento" required>
                                <option value="">Seleccione tipo de documento</option>
                                <option value="Factura">Factura</option>
                                <option value="Factura Exenta">Factura Exenta</option>
                                <option value="Boleta de Honorarios">Boleta de Honorarios</option>
                                <option value="Boleta">Boleta</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid three-col">
                        <div class="form-field">
                            <label class="form-label">Nombre proveedor <span class="required">*</span></label>
                            <select class="select" name="nombre_proveedor" id="nombreProveedor" required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->nombre }}" data-rut="{{ $proveedor->rut }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>

                            <a href="#" id="btnOpenProveedorModal" style="display:block; margin-top:5px; font-size: 0.9em; color: var(--brand);">¿No existe el proveedor? Regístralo aquí</a>

                        </div>

                        <div class="form-field">
                            <label class="form-label">RUT proveedor <span class="required">*</span></label>
                            <input type="text" class="input" name="rut_proveedor" id="rutProveedor" readonly style="background: #f5f8fb;" />
                        </div>

                        <div class="form-field">
                            <label class="form-label">Cantidad <span class="required">*</span></label>
                            <input type="number" class="input" name="cantidad" value="1" min="1" required />
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Monto (fact monto Neto - honorarios monto bruto) <span class="required">*</span></label>
                            <div style="position: relative;">
                                <input type="text" class="input" id="montoDisplay" placeholder="$0" />
                                <input type="hidden" name="monto" id="montoValue" required />
                            </div>
                        </div>

                        <div class="form-field full-width">
                            <label class="form-label">Descripción producto o servicio <span class="required">*</span></label>
                            <textarea class="textarea" name="descripcion" required></textarea>
                        </div>

                        <div class="form-field full-width">
                            <label class="form-label">Observación / Comentario</label>
                            <textarea class="textarea" name="observacion" placeholder="Añada cualquier detalle o instrucción adicional aquí..."></textarea>
                        </div>
                    </div>

                    <div class="form-field full-width">
                        <label class="form-label">Datos adjuntos</label>
                        <div class="attachment-box">
                            <p>No hay archivo adjunto.</p>
                            <label class="file-input">
                                📎 Adjuntar cotización
                                <input type="file" name="adjunto" accept=".pdf,.doc,.docx,.xls,.xlsx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                            </label>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('oc.index') }}" class="btn btn-ghost">↩ Volver</a>
                    <button type="submit" class="btn btn-primary">📨 Enviar</button>
                </div>

            </form>
        </main>
        </div>
    </div>

    <script>
        // Auto-completar datos cuando se selecciona razón social
        const razonSocialSelect = document.getElementById('razonSocialSelect');
        const rutRazonSocialInput = document.getElementById('rutRazonSocial');
        const codigoClienteInput = document.getElementById('codigoCliente');
        
        razonSocialSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const rut = selectedOption.getAttribute('data-rut');
            const codigo = selectedOption.getAttribute('data-codigo');
            rutRazonSocialInput.value = rut || '';
            codigoClienteInput.value = codigo || '';
        });

        // Auto-completar RUT de proveedor cuando se selecciona el nombre
        const nombreProveedorSelect = document.getElementById('nombreProveedor');
        const rutProveedorInput = document.getElementById('rutProveedor');
        
        nombreProveedorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const rut = selectedOption.getAttribute('data-rut');
            rutProveedorInput.value = rut || '';
        });

        // Validación de fechas
        const fechaInicio = document.querySelector('input[name="fecha_inicio_curso"]');
        const fechaTermino = document.querySelector('input[name="fecha_termino_curso"]');
        
        fechaTermino.addEventListener('change', function() {
            if (fechaInicio.value && fechaTermino.value) {
                if (new Date(fechaTermino.value) < new Date(fechaInicio.value)) {
                    alert('La fecha de término debe ser igual o posterior a la fecha de inicio');
                    fechaTermino.value = '';
                }
            }
        });

    </script>
    
    <!-- Validaciones del lado del cliente -->
    <script src="{{ asset('js/oc-validaciones.js') }}"></script>

    <!-- Formateo de Monto como Dinero -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const montoDisplay = document.getElementById('montoDisplay');
            const montoValue = document.getElementById('montoValue');

            if (!montoDisplay || !montoValue) return;

            // Función para formatear como dinero
            function formatMoney(value) {
                const numValue = value.replace(/[^\d]/g, '');
                if (!numValue) return '';
                return '$' + parseInt(numValue).toLocaleString('es-CL');
            }

            // Función para obtener solo números
            function getNumericValue(value) {
                return value.replace(/[^\d]/g, '');
            }

            // Evento al escribir
            montoDisplay.addEventListener('input', function(e) {
                let value = e.target.value;
                const numericValue = getNumericValue(value);
                
                // Mostrar formato de dinero
                if (numericValue) {
                    e.target.value = formatMoney(value);
                    montoValue.value = numericValue;
                } else {
                    e.target.value = '';
                    montoValue.value = '';
                }
            });

            // Evento al pegar contenido
            montoDisplay.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                const numericValue = getNumericValue(pastedText);
                
                if (numericValue) {
                    montoDisplay.value = formatMoney(numericValue);
                    montoValue.value = numericValue;
                }
            });

            // Validación al enviar formulario
            const form = montoDisplay.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!montoValue.value) {
                        e.preventDefault();
                        montoDisplay.style.borderColor = '#ef4444';
                        alert('Por favor ingrese un monto válido');
                    }
                });
            }
        });
    </script>

<!-- Modal Nuevo Proveedor -->
<div id="proveedorModal" class="modal-overlay" style="display: none;">
    <div class="modal-container" style="max-width: 550px;">
        <div class="modal-header">
            <h2 class="modal-title">Registrar Nuevo Proveedor</h2>
            <button class="modal-close" id="btnCloseProveedorModal" type="button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="formNuevoProveedor" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin: 0;">
            
            <div class="modal-body">
                <!-- STEP 1 -->
                <div id="proveedorStep1">
                <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: var(--brand);">I. Información del Proveedor</h3>
                <div style="margin-bottom: 10px;">
                    <label style="font-weight:bold;">RUT <span style="color:red">*</span></label>
                    <input type="text" class="input" id="prov_rut" required style="width: 100%;" pattern="^[0-9]+-[0-9kK]{1}$" title="Ejemplo: 12345678-9" oninput="this.value = this.value.replace(/[^0-9kK-]/g, '')" >
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-weight:bold;">Nombre de Fantasía <span style="color:red">*</span></label>
                    <input type="text" class="input" id="prov_nombre" required style="width: 100%;" minlength="2" >
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-weight:bold;">Razón Social <span style="color:red">*</span></label>
                    <input type="text" class="input" id="prov_razon_social" required style="width: 100%;" minlength="2" >
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-weight:bold;">Dirección <span style="color:red">*</span></label>
                    <input type="text" class="input" id="prov_direccion" required style="width: 100%;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-weight:bold;">Comuna <span style="color:red">*</span></label>
                    <input type="text" class="input" id="prov_comuna" required style="width: 100%;" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]/g, '')" >
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-weight:bold;">Región <span style="color:red">*</span></label>
                    <input type="text" class="input" id="prov_region" required style="width: 100%;" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]/g, '')" >
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-weight:bold;">Teléfono <span style="color:red">*</span></label>
                    <input type="text" class="input" id="prov_telefono" required style="width: 100%;" pattern="^\+?[0-9]{8,15}$" oninput="this.value = this.value.replace(/[^0-9+]/g, '')" >
                </div>
                </div>
                <!-- STEP 2 -->
                <div id="proveedorStep2" style="display: none;">
                    <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: var(--brand);">II. Información Bancaria</h3>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight:bold;">N° Cuenta <span style="color:red">*</span></label>
                        <input type="text" class="input" id="prov_numero_cuenta" required style="width: 100%;" pattern="^[0-9]+$" oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight:bold;">Tipo de Cuenta <span style="color:red">*</span></label>
                        <select id="prov_tipo_cuenta" required style="width: 100%;" class="input">
                            <option value="">Seleccionar...</option>
                            <option value="Cuenta Corriente">Cuenta Corriente</option>
                            <option value="Cuenta Vista">Cuenta Vista</option>
                            <option value="Cuenta RUT">Cuenta RUT</option>
                            <option value="Cuenta de Ahorro">Cuenta de Ahorro</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight:bold;">Banco <span style="color:red">*</span></label>
                        <input type="text" class="input" id="prov_banco" required style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight:bold;">Nombre Titular <span style="color:red">*</span></label>
                        <input type="text" class="input" id="prov_nombre_titular" required style="width: 100%;" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]/g, '')" >
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight:bold;">Rut Titular <span style="color:red">*</span></label>
                        <input type="text" class="input" id="prov_rut_titular" required style="width: 100%;" pattern="^[0-9]+-[0-9kK]{1}$" title="Ejemplo: 12345678-9" oninput="this.value = this.value.replace(/[^0-9kK-]/g, '')" >
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight:bold;">Correo <span style="color:red">*</span></label>
                        <input type="email" class="input" id="prov_correo" required style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight:bold;">Certificado Bancario (Solo PDF) <span style="color:red">*</span></label>
                        <input type="file" class="input" id="prov_certificado" required accept=".pdf" style="width: 100%;">
                    </div>
                </div>
            </div>
            
            <div class="modal-footer" id="proveedorModalFooter">
                <div id="footerStep1" style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-ghost" id="btnCancelProveedor" style="margin: 0;">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnNextStep1" style="margin: 0;">Siguiente</button>
                </div>
                <div id="footerStep2" style="display: none; gap: 10px;">
                    <button type="button" class="btn btn-ghost" id="btnPrevStep2" style="margin: 0;">Atrás</button>
                    <button type="submit" class="btn btn-primary" style="margin: 0;">Guardar Proveedor</button>
                </div>
            </div>
            
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnOpen = document.getElementById('btnOpenProveedorModal');
    const modal = document.getElementById('proveedorModal');
    const btnCancel = document.getElementById('btnCancelProveedor');
    const form = document.getElementById('formNuevoProveedor');
    const selectProveedor = document.getElementById('nombreProveedor');
    
    const step1 = document.getElementById('proveedorStep1');
    const step2 = document.getElementById('proveedorStep2');
    const btnNextStep1 = document.getElementById('btnNextStep1');
    const btnPrevStep2 = document.getElementById('btnPrevStep2');
    const footerStep1 = document.getElementById('footerStep1');
    const footerStep2 = document.getElementById('footerStep2');
    const btnClose = document.getElementById('btnCloseProveedorModal');

    if (btnOpen && modal) {
        btnOpen.addEventListener('click', (e) => {
            e.preventDefault();
            
                    form.reset();
            step1.style.display = 'block';
            step2.style.display = 'none';
            modal.style.display = 'flex';
        });

        const closeModal = () => {
            modal.style.display = 'none';
        };

        if (btnCancel) btnCancel.addEventListener('click', closeModal);
        if (btnClose) btnClose.addEventListener('click', closeModal);

        // Validar step1 y pasar a step2
        btnNextStep1.addEventListener('click', () => {
            const inputsStep1 = step1.querySelectorAll('input[required]');
            let allValid = true;
            inputsStep1.forEach(input => {
                if (!input.checkValidity()) {
                    allValid = false;
                    input.reportValidity();
                }
            });
            if (allValid) {
                step1.style.display = 'none';
                step2.style.display = 'block';
                footerStep1.style.display = 'none';
                footerStep2.style.display = 'flex';
            }
        });

        // Volver a step1
        btnPrevStep2.addEventListener('click', () => {
            step2.style.display = 'none';
            step1.style.display = 'block';
            footerStep1.style.display = 'flex';
            footerStep2.style.display = 'none';
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const rut = document.getElementById('prov_rut').value;
            const nombre = document.getElementById('prov_nombre').value;
            const razon_social = document.getElementById('prov_razon_social').value;
            const direccion = document.getElementById('prov_direccion').value;
            const comuna = document.getElementById('prov_comuna').value;
            const region = document.getElementById('prov_region').value;
            const telefono = document.getElementById('prov_telefono').value;

            const numero_cuenta = document.getElementById('prov_numero_cuenta').value;
            const tipo_cuenta = document.getElementById('prov_tipo_cuenta').value;
            const banco = document.getElementById('prov_banco').value;
            const nombre_titular = document.getElementById('prov_nombre_titular').value;
            const rut_titular = document.getElementById('prov_rut_titular').value;
            const correo = document.getElementById('prov_correo').value;
            const certificado = document.getElementById('prov_certificado') ? document.getElementById('prov_certificado').files[0] : null;

            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

            const formData = new FormData();
            formData.append('rut', rut);
            formData.append('nombre', nombre);
            formData.append('razon_social', razon_social);
            formData.append('direccion', direccion);
            formData.append('comuna', comuna);
            formData.append('region', region);
            formData.append('telefono', telefono);
            formData.append('numero_cuenta', numero_cuenta);
            formData.append('tipo_cuenta', tipo_cuenta);
            formData.append('banco', banco);
            formData.append('nombre_titular', nombre_titular);
            formData.append('rut_titular', rut_titular);
            formData.append('correo', correo);
            if (certificado) {
                formData.append('certificado_bancario', certificado);
            }

            fetch('/proveedores/ajax-create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.proveedor) {
                    const newOption = new Option(data.proveedor.nombre, data.proveedor.nombre, true, true);
                    newOption.setAttribute('data-rut', data.proveedor.rut); // Set the rut so it auto-fills
                    if(selectProveedor) {
                        selectProveedor.appendChild(newOption);
                        selectProveedor.value = data.proveedor.nombre;
                        
                        // Trigger change so event listeners update the RUT field
                        const event = new Event('change');
                        selectProveedor.dispatchEvent(event);
                    }
                    
                    
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Proveedor creado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    form.reset();
                    modal.style.display = 'none';
                    step2.style.display = 'none';
                    step1.style.display = 'block';
                } else {
                    let errorMsg = 'Error al crear proveedor. Verifica que todo esté correcto.';
                    if (data.errors) {
                        errorMsg = Object.values(data.errors).map(e => e.join(', ')).join('\n');
                    } else if (data.message) {
                        errorMsg = data.message;
                    }
                    Swal.fire({ icon: 'error', title: 'Error', text: errorMsg });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error al crear proveedor u ocurrió un problema de validación.' });
            });
        });
    }
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('input[name="adjunto"]');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0] ? e.target.files[0].name : 'No hay archivo adjunto.';
                const pTag = e.target.closest('.attachment-box').querySelector('p');
                if (pTag) {
                    pTag.textContent = fileName !== 'No hay archivo adjunto.' ? '✅ Archivo seleccionado: ' + fileName : 'No hay archivo adjunto.';
                }
            });
        }
    });
</script>
    </div>
    </div>
</body>
</html>
