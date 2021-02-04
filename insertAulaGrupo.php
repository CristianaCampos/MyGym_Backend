<?php

$cn = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($cn, "mygym_app");

$json = file_get_contents('php://input');

$obj = json_decode($json);

$nome = $obj->nome;
$diaSemana = $obj->diaSemana;

$idUtilizador = $obj->userId;

$sqlIdAula = "SELECT id FROM aulagrupo WHERE nome='$nome' AND idUtilizador= $idUtilizador";
$rIdAula = mysqli_query($cn, $sqlIdAula);

if (mysqli_num_rows($rIdAula) == 0) {
    $sqlInserirAula = "INSERT INTO aulagrupo(nome, diaSemana, idUtilizador) VALUES ('$nome', '$diaSemana', $idUtilizador)";
    $rInserirAula = mysqli_query($cn, $sqlInserirAula);

    if ($rInserirAula) {
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
