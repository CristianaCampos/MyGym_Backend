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

//
// params: name
//

$json = file_get_contents('php://input');

$obj = json_decode($json);

$userId = $obj->userId;

$query = "SELECT * FROM aulagrupo WHERE idUtilizador=$userId";
$result = mysqli_query($conn, $query);

$response = array();

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $response[$i] = $row;
            $i++;
        }

        $finalObj = (object) ['message' => "success", 'aulas' => $response];
    } else {
        $finalObj = (object) ['message' => "no_aulas_assigned", 'aulas' => $response];
    }
} else {
    $finalObj = (object) ['message' => "error", 'aulas' => $response];
}


echo json_encode($finalObj, JSON_PRETTY_PRINT);

mysqli_close($conn);
