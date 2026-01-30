<?php
header('Content-Type: application/json');

// Recibir datos
$data = json_decode(file_get_contents('php://input'), true);
$latitud = $data['latitud'] ?? null;
$longitud = $data['longitud'] ?? null;

$respuesta = [
    'latitud' => $latitud,
    'longitud' => $longitud
];

// Opcional: Obtener ciudad usando API de geocodificación inversa
if ($latitud && $longitud) {
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitud}&lon={$longitud}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MiApp/1.0');
    
    $resultado = curl_exec($ch);
    curl_close($ch);
    
    if ($resultado) {
        $geo = json_decode($resultado, true);
        $respuesta['ciudad'] = $geo['address']['city'] ?? 
                               $geo['address']['town'] ?? 
                               $geo['address']['village'] ?? 
                               'Desconocida';
        $respuesta['pais'] = $geo['address']['country'] ?? 'Desconocido';
    }
}

echo json_encode($respuesta);
?>