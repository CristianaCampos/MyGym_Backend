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

$aulaId = $obj->aulaId;

$query = "SELECT nome FROM aulagrupo WHERE id=$aulaId";
$result = mysqli_query($conn, $query);

$response = "";

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row["nome"];
        }

        $finalObj = (object) ['message' => "success", 'nome' => $response];
    } else {
        $finalObj = (object) ['message' => "no_aula_assigned", 'nome' => $response];
    }
} else {
    $finalObj = (object) ['message' => "error", 'nome' => $response];
}


echo json_encode($finalObj, JSON_PRETTY_PRINT);

mysqli_close($conn);
