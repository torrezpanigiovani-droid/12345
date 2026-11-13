<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $mov->ticket }}</title>
    <style>
        body { font-family: 'Courier New', monospace; background:#eee; margin:0; padding:20px; }
        .ticket { width:300px; margin:auto; background:#fff; padding:16px; border:1px dashed #333; text-align:center; }
        h2 { margin:0 0 4px; } hr { border:0; border-top:1px dashed #333; margin:10px 0; }
        table { width:100%; font-size:14px; text-align:left; } td:last-child { text-align:right; font-weight:bold; }
        .big { font-size:22px; font-weight:bold; letter-spacing:1px; }
        button { margin-top:14px; padding:8px 18px; cursor:pointer; }
        @media print { body { background:#fff; padding:0; } button { display:none; } .ticket { border:0; } }
    </style>
</head>
<body>
<div class="ticket">
    <h2>ParkControl</h2>
    <div>{{ $mov->estado === 'activo' ? 'TICKET DE ENTRADA' : 'COMPROBANTE DE SALIDA' }}</div>
    <hr>
    <div class="big">{{ $mov->vehiculo->placa }}</div>
    <div id="qr" style="display:inline-block;margin:10px 0"></div>
    <div><b>{{ $mov->ticket }}</b></div>
    <hr>
    <table>
        <tr><td>Tipo</td><td>{{ ucfirst($mov->vehiculo->tipo) }}</td></tr>
        <tr><td>Celda</td><td>{{ $mov->celda->codigo }}</td></tr>
        <tr><td>Ubicación</td><td>{{ $mov->celda->sector->piso->nombre }} / {{ $mov->celda->sector->nombre }}</td></tr>
        <tr><td>Entrada</td><td>{{ $mov->entrada_at->format('d/m/Y H:i') }}</td></tr>
        @if($mov->estado === 'finalizado')
            <tr><td>Salida</td><td>{{ $mov->salida_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Tiempo</td><td>{{ $mov->minutos }} min</td></tr>
            <tr><td>Método</td><td>{{ strtoupper($mov->pago->metodo ?? '-') }}</td></tr>
            <tr><td>TOTAL</td><td>${{ number_format($mov->monto, 2) }}</td></tr>
        @endif
        <tr><td>Atendió</td><td>{{ $mov->user->name }}</td></tr>
    </table>
    <hr>
    <small>Conserve este ticket. Gracias por su visita.</small><br>
    <button onclick="window.print()">Imprimir</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>new QRCode(document.getElementById('qr'), { text: @json($mov->ticket), width: 120, height: 120 });</script>
</body>
</html>
