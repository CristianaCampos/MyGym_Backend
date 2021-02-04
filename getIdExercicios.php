<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "mygym_app";

// Cria a ligação à BD
$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
// Verifica se a ligação falhou (ou teve sucesso)
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$json = file_get_contents('php://input');

$obj = json_decode($json);

$exercicioNome = $obj->exercicioNome;
$userId = $obj->userId;

$query = "SELECT id FROM exercicio WHERE nome='$exercicioNome' AND idUtilizador=$userId";
$result = mysqli_query($conn, $query);

$response = "";

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row["id"];
        }

        $finalObj = (object) ['message' => "success", 'id' => $response];
    } else {
        $finalObj = (object) ['message' => "no_exercicio_assigned", 'id' => $response];
    }
} else {
    $finalObj = (object) ['message' => "error", 'id' => $response];
}


echo json_encode($finalObj, JSON_PRETTY_PRINT);

mysqli_close($conn);
