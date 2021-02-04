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

$nome = $obj->nome;
$diaSemana = $obj->diaSemana;

$idUtilizador = $obj->idUtilizador;
$id = $obj->id;

$query = "UPDATE aulagrupo
SET nome = '$nome', diaSemana= '$diaSemana'
WHERE idUtilizador = $idUtilizador AND id=$id";

$result = mysqli_query($conn, $query);

$response = array();

if ($result) {
    $finalObj = (object) ['message' => "success"];
} else {
    $finalObj = (object) ['message' => "error"];
}

echo json_encode($finalObj, JSON_PRETTY_PRINT);

mysqli_close($conn);
