<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #334155; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; }
        .header { background: #0f6bb6; color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 30px; }
        .footer { font-size: 12px; color: #64748b; text-align: center; margin-top: 20px; }
        .comment-box { background: #f8fafc; border-left: 4px solid #0f6bb6; padding: 15px; margin: 20px 0; font-style: italic; }
        .details { margin-top: 20px; margin-bottom: 20px; }
        .details b { color: #0f6bb6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0;">Orden de Compra #{{ $oc->numero_oc }}</h2>
        </div>
        <div class="content">
            <p>Estimado/a <b>{{ $oc->proveedor }}</b>,</p>
            <p>Le informamos que se ha generado una nueva Orden de Compra para su revisión y gestión.</p>
            
            @if($comentario)
                <div class="comment-box">
                    <strong>Mensaje adicional:</strong><br>
                    {{ $comentario }}
                </div>
            @endif

            <div class="details">
                <p><b>Descripción:</b> {{ $oc->descripcion }}</p>
                <p><b>Monto Total:</b> ${{ number_format($oc->monto, 0, ',', '.') }}</p>
            </div>

            <p>Adjunto a este correo encontrará el documento PDF oficial de la Orden de Compra.</p>
            
            <p>Por favor, confirme la recepción de este documento y proceda con el despacho de los productos o la prestación de los servicios indicados.</p>
            
            <p>Atentamente,<br><b>Fundación SOFOFA</b></p>
        </div>
        <div class="footer">
            Este es un correo automático, por favor no responda directamente a este mensaje.
        </div>
    </div>
</body>
</html>
