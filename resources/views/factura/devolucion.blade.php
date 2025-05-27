<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura de Devolución</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Factura de Devolución #{{ $factura->numero_factura }}</h1>
    <p><strong>Fecha de emisión:</strong> {{ $factura->fecha_emision }}</p>
    <p><strong>Usuario:</strong> {{ $factura->prestamo->usuario->name }}</p>

    <h3>Bienes Devueltos</h3>
    <table>
        <thead>
            <tr>
                <th>Bien</th>
                <th>Cantidad Devuelta</th>
                <th>Estado de Devolución</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factura->prestamo->bienes as $bien)
                <tr>
                    <td>{{ $bien->nombre }}</td>
                    <td>{{ $bien->pivot->cantidad_devuelta }}</td>
                    <td>{{ ucfirst($bien->pivot->estado_devolucion) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
