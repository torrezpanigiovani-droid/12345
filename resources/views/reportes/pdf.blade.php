<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#222; }
        h1 { font-size: 18px; margin:0; } .sub { color:#666; margin-bottom:12px; }
        table { width:100%; border-collapse:collapse; }
        th { background:#007bff; color:#fff; padding:6px; text-align:left; }
        td { border-bottom:1px solid #ddd; padding:5px 6px; }
        tr:nth-child(even) td { background:#f6f8fa; }
        .foot { margin-top:14px; color:#888; font-size:10px; }
    </style>
</head>
<body>
    <h1>ParkControl · {{ $r['titulo'] }}</h1>
    <div class="sub">Periodo: {{ $r['periodo'] }}</div>
    <table>
        <thead><tr>@foreach($r['columnas'] as $c)<th>{{ $c }}</th>@endforeach</tr></thead>
        <tbody>
        @forelse($r['filas'] as $fila)
            <tr>@foreach($fila as $v)<td>{{ $v }}</td>@endforeach</tr>
        @empty
            <tr><td colspan="{{ count($r['columnas']) }}">Sin datos en el periodo seleccionado.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="foot">Generado el {{ $generado }}</div>
</body>
</html>
