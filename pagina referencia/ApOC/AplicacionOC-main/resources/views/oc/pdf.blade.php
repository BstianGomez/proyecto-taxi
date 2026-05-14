<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Compra {{ $oc->numero_oc }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #222;
            margin: 14px;
        }

        .page-info {
            text-align: right;
            font-size: 9px;
            margin-bottom: 8px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo-box {
            width: 26%;
            font-weight: 700;
            font-size: 28px;
            line-height: 1;
        }

        .logo-sub {
            font-size: 9px;
            font-weight: 600;
            margin-top: 4px;
        }

        .title-box {
            width: 46%;
            text-align: right;
        }

        .title-main {
            text-align: center;
            font-weight: 700;
            font-size: 18px;
            margin-top: 4px;
            letter-spacing: 0.8px;
        }

        .title-sub {
            text-align: center;
            font-size: 10px;
            margin-top: 2px;
            line-height: 1.4;
        }

        .right-box {
            width: 28%;
            text-align: right;
            font-size: 11px;
            line-height: 1.6;
        }

        .intro {
            border-top: 1px solid #444;
            padding-top: 6px;
            margin-top: 4px;
            margin-bottom: 6px;
            font-size: 10px;
        }

        .party-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .party-table td {
            padding: 1px 2px;
            vertical-align: top;
            font-size: 10px;
        }

        .party-label {
            width: 14%;
            font-weight: 700;
        }

        .party-value-left {
            width: 38%;
        }

        .party-label-right {
            width: 12%;
            font-weight: 700;
            text-align: right;
        }

        .party-value-right {
            width: 36%;
            text-align: left;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #444;
            margin-top: 4px;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #444;
            padding: 3px;
            font-size: 9px;
            vertical-align: top;
        }

        .detail-table th {
            text-align: center;
            font-weight: 700;
        }

        .text-right {
            text-align: right;
        }

        .totals-table {
            width: 30%;
            margin-left: auto;
            border-collapse: collapse;
            margin-top: 0;
            border-left: 1px solid #444;
            border-right: 1px solid #444;
            border-bottom: 1px solid #444;
        }

        .totals-table td {
            border-top: 1px solid #444;
            padding: 2px 4px;
            font-size: 9px;
        }

        .amount-text {
            margin-top: 6px;
            border: 1px solid #444;
            padding: 4px;
            font-size: 9px;
            font-weight: 600;
        }

        .billing-box {
            border: 1px solid #444;
            margin-top: 4px;
            padding: 5px;
            font-size: 9px;
            line-height: 1.45;
        }

        .billing-box .row {
            margin: 1px 0;
        }

        .approval-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .approval-table td {
            border: 1px solid #444;
            padding: 7px 4px;
            text-align: center;
            font-size: 9px;
            font-weight: 700;
        }

        .muted {
            color: #555;
        }
    </style>
</head>
<body>
    @php
        $cantidad = max((int) ($oc->cantidad ?? 1), 1);
        $monto = (float) ($oc->monto ?? 0);
        $tipoDoc = strtolower((string) ($oc->solicitud_tipo_documento ?? ''));
        $isAfectoIva = str_contains($tipoDoc, 'factura') && !str_contains($tipoDoc, 'exenta');
        $neto = round($monto, 2);
        $iva = $isAfectoIva ? round($neto * 0.19, 2) : 0;
        $total = $neto + $iva;
        $valorUnitario = $cantidad > 0 ? round($neto / $cantidad, 2) : $neto;
        $fechaOc = \Carbon\Carbon::parse($oc->created_at ?? now())->locale('es')->translatedFormat('d \\d\\e F \\d\\e Y');
    @endphp

    <div class="page-info">Página 1 de 1</div>

    <table class="header-table">
        <tr>
            <td class="logo-box">
                SOFOFA
                <div class="logo-sub">FUNDACIÓN DE CAPACITACIÓN</div>
            </td>
            <td class="title-box">
                <div class="title-main">ORDEN DE COMPRA</div>
                <div class="title-sub">Fundación Cap. SOFOFA<br>75998240-1</div>
            </td>
            <td class="right-box">
                N°PEDIDO : {{ $oc->numero_oc }}<br>
                <span class="muted">{{ ucfirst($fechaOc) }}</span>
            </td>
        </tr>
    </table>

    <div class="intro">
        Según vuestra cotización de {{ \Carbon\Carbon::parse($oc->created_at ?? now())->format('d.m.Y') }}, solicitamos proceder con la siguiente Orden de Compra:
    </div>

    <table class="party-table">
        <tr>
            <td class="party-label">Empresa</td>
            <td class="party-value-left">{{ $oc->proveedor ?? 'N/A' }}</td>
            <td class="party-label-right">EMAIL:</td>
            <td class="party-value-right">{{ $oc->email_proveedor ?: 'N/A' }}</td>
        </tr>
        <tr>
            <td class="party-label">Dirección</td>
            <td class="party-value-left">N/A</td>
            <td class="party-label-right">Fax:</td>
            <td class="party-value-right">N/A</td>
        </tr>
        <tr>
            <td class="party-label">Rut</td>
            <td class="party-value-left">{{ $oc->rut ?: 'N/A' }}</td>
            <td class="party-label-right">Teléfono</td>
            <td class="party-value-right">N/A</td>
        </tr>
        <tr>
            <td class="party-label">Atención Sr.</td>
            <td class="party-value-left">N/A</td>
            <td class="party-label-right"></td>
            <td class="party-value-right"></td>
        </tr>
    </table>

    <table class="detail-table">
        <thead>
            <tr>
                <th style="width:4%;">Pos.</th>
                <th style="width:7%;">CANT.</th>
                <th style="width:4%;">UN.</th>
                <th style="width:33%;">DESCRIPCION</th>
                <th style="width:8%;">VAL. UNIT. ($)</th>
                <th style="width:8%;">VALOR BASE ($)</th>
                <th style="width:9%;">DESCTO./RECARGO</th>
                <th style="width:8%;">VALOR NETO ($)</th>
                <th style="width:8%;">IVA ($)</th>
                <th style="width:11%;">VALOR TOTAL ($)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-right">010</td>
                <td class="text-right">{{ number_format($cantidad, 0, ',', '.') }}</td>
                <td class="text-right">UN</td>
                <td>{{ $oc->descripcion ?: 'N/A' }}</td>
                <td class="text-right">{{ number_format($valorUnitario, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($neto, 0, ',', '.') }}</td>
                <td class="text-right">0</td>
                <td class="text-right">{{ number_format($neto, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($iva, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="10" style="height:240px;"></td>
            </tr>
        </tbody>
    </table>

    <table class="totals-table">
        <tr><td>NETO</td><td class="text-right">{{ number_format($neto, 0, ',', '.') }}</td></tr>
        <tr><td>AFECTO</td><td class="text-right">{{ number_format($neto, 0, ',', '.') }}</td></tr>
        <tr><td>EXENTO</td><td class="text-right">{{ $isAfectoIva ? '0' : number_format($neto, 0, ',', '.') }}</td></tr>
        <tr><td>IVA</td><td class="text-right">{{ number_format($iva, 0, ',', '.') }}</td></tr>
        <tr><td><strong>TOTAL</strong></td><td class="text-right"><strong>{{ number_format($total, 0, ',', '.') }}</strong></td></tr>
    </table>

    <div class="amount-text">Son : ${{ number_format($total, 0, ',', '.') }}</div>

    <div class="billing-box">
        <div class="row"><strong>FACTURAR A :</strong> Fundación de Capacitación SOFOFA</div>
        <div class="row"><strong>RUT :</strong> 75998240-1</div>
        <div class="row"><strong>DIRECCION :</strong> Nueva York 33 Piso 15&nbsp;&nbsp;Santiago</div>
        <div class="row"><strong>FONO :</strong> (56-2) 28995100</div>
        <div class="row"><strong>GIRO :</strong> Capacitación</div>
        <div class="row"><strong>DESPACHO MERCADERIAS A :</strong> Centro Fundación Nueva York 33, Piso 15</div>
        <div class="row"><strong>DESPACHO FACTURAS A :</strong> intercambio.fundasofa@adcele.cl (Envío DTE en Formato XML)</div>
        <div class="row"><strong>CONDICIONES DE PAGO :</strong> P003 Pago 30 días corridos sin DPP.</div>
        <div class="row"><strong>PLAZO DE ENTREGA :</strong> N/A</div>
        <div class="row"><strong>SOLICITADO POR :</strong> Sistema OC</div>
        <div class="row"><strong>OBSERVACIONES :</strong> Uso Interno: {{ $oc->descripcion ?: 'N/A' }}</div>
    </div>

    <table class="approval-table">
        <tr>
            <td>V°B° ADQUISICIONES</td>
            <td>V°B° CONTABILIDAD</td>
            <td>V°B° AUTORIZACION FINAL</td>
        </tr>
    </table>
</body>
</html>
