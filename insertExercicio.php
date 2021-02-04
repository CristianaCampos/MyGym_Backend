<?php

$cn = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($cn, "mygym_app");

$encodedData = file_get_contents('php://input');
$decodedData = json_decode($encodedData);

$nome = $decodedData->nome;
$zonaMuscular = $decodedData->zonaMuscular;

$idUtilizador = $decodedData->userId;

$sqlIdExercicio = "SELECT id FROM exercicio WHERE nome='" . $nome . "' AND idUtilizador= $idUtilizador";
$rIdExercicio = mysqli_query($cn, $sqlIdExercicio);

if (mysqli_num_rows($rIdExercicio) == 0) {
    $sqlInserirExercicio = "INSERT INTO exercicio(nome, zonaMuscular, idUtilizador) VALUES ('$nome', '$zonaMuscular', $idUtilizador)";
    $rInserirExercicio = mysqli_query($cn, $sqlInserirExercicio);

    if ($rInserirExercicio) {
        $finalObj = (object) ['message' => "success"];
    } else {
        $finalObj = (object) ['message' => "failed"];
    }
} else {
    $finalObj = (object) ['message' => "failed"];
}

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

mysqli_close($cn);
