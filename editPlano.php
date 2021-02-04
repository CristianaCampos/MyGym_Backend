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
$exercicio1 = $obj->exercicio1;
$exercicio2 = $obj->exercicio2;
$exercicio3 = $obj->exercicio3;
$aula1 = $obj->aula1;
$aula2 = $obj->aula2;

$idUtilizador = $obj->idUtilizador;
$id = $obj->id;

$idEx1 = getExercicioIdByName($conn, $exercicio1);
$idEx2 = getExercicioIdByName($conn, $exercicio2);
$idEx3 = getExercicioIdByName($conn, $exercicio3);

$idAula1 = getAulaIdByName($conn, $aula1);
$idAula2 = getAulaIdByName($conn, $aula2);

$query = "UPDATE planotreino
    SET nome = '$nome', diaSemana= '$diaSemana', idEx1 = $idEx1, idEx2 = $idEx2, idEx3 = $idEx3, idAula1 = $idAula1, idAula2 = $idAula2
    WHERE id = $id AND idUtilizador = $idUtilizador";
$result = mysqli_query($conn, $query);

if ($result) {
    $finalObj = (object) ['message' => "success"];
} else {
    $finalObj = (object) ['message' => "errorUpdatePlano"];
}

echo json_encode($finalObj, JSON_PRETTY_PRINT);

function getExercicioIdByName($conn, $nomeExercicio)
{
    $id = 0;

    if ($nomeExercicio != "---") {

        $query = "SELECT id FROM exercicio WHERE nome='$nomeExercicio'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $id = $row["id"];
                }
            }
        }
    } else {
        $id = 0;
    }

    return $id;
}

function getAulaIdByName($conn, $nomeAula)
{
    $id = 0;

    if ($nomeAula != "---") {

        $query = "SELECT id FROM aulagrupo WHERE nome='$nomeAula'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $id = $row["id"];
                }
            }
        }
    } else {
        $id = 0;
    }

    return $id;
}

mysqli_close($conn);
