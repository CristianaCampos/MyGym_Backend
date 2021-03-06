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

$id = $obj->id;
$idUser = $obj->userId;

$query = "DELETE FROM aulagrupo WHERE id=$id;";
$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_affected_rows($conn) > 0) {
        $finalObj = (object) ['message' => "success"];
    } else {
        $finalObj = (object) ['message' => "delete_failed"];
    }
} else {
    $finalObj = (object) ['message' => "error"];
}

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

mysqli_close($conn);
